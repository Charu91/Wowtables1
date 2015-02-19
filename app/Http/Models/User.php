<?php namespace WowTables\Http\Models;

use DB;
use Session;
use Log;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Auth\Authenticatable;
use Rhumsaa\Uuid\Uuid;
use Illuminate\Contracts\Hashing\Hasher;

class User {

    use Authenticatable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

    /**
     * The authenticator implementation.
     *
     * @var object
     */
    public $auth;

    /**
     *
     * The role of the user
     *
     * @var string
     */
    public $role;

    /**
     * The permissions granted to the user
     *
     * @var array
     */

    protected $permissions = [];

    /**
     * The full name of the user
     *
     * @var string
     */
    public $full_name;

    /**
     * Array of all the user roles available
     *
     * @var array
     */
    protected $roles;

    /**
     * The Hasher Object
     *
     * @var object
     */
    protected $hasher;


    /**
     * The User Construct
     *
     * @param Authenticator $auth
     */
    public function __construct( Auth $auth, Roles $roles, Hasher $hasher )
    {
        $this->auth = $auth;
        $this->roles = $roles;
        $this->hasher = $hasher;

        if($this->auth->check()){
            if(empty($this->full_name)){
                $this->populateUserData($this->auth->user()->id);
            }
        }
    }

    /**
     *
     * Log a user
     * @param $email
     * @param $password
     * @param bool $remember
     *
     * @return bool
     */
    public function login( $email, $password, $remember = false )
    {
        $query = '
            SELECT
                u.`id`,
                u.`full_name`,
                u.`password`,
                u.`old_password`,
                u.`type`,
                r.`name` as `role`,
                p.`action`,
                p.`resource`
            FROM users AS `u`
            INNER JOIN roles AS `r` ON u.`role_id` = r.`id`
            LEFT JOIN role_permissions AS `rp` ON rp.`role_id` = r.`id`
            LEFT JOIN permissions AS `p` ON rp.`permission_id` = p.`id`
            WHERE u.`email` = ?
        ';

        $user = DB::select($query, [$email]);

        if($user){
            if($user[0]->type === 'old_site'){
                if(md5($password) === $user[0]->old_password){
                    $password = bcrypt($password);
                    $new_site_user_update = DB::update(
                        'UPDATE users SET password = ?, type = ? WHERE id = ?',
                        [$password, 'new_site', $user[0]->id]
                    );

                    if($new_site_user_update){
                        if($this->auth->loginUsingId($user[0]->id, $remember)){
                            $this->role = $user[0]->role;
                            $this->full_name = $user[0]->full_name;
                            if($user[0]->action){
                                foreach($user as $userPermission){
                                    if(!isset($this->permissions[$userPermission->resource]))
                                        $this->permissions[$userPermission->resource] = [];

                                    $this->permissions[$userPermission->resource][] = $userPermission->action;
                                }
                            }


                            return [ 'state' => 'success'];
                        }else{
                            return [
                                'state' => 'failure',
                                'message' => 'Sorry we had a problem. Please try again or contact us of still unsuccessful'
                            ];
                        }
                    }else{
                        return [
                            'state' => 'failure',
                            'message' => 'Sorry we had a problem. Please try again or contact us of still unsuccessful'
                        ];
                    }
                }else{
                    return [
                        'state' => 'failure',
                        'message' => 'The email address and password did not match'
                    ];
                }
            }else{

                if ($this->auth->attempt(['email' => $email, 'password' => $password], $remember)) {

                    $this->role = $user[0]->role;
                    $this->full_name = $user[0]->full_name;

                    if($user[0]->action){
                        foreach($user as $userPermission){
                            if(!isset($this->permissions[$userPermission->resource]))
                                $this->permissions[$userPermission->resource] = [];

                            $this->permissions[$userPermission->resource][] = $userPermission->action;
                        }
                    }

                    return [ 'state' => 'success'];
                } else {
                    return [
                        'state' => 'failure',
                        'message' => 'The email address and password did not match'
                    ];
                }

            }
        }else{
            return [
                'state' => 'failure',
                'message' => 'The email address has not been registered with us'
            ];
        }
    }

