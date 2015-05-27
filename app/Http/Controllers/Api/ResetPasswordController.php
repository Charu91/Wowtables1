<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Password;
use Request;
use Validator;
use Config;
/**
 * Controller class PasswordController.
 *
 * @package  Wowtables
 * @version  1.0.0
 * @since    1.0.0
 * @author   Parth Shukla <parthshukla@ahex.co.in>
 */
class ResetPasswordController extends Controller {

	/**
     * Method to create new token for forgot password request.
     *
     * @access  public
     * @return  response
     * @since   1.0.0
     */
	public function forgotPasswordRequest() {

		$data = Request::all();

		//validating user submitted data
        $validator = Validator::make($data,Password::$arrPasswordRule);

        if($validator->fails()){
            $message=$validator->messages();
            $errorMessage="";
            foreach($data as $key => $value) {
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n';
                }
            }
            $arrResponse['status'] = Config::get('constants.API_ERROR');
            $arrResponse['msg'] = $errorMessage;
            return response()->json($arrResponse,200);
        }
        else{
            return response()->json(Password::requestPassword($data),200);
        }       

	}

	//-----------------------------------------------------------------

    /**
     * Updates the user existing password.
     *
     * @access  public
     * @return  response
     * @since   1.0.0
     */
	public function newPassword() {

		$data = Request::all();

		//Validation user's profile data
        $validator = Validator::make($data,Password::$arrPasswordConfirm);        

        if($validator->fails()){
            $message=$validator->messages();
            foreach($data as $key => $value) {
                $errorMessage = '';
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n ';
                }
            }
            $arrResponse['status'] = Config::get('constants.API_ERROR');
            $arrResponse['msg'] = $errorMessage;
            return response()->json($arrResponse,200);
        }
        else{

             //print_r($data['password']) ; die('..Ready Here');
            $arrData['password']=$data['password'];
            $arrData['token']=$data['token'];
            return response()->json(Password::updatePasswordDatabase($arrData),200);
        }        

	}

    //-----------------------------------------------------------------

    /**
     * Verifies the token submitted by the user.
     * 
     * @access  public
     * @param   string  $token
     * @return  response
     * @since   1.0.0
     */
    public function verifyResetToken($token) {

        $data=array('token' => $token );

        $validator = Validator::make($data,Password::$arrPasswordToken);

        if($validator->fails()){
            $message=$validator->messages();
            foreach($data as $key => $value) {
                $errorMessage = '';
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n ';
                }
            }
            $arrResponse['status'] = Config::get('constants.API_ERROR');
            $arrResponse['msg'] = $errorMessage;
            return response()->json($arrResponse,200);
        }
        else{

                $arrResponse['status'] = Config::get('constants.API_SUCCESS');
                $arrResponse['msg'] = 'Valid Token.';
                $arrResponse['data']['token'] = $token;            
        }           

        return response()->json($arrResponse,200);        
    }

}
//end of class ResetPasswordRequest
//end of file ResetPasswordRequest.php