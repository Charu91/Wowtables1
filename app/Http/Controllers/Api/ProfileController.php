<?php  namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Profile;
use Request;
use Config;
use Validator;

class ProfileController extends Controller {

    /**
     * Show the Profile Detail of the user
     *
     * @access	public
     * @param	string	$data
     * @return	json
     * @since	1.0.0
     */
    public function show() {

        $token=$_SERVER['HTTP_X_WOW_TOKEN'];

        //array to store response
        $arrResponse=array();

        $data=array('access_token' => $token);

        //Validate user's access_token exist or not
        $validator = Validator::make($data,Profile::$arrMyProfileRule);

        if($validator->fails()){
            $message=$validator->messages();
            $errorMessage="";
            foreach($data as $key => $value) {
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n ';
                }
            }
            $arrResponse['status'] = Config::get('constants.API_ERROR');
            $arrResponse['msg'] = $errorMessage;
        }
        else{
            $arrResponse=Profile::getUserProfile($token);
        }

        return response()->json( $arrResponse,200);
    }

    //---------------------------------------------------------------------

    /**
     * Update the Profile Detail of the user
     *
     * @access	public
     * @param	string	$data
     * @return	json
     * @since	1.0.0
     */
    public function update() {
        //array to store response
        $arrResponse=array();

        //read data input by the user
        $data = Request::all();

        $data['access_token']=$_SERVER['HTTP_X_WOW_TOKEN'];

        //print_r($data); die();
        //Validation user's profile data
        $validator = Validator::make($data,Profile::$arrRules);

        if($validator->fails()){
            $message=$validator->messages();
            $errorMessage="";
            foreach($data as $key => $value) {
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n ';
                }
            }
            $arrResponse['status'] = Config::get('constants.API_ERROR');
            $arrResponse['msg'] = $errorMessage;
        }
        else{
            $arrResponse=Profile::updateProfile($data);
        }

        return response()->json( $arrResponse,200);
    }

    //---------------------------------------------------------------------

    /**
     * Set the Preferred Areas for the user
     *
     * @access	public
     * @param	string	$area
     * @return	json
     * @since	1.0.0
     */
    public function setPreferredAreas() {

        $area = Request::all();
        return response()->json(Profile::savePreferredAreas($area),200);
    }

    //---------------------------------------------------------------------

    /**
     * List the Preferred Areas for the user
     *
     * @access	public
     * @param	string	$userID
     * @return	json
     * @since	1.0.0
     */
    public function getPreferredArea() {

        $token=$_SERVER['HTTP_X_WOW_TOKEN'];
        $area = Request::all();
        return response()->json(Profile::getUserPreferences($token),200);
    }
} 