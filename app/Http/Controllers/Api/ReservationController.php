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
	
	//-----------------------------------------------------------------
	
	/**
	 * 
	 */
	public function __construct(Request $request) {
		$this->request = $request;
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
			$arrResponse['data'] = Reservation::getExperienceLocationAndLimit($id);#Locations::getProductVendorLocation($id);
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
	public function getSchedule($type, $id, $day=NULL) {
		//array to store response
		$arrResponse = array();
		
		if($type=="experience") {
			$arrResponse['data'] = Schedules::getExperienceLocationSchedule($id,$day);
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
		}
		else if($type=='alacarte') {
			$arrResponse['data'] = Schedules::getVendorLocationSchedule($id,$day);
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
	 * 
	 */
	public function reserveTable() {
		//array to store response
		#$arrResponse = array();
		
		//reading data input by the user
		$arrData =  $this->request->all();
		
		$arrResponse = Reservation::validateReservationData($arrData);
		
		if($arrResponse['status'] == Config::get('constants.API_SUCCESS')) {
			if(ReservationDetails::addReservationDetails($arrData)) {
				$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			}
		}
		
		return response()->json($arrResponse,200);
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Handles requests for cancelling a reservation.
	 * 
	 * @access	public
	 * @since	1.0.0
	 * @return	response
	 */
	public function cancelReservation() {
		$reservationID = $this->request->input('reservationID');
		
		$arrResponse = ReservationDetails::cancelReservation($reservationID);
		
		return response()->json($arrResponse,200);		
	}
 }
//end of class ReservationController
//end of file Wowtables/app/Http/Controllers/Api/ReservationController.php