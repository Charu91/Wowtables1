<?php namespace WowTables\Http\Models;

use DB;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Auth\Authenticatable;
use Session;
use Cookie;

/**
 * Class Facebook
 * @package WowTables\Http\Models
 */
class Facebook {

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

            //Adding user membershipId to the database
            DB::table('user_attributes_varchar')->insert([
               'user_id' => $user_id,
               'user_attribute_id' => 7,
               'attribute_value' => '1'.str_pad($user_id, 6, '0', STR_PAD_LEFT),
           ]); 

            if($this->auth->loginUsingId($user_id)){
                $this->role = 'Gourmet';

                //setting up the session
                $user_array  =  Auth::user();
                $userdata = array(
                                'id'  => $user_array->id,
                                'username'  => substr($user_array->email,0,strpos($user_array->email,"@")),
                                'email'     => $user_array->email,
                                'full_name' =>$user_array->full_name,
                                'user_role' =>$user_array->role_id,
                                'phone'     =>$user_array->phone_number,
                                'city_id'   =>$user_array->location_id,
                                'facebook_id'=>@$user_array->fb_token,
                                'exp'=>"10",
                                'logged_in' => TRUE,
                            );
            Session::put($userdata);

            $order = unserialize(Cookie::get('order'));

                return ['state' => 'success', 'location' => false];

            } 
            else{
                return [
                    'state' => 'failure',
                    'message' => 'Sorry we had a problem. Please try again or contact us of still unsuccessful'
                ];
            }
        }
        else{
            if($user[0]->fb_token_exists) { 

                if($user[0]->location_id) {
                    $location_slug = DB::table('locations')->where('id', $user[0]->location_id)->pluck('slug');

                    if($location_slug){
                        $retarr = ['status' => 'success', 'location' => $location_slug];
                    }else{
                        $retarr = ['status' => 'success', 'location' => false];
                    }
                } 
                else {
                    $retarr = ['status' => 'success', 'location' => false];
                }
            }
            else if($user[0]->email_exists) {
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
                } 
                else{
                    $retarr = ['status' => 'success', 'location' => false];
                }
            }

            if($this->auth->loginUsingId($user[0]->id)) {
                $this->role = 'Gourmet';

                $user_array  =  $this->auth->user();
                $userdata = array(
                                'id'  => $user_array->id,
                                'username'  => substr($user_array->email,0,strpos($user_array->email,"@")),
                                'email'     => $user_array->email,
                                'full_name' =>$user_array->full_name,
                                'user_role' =>$user_array->role_id,
                                'phone'     =>$user_array->phone_number,
                                'city_id'   =>$user_array->location_id,
                                'facebook_id'=>@$user_array->fb_token,
                                'exp'=>"10",
                                'logged_in' => TRUE,
                            );
            Session::put($userdata);

            $order = unserialize(Cookie::get('order'));


                return $retarr;
            }else{
                return [
                    'state' => 'failure',
                    'message' => 'Sorry we had a problem. Please try again or contact us of still unsuccessful'
                ];
            }
        }
    }

    //-----------------------------------------------------------------

    /**
     *
     */
    public function addUserCity($cityName) {
        $resultCity = DB::table('locations')
                        ->where('name', '=',$cityName)
                        ->select('id')->first();

        if($resultCity) {
            //reading user id from session
            $userId = Session::get('id');

            $userResult = DB::table('users')->where('id','=',$userId)
                            ->update(['location_id' => $resultCity->id]);

        }
    }

    //-----------------------------------------------------------------

    /**
     *
     */
    public function readUserCity() {
        //reading the values form the session
        $userId = Session::get('id');
        $cityId = Session::get('city_id');


        $resultCity = DB::table('locations')
                            ->join('users', 'users.location_id','=','locations.id')
                            ->where('users.id',$userId)
                            ->where('locations.id', $cityId)
                            ->select('slug')
                            ->first();
        if ($resultCity) {
            return $resultCity->slug;
        } 

        return false;
    }

}