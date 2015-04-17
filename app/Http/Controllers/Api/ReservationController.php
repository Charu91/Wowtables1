<?php namespace WowTables\Http\Controllers\Api;

use Config;

use Illuminate\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Reservation;
use WowTables\Http\Models\Locations;

/**
 * Controller class ReservationController
 * 
 * @since	1.0.0
 * @version	1.0.0
 * @author	Parth Shukla<parthshukla@ahex.co.in>
 */
 class ReservationController extends Controller {
 	
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
			$arrResponse['data'] = Locations::getProductVendorLocation($id);
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
		}
		else if($type=='alacarte') {
			$arrResponse['data'] = Locations::getVendorLocationDetails($id);
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
	 * @return	response
	 * @since	1.0.0
	 */
	public function getPartySize() {
		
	}
 }
//end of class ReservationController
//end of file Wowtables/app/Http/Controllers/Api/ReservationController.php