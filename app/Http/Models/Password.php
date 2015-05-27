<?php namespace WowTables\Http\Models;

use DB;
use Config;
use WowTables\Http\Models\Eloquent\PasswordRequest;
use WowTables\Http\Models\Eloquent\User;
use URL;
use Mail;

class Password {

		static $arrPasswordRule=array( 'email' => 'required|exists:users,email');

        static $arrPasswordConfirm=array( 
                                            'token' => 'required',
                                            'password' => 'required|confirmed',
                                            'password_confirmation' => 'required'
                                        );

	//Function to create reset link
	public static function requestPassword($email){

		$response=self::savePasswordRequest($email); 


	}
	//-----------------------------------------------------------------





	//Function to update new password
	public static function updatePasswordDatabase($arrData){

        //print_r($arrData['password']); //die();

        $user = PasswordRequest::where('request_token', '=', $arrData ['token'])->firstOrFail();
            
            //print_r($user['user_id']); die();
        $password=bcrypt($arrData['password']);

        $affectedRows = User::where('id', '=', $user['user_id'])
                                    ->update([ 'password' => $password ]);

        //print_r($password); 

        $affectedRows2 = PasswordRequest::where('user_id', '=', $user['user_id'])
                                        ->update(['status' => 'expired']);

        //echo "\n  HI i am in update password ......."; 

        $arrResponse['status'] = Config::get('constants.API_SUCCESS');
        $arrResponse['msg'] = 'Password set successfully.';

        return $arrResponse;
	}
	//-----------------------------------------------------------------




	//Function to send mail to reset password
	static public function sendMail($data){	
     //print_r($data); die();	

    $mailbody =  "User - ".$data['email'].";<br/>Hi {$data['userName']},<br>
        We have received a forgot password request from you. If you have sent the request, please use the link below to set a new password.
        <br /><a href='".URL::to('resetPassword')."/".$data['randString']."'>".URL::to('resetPassword')."/".$data['randString']. "</a><br />
        If you have not sent the request then you do not need to do anything.<br />
        <br>Thanks & Regards<br>
        The WowTables Team";
       
    Mail::raw($mailbody, function($message) use ($users)
    	{
        	$message->from('info@wowtables.com', 'WowTables by GourmetItUp');

        	$message->to($users['email_address'])->subject('Registration with WowTables');
    	});
    }


    static public function savePasswordRequest($email){
    	$userData=DB::table('users')
    			->where('email',$email)
    			->select('full_name','id')
    			->first();

        $randStr=str_random(64);      

        //Store data required to send mail
        $data=array();
        $data['email']=$email['email'];
        $data['userName']=$userData->full_name;
        $data['user_id']=$userData->id;
        $data['randString']=$randStr;

           // print_r($data); die("HI hi");

        $oldRequest = PasswordRequest::where('user_id', '=', $data['user_id'])
                                        ->where('status', '=', 'active')
                                        ->first();

        $affectedRows = PasswordRequest::where('user_id', '=', $data['user_id'])
                                        ->update(['status' => 'expired']);
            
        $passwordRequest = new PasswordRequest;
        $passwordRequest->user_id = $data['user_id'];
        $passwordRequest->request_token = $data['randString'];
        $passwordRequest->status = 'active';        
        $passwordRequest->save();
        
        //print_r($oldRequest); die();
        

        //Send reset email to user
            $emailResponse=self::sendMail($data);
    }
    //-----------------------------------------------------------------


    //Function to create reset link
    public static function verifyToken($token){

        $dbToken=DB::table('password_request as pr')
                        ->where('pr.request_token',$token)
                        ->where('pr.status','=','active')
                        ->select('user_id')
                        ->first(); 

        //To store response
        $arrResponse=array();

         if($dbToken){

                $arrResponse['status'] = Config::get('constants.API_SUCCESS');
                $arrResponse['msg'] = 'Valid Token.';
                $arrResponse['data']['token'] = $token;

                //print_r($dbToken); die('hi...');
        }
        else{

                $arrResponse['status'] = Config::get('constants.API_ERROR');
                $arrResponse['msg'] = 'Either token is expired or is not a valid one.';
        }

        return $arrResponse;
    }
    //-----------------------------------------------------------------

}
