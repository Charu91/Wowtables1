<?php namespace WowTables\Http\Models\Eloquent;

use DB;
use Config;
use Illuminate\Database\Eloquent\Model;

/**
 * Model class Reservation.
 * 
 * @package		wowtables
 * @version		1.0.0
 * @since		1.0.0
 * @author		Parth Shukla <parthshukla@ahex.co.in>
 */
class ReservationDetails extends Model {
	
	/**
	 * Table to be used by this model.
	 * 
	 * @var		string
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $table = 'reservation_details';
	
	/**
	 * Columns that cannot be mass-assigned.
	 * 
	 * @var		array
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $guarded = array('id');
	
	//-----------------------------------------------------------------
	
	/**
	 * Writes the details of the reservation in the DB.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array 	$arrData
	 * @return	boolean
	 * @since	1.0.0
	 */
	public static function addReservationDetails($arrData) {
		//creating a new instance of the table
		$reservation = new ReservationDetails;
		
		//initializing the data
		$reservation->reservation_status = 'new';
		$reservation->reservation_date = $arrData['reservationDate'];
		$reservation->reservation_time = $arrData['reservationTime'];
		$reservation->no_of_persons = $arrData['partySize'];
		$reservation->vendor_location_id = $arrData['vendorLocationID'];
		$reservation->guest_name = $arrData['guestName'];
		$reservation->guest_email = $arrData['guestEmail'];
		$reservation->guest_phone = $arrData['phone'];
		$reservation->reservation_type = $arrData['reservationType'];
				
		//setting up the variables that may be present
		if(isset($arrData['specialRequest'])) {
			$reservation->special_request = $arrData['specialRequest'];
		}
		
		if(isset($arrData['addedBy'])) {
			$reservation->added_by = $arrData['addedBy'];
		}
 		
		#saving the information into the DB
		$savedData = $reservation->save();
		
		if($savedData) {
			return TRUE;
		}
		
		return FALSE;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Reads the total number of reservations done againts a
	 * particular vendor location and type.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array 	$arrData
	 * @since	1.0.0
	 * @return	integer
	 */
	public static function getReservationCount($arrData) {
		$queryResult = Self::where('vendor_location_id',$arrData['vendorLocationID'])
						->where('reservation_date',$arrData['reservationDate'])
						->where('reservation_type',$arrData['reservationType'])
						->whereIn('reservation_status',array('new','edited'))
						->groupBy('vendor_location_id')
						->select(DB::raw('SUM(no_of_persons) as person_count'))
						->get();
		
		//array to store the reservation details
		$arrDetails = array();
		
		if($queryResult) {
			foreach($queryResult as $row) {
				$arrDetails[] = $row->person_count; 
			}
		}
		return $arrData;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Updates the status of the reservation to cancel.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer	$reservationID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function cancelReservation($reservationID) {
		//array to hold response
		$arrResponse = array();
		
		$queryResult = Self::where('id',$reservationID)
							//->where('user_id', $userID)
							->where('reservation_status','!=','cancel')
							->first();
		
		if($queryResult) {
			$reservation = Self::find($reservationID);
			$reservation->reservation_status = 'cancel';
			$reservation->save();
			
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
		}
		else {
			$arrResponse['status'] = Config::get('constants.API_FAIL');
			$arrResponse['msg'] = 'Sorry. No Such record exists.';
		}
		
		return $arrResponse;
	}
}
//end of class Reservation
//end of file app/Http/Models/Eloquent/Reservation.php