    /**
     * Register a user to gourmetitup
     *
     * @param $email
     * @param $password
     * @param $location_id
     */
    public function register($email, $password, $location_id)
    {
        $gourmetRoleId = DB::table('roles')->where('name', 'Gourmet')->pluck('id');

        $user_id = DB::table('users')->insertGetId([
            'email' => $email,
            'password' => bcrypt($password),
            'location_id' => $location_id,
            'role_id' => $gourmetRoleId
        ]);

        if($this->auth->loginUsingId($user_id)){
            $this->role = 'Gourmet';

            $location_slug = DB::table('locations')->where('id', $location_id)->where('type', 'City')->pluck('slug');

            if($location_slug){
                return ['state' => 'success', 'location' => $location_slug];
            }else{
                return ['state' => 'success', 'location' => false];
            }
        }else{
            return [
                'state' => 'failure',
                'message' => 'Sorry we had a problem. Please try again or contact us of still unsuccessful'
            ];
        }

    }

    /**
     * Login a facebook user into the system
     *
     * @param $token
     * @param $email
     */
    public function fb_login($token, $email, $full_name)
    {
        $query = '
            SELECT
                `id`,
                IF(`fb_token` IS NOT NULL, 1, 0) AS `fb_token_exists`,
                IF(`email` IS NOT NULL, 1, 0) AS `email_exists`,
                `location_id`
            FROM users
            WHERE `fb_token` = ? OR `email` = ?
        ';

        $user = DB::select($query, [$token, $email]);

        if(!$user){
            $gourmetRoleId = DB::table('roles')->where('name', 'Gourmet')->pluck('id');

            $user_id = DB::table('users')->insertGetId([
                'email'     => $email,
                'full_name' => $full_name,
                'fb_token'  => $token,
                'role_id'   => $gourmetRoleId
            ]);

            if($this->auth->loginUsingId($user_id)){
                $this->role = 'Gourmet';

                return ['state' => 'success', 'location' => false];
            }else{
                return [
                    'state' => 'failure',
                    'message' => 'Sorry we had a problem. Please try again or contact us of still unsuccessful'
                ];
            }
        }else{
            if($user[0]->fb_token_exists){
                if($user[0]->location_id){
                    $location_slug = DB::table('locations')->where('id', $user[0]->location_id)->pluck('slug');

                    if($location_slug){
                        $retarr = ['status' => 'success', 'location' => $location_slug];
                    }else{
                        $retarr = ['status' => 'success', 'location' => false];
                    }
                }else{
                    $retarr = ['status' => 'success', 'location' => false];
                }
            }else if($user[0]->email_exists){
                DB::table('users')->where('id', $user[0]->id)->update([
                    'fb_token' =>  $token,
                    'full_name' => $full_name
                ]);

                if($user[0]->location_id){
                    $location_slug = DB::table('locations')->where('id', $user[0]->location_id)->pluck('slug');

                    if($location_slug){
                        $retarr = ['status' => 'success', 'location' => $location_slug];
                    }else{
                        $retarr = ['status' => 'success', 'location' => false];
                    }
                }else{
                    $retarr = ['status' => 'success', 'location' => false];
                }
            }

            if($this->auth->loginUsingId($user[0]->id)){
                $this->role = 'Gourmet';

                return $retarr;
            }else{
                return [
                    'state' => 'failure',
                    'message' => 'Sorry we had a problem. Please try again or contact us of still unsuccessful'
                ];
            }
        }
    }

    /**
     * Generate and send a forgot password email
     *
     * @param $email
     */
    public function forgot_password($email)
    {
        $full_name = DB::table('users')->where('email', $email)->pluck('full_name');

        Mail::send('emails.forgot_password', array('key' => 'value'), function($message) use ($full_name)
        {
            $message->to('$email', !empty($full_name)? $full_name: 'WowTables User')->subject('Please reset your password');
        });

        return ['status' => 'success'];
    }

    /**
     * Set the location of an already signed in user
     *
     * @param $location_id
     */
    public function setLocation($location_id)
    {
        $location_slug = DB::table('locations')->where('id', $location_id)->pluck('slug');

        if($location_slug){
            DB::table('users')->where('id', $this->auth->user()->id)->update([
                'location_id' =>  $location_id
            ]);

            return ['status' => 'success', 'location' => $location_slug];
        }else{
            return [
                'status' => 'failure',
                'message' => 'The location you entred seems to be invalid. Please contact the website admin'
            ];
        }
    }


