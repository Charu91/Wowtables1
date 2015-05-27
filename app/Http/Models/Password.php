<?php namespace WowTables\Http\Models;

use DB;
use Config;
use WowTables\Http\Models\Eloquent\PasswordRequest;
use WowTables\Http\Models\Eloquent\User;
use URL;
use Mail;

/**
 * Model class Password.
 *
 * @package Wowtables
 * @version 1.0.0
 * @since   1.0.0
 * @author  Parth Shukla <parthshukla@ahex.co.in>
 */
class Password {

		static $arrPasswordRule=array( 'email' => 'required|exists:users,email');

        static $arrPasswordConfirm=array( 
                                            'token' => 'required|passwordResetToken',
                                            'password' => 'required|confirmed',
                                            'password_confirmation' => 'required'
                                        );
        static $arrPasswordToken=array( 'token' => 'required|passwordResetToken' );

        //-------------------------------------------------------------

        /**
         * Creates the token and sends mail to user.
         * 
         * @static
         * @access public
         * @param  array  $data
         * @return array
         * @since  1.0.0
         */
        public static function requestPassword($data) {
            //creating a new token
            $response = self::savePasswordRequest($data);
            //sending mail
            self::sendMail($response);

            $arrResponse=array();
            $arrResponse['status'] = Config::get('constants.API_SUCCESS');
            $arrResponse['msg'] = 'A mail has been sent to the email you registered with.';

            return $arrResponse;

	   }

       //---------------------------------------------------------------

       /**
        * Updates the user password to the new value.
        *
        * @static
        * @access  public
        * @param   array   $arrData
        * @return  array
        * @since   1.0.0
        */
        public static function updatePasswordDatabase($arrData) {
            $user = PasswordRequest::where('request_token', '=', $arrData ['token'])
                        ->first();

            $password=bcrypt($arrData['password']);

            User::where('id', '=', $user['user_id'])
                                    ->update([ 'password' => $password ]);

            PasswordRequest::where('request_token', '=', $arrData ['token'])
                                        ->update(['status' => 'used']);
            $arrResponse['status'] = Config::get('constants.API_SUCCESS');
            $arrResponse['msg'] = 'Password set successfully.';

            return $arrResponse;
	   }

       //-----------------------------------------------------------------

	/**
     * Sends password reset mail.
     *
     * @static
     * @access  public
     * @param   array  $data
     * @since  1.0.0
     */
	static public function sendMail($data){

    $mailbody =  "User - ".$data['email'].";<br/>Hi {$data['userName']},<br>
        We have received a forgot password request from you. If you have sent the request, please use the link below to set a new password.
        <br /><a href='".URL::to('forgotPassword')."/".$data['randString']."'>".URL::to('forgotPassword')."/".$data['randString']. "</a><br />
        If you have not sent the request then you do not need to do anything.<br />
        <br>Thanks & Regards<br>
        The WowTables Team";
       
    Mail::raw($mailbody, function($message) use ($data)
    	{
        	$message->from('info@wowtables.com', 'WowTables by GourmetItUp');

        	$message->to($data['email'])->subject('Registration with WowTables');
    	});
    }

    //---------------------------------------------------------------------

    /**
     * Creates a token for password reset.
     * 
     * @static
     * @access   public
     * @param    array    $data
     * @return   array
     * @since    1.0.0
     */
    static public function savePasswordRequest($data) {

        $userData=DB::table('users')
    			->where('email',$data['email'])
    			->select('full_name','id')
    			->first();

        $randStr=str_random(64);      

        //Store data required to send mail
        $returnData=array();
        $returnData['userName']=$userData->full_name;
        $returnData['user_id']=$userData->id;
        $returnData['randString'] = $randStr;
        $returnData['email']=$data['email'];

        PasswordRequest::where('user_id', '=',  $returnData['user_id'])
                            ->where('status','active')
                            ->update(['status' => 'expired']);
            
        $passwordRequest = new PasswordRequest;
        $passwordRequest->user_id =  $returnData['user_id'];
        $passwordRequest->request_token = $randStr;
        $passwordRequest->status = 'active';        
        $passwordRequest->save();
        
        
        return $returnData;
    }
}
//end of class Password
//end of file Password.php