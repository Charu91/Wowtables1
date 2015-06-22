<?php namespace WowTables\Http\Models;

use DB;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Auth\Authenticatable;

/**
 * Class Facebook
 * @package WowTables\Http\Models
 */
class User {

	use Authenticatable;

    /**
     * The authenticator implementation.
     *
     * @var object
     */
    public $auth;
    
    /**
     * The full name of the user
     *
     * @var string
     */

    public $role;
    /**
     * The full name of the user
     *
     * @var string
     */

    public $full_name;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
     * The User Construct
     *
     * @param 
     */
    public function __construct( Auth $auth)
    {
    	// Hasher $hasher, Config $config 
        $this->auth = $auth;
        //$this->roles = $roles;
        //$this->hasher = $hasher;
        //$this->config = $config;

        
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

}