    public function mobileRegister(array $data)
    {
        DB::beginTransaction();

        $email_exists = DB::table('users')->where('email', $data['email'])->count();

        if (!$email_exists) {

            $gourmetRoleId = DB::table('roles')->where('name', 'Gourmet')->pluck('id');

            $userInsertId = DB::table('users')->insertGetId([
                'email' => $data['email'],
                'role_id' => $gourmetRoleId,
                'password' => bcrypt($data['password']),
                'location_id' => $data['location_id'],
                'phone_number' => $data['phone_number']
            ]);

            if($userInsertId){
                $access_token = Uuid::uuid1()->toString();
                $access_token_expiry = time() + (180 * 24 * 60 * 60);

                $query = '
                    INSERT INTO user_devices (
                      `device_id`,
                      `user_id`,
                      `access_token`,
                      `access_token_expires`,
                      `os_type`,
                      `os_version`,
                      `hardware`,
                      `app_version`
                    ) VALUES (?,?,?,FROM_UNIXTIME(?),?,?,?,?)
                    ON DUPLICATE KEY UPDATE
                      `user_id` = VALUES(user_id),
                      `access_token` = VALUES(access_token),
                      `access_token_expires` = VALUES(access_token_expires),
                      `os_type` = VALUES(os_type),
                      `os_version` = VALUES(os_version),
                      `hardware` = VALUES(hardware),
                      `app_version` = VALUES(app_version)
                ';

                $userDeviceInsert = DB::insert($query, [
                    $data['device_id'],
                    $userInsertId,
                    $access_token,
                    $access_token_expiry,
                    $data['os_type'],
                    $data['os_version'],
                    $data['hardware'],
                    $data['app_version']
                ]);

                if($userDeviceInsert){
                    DB::commit();
                    return [
                        'code' => 200,
                        'data' => [
                            'access_token' => $access_token
                        ]
                    ];
                }else{
                    DB::rollBack();
                    return [
                        'code' => 500,
                        'data' => [
                            'action' => 'Insert the user device or update if exists',
                            'message' => 'There was an issue processing your request. Please try again'
                        ]
                    ];
                }
            }else{
                DB::rollBack();
                return [
                    'code' => 500,
                    'data' => [
                        'action' => 'Inserting the newly registered user to the DB',
                        'message' => 'There was an issue processing your request. Please try again'
                    ]
                ];
            }
        } else {
            DB::rollBack();
            return [
                'code' => 225,
                'data' => [
                    'action' => 'Check if email already is registered with WowTables',
                    'message' => 'You are already registered with WowTables. Please sign in instead'
                ]
            ];
        }
    }

    public function mobileLogin(array $data)
    {

        dd($data);
        DB::beginTransaction();

        $user = DB::table('users')
                        ->select('password', 'id', 'location_id', 'phone_number')
                        ->where('email', $data['email'])
                        ->first();

        if ($user) {
            if($this->hasher->check($data['password'], $user->password)){

                $access_token = Uuid::uuid1()->toString();
                $access_token_expiry = time() + (360 * 24 * 60 * 60);

                $query = '
                    INSERT INTO user_devices (
                      `device_id`,
                      `user_id`,
                      `access_token`,
                      `access_token_expires`,
                      `os_type`,
                      `os_version`,
                      `hardware`,
                      `app_version`
                    ) VALUES (?,?,?,FROM_UNIXTIME(?),?,?,?,?)
                    ON DUPLICATE KEY UPDATE
                      `user_id` = VALUES(user_id),
                      `access_token` = VALUES(access_token),
                      `access_token_expires` = VALUES(access_token_expires),
                      `os_type` = VALUES(os_type),
                      `os_version` = VALUES(os_version),
                      `hardware` = VALUES(hardware),
                      `app_version` = VALUES(app_version)
                ';

                $userDeviceInsert = DB::insert($query, [
                    $data['device_id'],
                    $user->id,
                    $access_token,
                    $access_token_expiry,
                    $data['os_type'],
                    $data['os_version'],
                    $data['hardware'],
                    $data['app_version']
                ]);

                if($userDeviceInsert){

                    if(!empty($user->location_id)){
                        $location_id = $user->location_id;
                        $location_name = DB::table('locations')->where('id', $location_id)->pluck('name');

                        if(!$location_name){
                            $location_id = null;
                            $location_name = null;
                        }
                    }else{
                        $location_id = null;
                        $location_name = null;

                    }

                    DB::commit();
                    return [
                        'code' => 200,
                        'data' => [
                            'access_token' => $access_token,
                            'location_id' => $location_id,
                            'location_name' => $location_name,
                            'phone_number' => $user->phone_number
                        ]
                    ];
                }else{
                    DB::rollBack();
                    return [
                        'code' => 500,
                        'data' => [
                            'action' => 'Insert the user device or update if exists',
                            'message' => 'There was an issue processing your request. Please try again'
                        ]

                    ];
                }
            }else{
                DB::rollBack();
                return [
                    'code' => 227,
                    'data' => [
                        'action' => 'Check if the email address and password match',
                        'message' => 'There is an email password mismatch. Please check an try again'
                    ]
                ];
            }
        } else {
            DB::rollBack();
            return [
                'code' => 226,
                'data' => [
                    'action' => 'Check if email is registered with WowTables',
                    'message' => 'The email you are trying to signin from is not registered with us. Please register first'
                ]
            ];
        }
    }

