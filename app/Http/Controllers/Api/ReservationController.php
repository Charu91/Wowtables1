<?php namespace WowTables\Http\Controllers\Api;

use Config;

use Illuminate\Http\Request;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Reservation;
use WowTables\Http\Models\Eloquent\Products\ProductVendorLocationLimit;
use WowTables\Http\Models\Eloquent\Vendors\VendorLocationLimit;
use WowTables\Http\Models\Schedules;
use WowTables\Http\Models\Eloquent\ProductVendorLocationBlockSchedule;
use WowTables\Http\Models\Eloquent\ReservationDetails;
use WowTables\Http\Models\UserDevices;
use Validator;
use Mailchimp;
use WowTables\Http\Controllers\ConciergeApi\ReservationController as RestaurantManager;
use WowTables\Http\Models\Eloquent\Reservations\ReservationDetails as ReservationModel;


/**
 * Controller class ReservationController
 * 
 * @since	1.0.0
 * @version	1.0.0
 * @author	Parth Shukla<parthshukla@ahex.co.in>
 */
 class ReservationController extends Controller {
 	
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
	
	//-----------------------------------------------------------------
	
	/**
	 * 
	 */
	public function __construct(Request $request, Mailchimp $mailchimp,RestaurantManager $restaurantapp) {
		$this->request = $request;
		$this->mailchimp = $mailchimp;
		$this->restaurantapp = $restaurantapp;
	}
	
	//-----------------------------------------------------------------
 	
	/**
	 * Handles requests for displaying the locations
	 * of the passed experience or the alacarte.
	 * 
	 * @access	public
	 * @param	string	$type
	 * @param	integer	$id
	 * @return	response
	 * @since	1.0.0
	 */
	public function getLocation($type,$id) {
		$arrResponse = array();
		if($type=="experience") {
			$arrResponse['data'] = Reservation::getExperienceLocationAndLimit($id);
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
		}
		else if($type=='alacarte') {
			$arrResponse['data'] = Reservation::getVendorLocationAndLimit($id);
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
		}
		else {
			$arrResponse['msg'] = 'Invalid parameters';
			$arrResponse['status'] = Config::get('constants.API_ERROR');
		}
		
		
		return response()->json($arrResponse,200);
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Handles requests for displaying the information
	 * related to party size.
	 * 
	 * @access	public
	 * @param	string	$type
	 * @param	string	$id
	 * @return	response
	 * @since	1.0.0
	 */
	public function getPartySize($type,$id) {
		//array to store response
		$arrResponse = array();
		if($type=="experience") {
			$arrResponse['data'] = ProductVendorLocationLimit::getProductPeopleReservationLimit($id);
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
		}
		else if($type=='alacarte') {
			$arrResponse['data'] = VendorLocationLimit::getVendorPeopleReservationLimit($id);
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
		}
		else {
			$arrResponse['msg'] = 'Invalid parameters';
			$arrResponse['status'] = Config::get('constants.API_ERROR');
		} 
		
		return response()->json($arrResponse,200);
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Handles request for displaying the schedule related
	 * to a location.
	 * 
	 * @access	public
	 * @param	string
	 * @param	integer
	 * @param	string		default NULL
	 * @return	response
	 * @since	1.0.0
	 */
	public function getSchedule($type, $typeID, $typeLocationID = NULL, $day=NULL) {
		//array to store response
		$arrResponse = array();
		
		if($type=="experience") {
			$arrResponse['data'] = Schedules::getExperienceLocationSchedule($typeID, $typeLocationID, $day);
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
		}
		else if($type=='alacarte') {
			$arrResponse['data'] = Schedules::getVendorLocationSchedule($typeLocationID,$day);
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
		}
		else {
			$arrResponse['msg'] = 'Invalid parameters';
			$arrResponse['status'] = Config::get('constants.API_ERROR');
		} 
		
		return response()->json($arrResponse,200);
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Handles requests for reserving the table.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function reserveTable() {
		//array to store response
		$arrResponse = array();
		
		//reading data input by the user
		$data =  $this->request->all();

		$data['access_token']=$_SERVER['HTTP_X_WOW_TOKEN'];


		//validating user data
		$validator = Validator::make($data,Reservation::$arrRules);
		
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
			$userID = UserDevices::getUserDetailsByAccessToken($data['access_token']);
			
			if($userID > 0) {
				//validating the information submitted by users
				$arrResponse = Reservation::validateReservationData($data);
				
				if($arrResponse['status'] == Config::get('constants.API_SUCCESS')) {
						$arrResponse = ReservationDetails::addReservationDetails($data,$userID, $this->mailchimp, 'mobile_user');
					}
				}
				else {
					$arrResponse['status'] = Config::get('constants.API_ERROR');
					$arrResponse['msg'] = 'Not a valid request.';	
				}
			}

		$tokens = ReservationModel::pushToRestaurant($arrResponse['data']['reservation_id']);
		$this->restaurantapp->push($arrResponse['data']['reservation_id'],$tokens,true);
				
		return response()->json($arrResponse,200);
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Handles requests for cancelling a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function cancelReservation() {
		//reading data input by the user
		$data =  $this->request->all();

		//Setting token value if user has not sent the token in request
		if(!isset($data['access_token'])){
			$data['access_token'] = $_SERVER['HTTP_X_WOW_TOKEN'] ;
		}

		$userID = UserDevices::getUserDetailsByAccessToken($data['access_token']);
		$reservationID = $this->request->input('reservationID');
		
		$arrResponse = ReservationDetails::cancelReservation($reservationID, $this->mailchimp,$userID);

		$tokens = ReservationModel::pushToRestaurant($reservationID);
		$this->restaurantapp->push($reservationID,$tokens,true);

		return response()->json($arrResponse,200);		
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Handles requests for updating an existing reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function changeReservation() {
		//array to store response
		$arrResponse = array();
		
		//reading data input by the user
		$data =  $this->request->all();
		/*
		//reading the reservation rules
		$arrRule = Reservation::$arrRules;
		
		//updating the validation rule for reservation time
		$arrRule['reservationTime'] = 'required|OutsidePrevReservationTimeRange: reservationDate, reservationID'; 
		
		 */
		 //print_r($data); die();
		 //validating user data

		 $validator = Validator::make($data,Reservation::$arrRules); 
			//print_r($validator->fails());die;

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
		 	
		 	//validating the user input data
			$arrResponse = Reservation::validateEditReservationData($data);
			die('hello');
		
			if($arrResponse['status'] == Config::get('constants.API_SUCCESS')) {
				$arrResponse = ReservationDetails::updateReservationDetail($data);
			} 
			 
		 }

		$tokens = ReservationModel::pushToRestaurant($data['reservationID']);
		$this->restaurantapp->push($data['reservationID'],$tokens,true);
		return response()->json($arrResponse,200);		
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Handles requst for displaying the historical reservation
	 * record of the logged in user.
	 * 
	 * @access	public
	 * @param	string	$access_token
	 * @return	response
	 * @since	1.0.0
	 */
	public function reservationRecord() {	

		$access_token=$_SERVER['HTTP_X_WOW_TOKEN'];

		$userID = UserDevices::getUserDetailsByAccessToken($access_token);
		if($userID) {
			$arrResponse = Reservation::getReservationRecord($userID);
		}
		else {
			$arrResponse['status'] = Config::get('constants.API_ERROR');
			$arrResponse['msg'] = 'Not a valid request'; 
		}
		
		return response()->json($arrResponse,200);
	}
 }
//end of class ReservationController
//end of file Wowtables/app/Http/Controllers/Api/ReservationController.php