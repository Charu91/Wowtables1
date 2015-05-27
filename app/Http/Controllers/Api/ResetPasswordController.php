<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Password;
use Request;
use Validator;
use Config;
class PasswordController extends Controller {

	//Function to create password reset string and send the mail to user
	public function createPassword() {

		$email = Request::all();

		//Validation user's profile data
        $validator = Validator::make($email,Password::$arrPasswordRule);

        if($validator->fails()){
            $message=$validator->messages();
            $errorMessage=" Not a valid email";
            foreach($email as $key => $value) {
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n';
                }
            }
            $arrResponse['status'] = Config::get('constants.API_ERROR');
            $arrResponse['msg'] = $errorMessage;
            return response()->json($arrResponse,200);
        }
        else{
            return response()->json(Password::requestPassword($email),200);
        }       

	}
	//-----------------------------------------------------------------


	//Function to update new password of the user
	public function updatePassword() {

		$data = Request::all();

		//Validation user's profile data
        $validator = Validator::make($data,Password::$arrPasswordConfirm);        

        if($validator->fails()){
            $message=$validator->messages();
            $errorMessage="Password not matched. Try again...";
            foreach($data as $key => $value) {
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



    //Function to verify token
    public function verifyResetToken($token) {
        return response()->json(Password::verifyToken($token),200);        
    }
    //-----------------------------------------------------------------

}