    public function mobileFbLogin(array $data)
    {
        DB::beginTransaction();

        $query = '
            SELECT
                `id`,
                IF(`fb_token` IS NOT NULL, 1, 0) AS `fb_token_exists`,
                IF(`email` IS NOT NULL, 1, 0) AS `email_exists`,
                `location_id`,
                `phone_number`
            FROM users
            WHERE `fb_token` = ? OR `email` = ?
            ORDER BY `fb_token`
        ';

        $userResult = DB::select($query, [$data['token'], $data['email']]);

        if(!$userResult) {
            $gourmetRoleId = DB::table('roles')->where('name', 'Gourmet')->pluck('id');

            $user_id = DB::table('users')->insertGetId([
                'email' => $data['email'],
                'full_name' => $data['full_name'],
                'fb_token' => $data['token'],
                'role_id' => $gourmetRoleId
            ]);

            $location_id = null;
            $phone_number = null;

        }else{

            $fb_token_exists = false;
            $email_exists = false;
            $fb_user_id = $userResult[0]->id;
            $email_user_id = $userResult[0]->id;
            $fb_user_location_id = $userResult[0]->location_id;
            $email_user_location_id = $userResult[0]->location_id;
            $fb_user_phone_number = $userResult[0]->phone_number;
            $email_user_phone_number = $userResult[0]->phone_number;

            foreach($userResult as $user){
                if($user->fb_token_exists){
                    $fb_user_id = $user->id;
                    $fb_token_exists = true;
                    $fb_user_location_id = $user->location_id;
                }else if($user->email_exists){
                    $email_user_id = $user->id;
                    $email_exists = true;
                    $email_user_location_id = $user->location_id;
                }
            }

            if(!$fb_token_exists && $email_exists){
                $user_id = $email_user_id;
                $location_id = $email_user_location_id;
                $phone_number = $email_user_phone_number;
                DB::table('users')->where('email', $data['email'])->update([
                    'fb_token' => $data['fb_token'],
                    'full_name' => $data['full_name']
                ]);
            }

            if(!isset($user_id)) $user_id = $fb_user_id;
            if(!isset($location_id)) $location_id = $fb_user_location_id;
            if(!isset($phone_number)) $phone_number = $fb_user_phone_number;
        }

        $access_token = Uuid::uuid1()->toString();
        $access_token_expiry = time() + (360 * 24 * 60 * 60);

        $query = '
                    INSERT INTO user_devices (
                      `device_id`,
                      `user_id`,
                      `access_token`,
                      `access_token_expires`,
                      `os_type`,
                      `os_version`,
                      `hardware`,
                      `app_version`
                    ) VALUES (?,?,?,FROM_UNIXTIME(?),?,?,?,?)
                    ON DUPLICATE KEY UPDATE
                      `user_id` = VALUES(user_id),
                      `access_token` = VALUES(access_token),
                      `access_token_expires` = VALUES(access_token_expires),
                      `os_type` = VALUES(os_type),
                      `os_version` = VALUES(os_version),
                      `hardware` = VALUES(hardware),
                      `app_version` = VALUES(app_version)
                ';

        $userDeviceInsert = DB::insert($query, [
            $data['device_id'],
            $user_id,
            $access_token,
            $access_token_expiry,
            $data['os_type'],
            $data['os_version'],
            $data['hardware'],
            $data['app_version']
        ]);

        if($userDeviceInsert){

            if(!empty($location_id)){
                $location_name = DB::table('locations')->where('id', $location_id)->pluck('name');

                if(!$location_name){
                    $location_id = null;
                    $location_name = null;
                }
            }else{
                $location_id = null;
                $location_name = null;

            }

            DB::commit();
            return [
                'code' => 200,
                'data' => [
                    'access_token' => $access_token,
                    'location_id' => $location_id,
                    'location_name' => $location_name,
                    'phone_number' => $phone_number
                ]
            ];
        }else{
            DB::rollBack();
            return [
                'code' => 500,
                'data' => [
                    'action' => 'Insert the user device or update if exists',
                    'message' => 'There was an issue processing your request. Please try again'
                ]

            ];
        }
    }
    /**
     *
     * Get whether the user can access a particular resource
     *
     * @param $action
     * @param $resource
     *
     * @return bool
     */
    public function can( $action, $resource )
    {
        if(isset($this->permissions['all']) && in_array('admin',$this->permissions['all'])){
           return true;
        }else if(isset($this->permissions[$action]) && in_array($action, $this->permissions[$resource])){
            return true;
        }

        return false;
    }

