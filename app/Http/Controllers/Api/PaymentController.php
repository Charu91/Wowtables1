<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Payment;
use Illuminate\Http\Request;
use Validator;
use Config;

use WowTables\Http\Models\Eloquent\Transaction;
use WowTables\Http\Models\UserDevices;
use WowTables\Http\Models\Eloquent\ReservationDetails;
use Mailchimp;

class PaymentController extends Controller {

	/**
	 * Instance of Request class.
	 * 
	 * @var		Request
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $request;

	protected $mailchimp;
	protected $listId = '986c01a26a';

	public function __construct(Request $request, Mailchimp $mailchimp) {
		$this->request = $request;
		$this->mailchimp = $mailchimp;	
	}
	//-----------------------------------------------------------------

	/**
	 * Handle the request to generate Mobile PayU hash code.
	 * 
	 * @access	public
	 * @param	array	 
	 * @return	json
	 * @since	1.0.0
	 */
	 public function getMobileHash() {
	 	
	 	//reading data input by the user
	 	$data = $this->request->all();

	 	//validating user data
		$validator = Validator::make($data,Payment::$arrRules);
		
		if($validator->fails()) {
			$message = $validator->messages();
			$errorMessage = "";
			foreach($data as $key => $value) {
				if($message->has($key)) {
					$errorMessage .= $message->first($key).'\n ';
				}
			}
			
			$arrResponse['status'] = Config::get('constants.API_ERROR');
			$arrResponse['error'] = $errorMessage;				
		}
		else {
			
			$code = Payment::getPayUHash($data);
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			$arrResponse['data'] = $code;			
		}
		
		return response()->json($arrResponse,200);
	 }

	 //------------------------------------------------------------------------

	 /**
	  * Handle requests for adding payment transaction to 
	  * the database.
	  * 
	  * @access  public
	  * @return  response
	  * @since   1.0.0
	  */
	 public function savePaymentTransaction() {
	 	//array to store response
		$arrResponse = array();
		
		//reading data input by the user
		$data =  $this->request->all();

		$accessToken = $_SERVER['HTTP_X_WOW_TOKEN'];

		//reading the user detail
		$userID = UserDevices::getUserDetailsByAccessToken($accessToken);

		//validating user data
		$validator = Validator::make($data,Transaction::$arrRules);

		if($validator->fails()) {
			$message = $validator->messages();
			$errorMessage = "";
			foreach($data as $key => $value) {
				if($message->has($key)) {
					$errorMessage .= $message->first($key).'\n ';
				}
			}
			
			$arrResponse['status'] = Config::get('constants.API_ERROR');
			$arrResponse['error'] = $errorMessage;				
		}
		else {
			//validation passed
			$transaction = new Transaction;

			//initializing the columns
			$transaction->transaction_number 	= $data['transaction_number'];
			$transaction->reservation_id 		= $data['reservation_id'];
			$transaction->amount_paid 			= $data['amount_paid'];
			$transaction->transaction_date		= $data['transaction_date'];
			$transaction->transaction_details	= $data['transaction_details'];
			$transaction->response_code			= $data['response_code'];
			$transaction->response_message		= $data['response_message'];
			$transaction->source_type			= $data['source_type'];
			$transaction->user_id				= $userID;

			//saving information into DB
			$transaction->save();
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');			
		}
		return response()->json($arrResponse,200);
		
	 }

	 //------------------------------------------------------------------------

	 /**
	  * Handle requests for payment success response by PayU. 
	  * 	  
	  * @access  public
	  * @return  response
	  * @since   1.0.0
	  */
	 public function getSuccessResponse() {

	 	//reading data input by the user
		$data =  $this->request->all();

		return response()->json($data,200);

	 }
	 //------------------------------------------------------------------------

	 /**
	  * Handle requests for payment failure response by PayU.	  
	  * 
	  * @access  public
	  * @return  response
	  * @since   1.0.0
	  */
	 public function getFailureResponse() {

	 	//reading data input by the user
		$data =  $this->request->all();

		//Setting token value if user has not sent the token in request
		if(!isset($data['access_token'])){
			$data['access_token'] = $_SERVER['HTTP_X_WOW_TOKEN'] ;
		}

		$userID = UserDevices::getUserDetailsByAccessToken($data['access_token']);
		$reservationID = $this->request->input('reservationID');
		
		$arrResponse = ReservationDetails::cancelReservation($reservationID, $this->mailchimp,$userID);
		
		return response()->json($data,200);

	 }

	/**
	 * handles payu success and failure. written for testing
	 */

	public function payuApiResponse(){
		return view('frontend.pages.payuapi');
	}
}
//end of class PaymentController
//end of file PaymentController.php