    /**
     * @param $role_name
     *
     * @return bool
     */
    public function hasRole( $role_name )
    {
        return strtolower($role_name) === strtolower($this->role);
    }

    /**
     * Create a different user if you have permission to do so
     *
     * @param $email
     * @param $password
     * @param $location_id
     * @param $role_id
     *
     * @return array
     */
    public function create($data)
    {

        DB::beginTransaction();

        $userId = DB::table('users')->insertGetId([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'location_id' => $data['location_id'],
            'role_id' => $data['role_id'],
            'newsletter_frequency' => (isset($data['newsletter_frequency']))? $data['newsletter_frequency']:'Weekly'
        ]);

        if($userId){
            if(!empty($data['attributes']) && is_array($data['attributes'])){
                $insertArray = [];
                foreach($data['attributes'] as $attribute => $value){
                    if(!empty($value)) {
                        if (is_array($value)) {
                            $insertArray[] = ['user_id' => $userId, 'meta_key' => $attribute, 'meta_value' => json_encode($value)];
                        } else {
                            $insertArray[] = ['user_id' => $userId, 'meta_key' => $attribute, 'meta_value' => $value];
                        }
                    }
                }

                if(count($insertArray)){
                    if(DB::table('user_meta')->insert($insertArray)){
                        return ['status'=> 'success'];
                    }else{
                        DB::rollBack();
                        return [
                            'status' => 'failure',
                            'action' => 'Inserting user meta information',
                            'message' => 'The user could not be created. Please contact the sys admin'
                        ];
                    }
                }else{
                    DB::commit();
                    return ['status' => 'success'];
                }
            }else{
                DB::commit();
                return ['status' => 'success'];
            }
        }else{
            DB::rollBack();
            return [
                'status' => 'failure',
                'action' => 'Inserting the user into the DB',
                'message' => 'The user could not be created. Please contact the sys admin'
            ];
        }
    }

    /**
     * Log the user out
     *
     * @return array
     */
    public function logout(){
        $this->auth->logout();

        return ['status' => 'success'];
    }


    /**
     * Populate the basic user details
     *
     * @param $user_id
     *
     * @return void
     */
    private function populateUserData($user_id)
    {
        $query = '
            SELECT
                u.`id`,
                u.`full_name`,
                u.`password`,
                u.`old_password`,
                r.`name` as `role`,
                p.`action`,
                p.`resource`
            FROM users AS `u`
            INNER JOIN roles AS `r` ON u.`role_id` = r.`id`
            LEFT JOIN role_permissions AS `rp` ON rp.`role_id` = r.`id`
            LEFT JOIN permissions AS `p` ON rp.`permission_id` = p.`id`
            WHERE u.`id` = ?
        ';

        $user = $user = DB::select($query, [$user_id]);

        if($user){
            $this->role = $user[0]->role;
            $this->full_name = $user[0]->full_name;
            if($user[0]->action){
                foreach($user as $userPermission){
                    if(!isset($this->permissions[$userPermission->resource]))
                        $this->permissions[$userPermission->resource] = [];

                    $this->permissions[$userPermission->resource][] = $userPermission->action;
                }
            }
        }else{
            Log::error('Houston We have a problem!!');
        }
    }
}