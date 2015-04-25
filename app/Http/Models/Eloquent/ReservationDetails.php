<?php namespace WowTables\Http\Models\Eloquent;

use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
use URL;

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
		
		//setting up the value of the location id as per type
 		if($arrData['reservationType'] == 'alacarte') {
 			$reservation->vendor_location_id = $arrData['vendorLocationID'];
 		}
		else if($arrData['reservationType'] == 'experience') {
			$reservation->vendor_location_id = 0;
			$reservation->product_vendor_location_id = $arrData['vendorLocationID'];
		}
		#saving the information into the DB
		$savedData = $reservation->save();
		
		if($savedData) {
			if($arrData['reservationType'] == 'alacarte') {
				
				$arrData['status'] = Config::get('constants.API_SUCCESS');
				//reading the resturants detail
				$aLaCarteDetail = self::readVendorDetailByLocationID($arrData['vendorLocationID']);
				
				$arrResponse['data']['name'] = $aLaCarteDetail['name'];
				$arrResponse['data']['url'] = URL::to('/').'alacarte/'.$aLaCarteDetail['id'];
				$arrResponse['data']['reservationDate'] = $arrData['reservationDate'];
				$arrResponse['data']['reservationTime'] = $arrData['reservationTime'];
				$arrResponse['data']['partySize'] = $arrData['partySize'];
				$arrResponse['data']['reward_point'] = $aLaCarteDetail['reward_point'];				
			}
			else if($arrData['reservationType'] == 'experience') {
				
				$arrData['status'] = Config::get('constants.API_SUCCESS');
				//reading the resturants detail
				$aLaCarteDetail = self::readProductDetailByProductVendorLocationID($arrData['vendorLocationID']);
				
				$arrResponse['data']['name'] = $aLaCarteDetail['name'];
				$arrResponse['data']['url'] = URL::to('/').'alacarte/'.$aLaCarteDetail['id'];
				$arrResponse['data']['reservationDate'] = $arrData['reservationDate'];
				$arrResponse['data']['reservationTime'] = $arrData['reservationTime'];
				$arrResponse['data']['partySize'] = $arrData['partySize'];
				$arrResponse['data']['reward_point'] = $aLaCarteDetail['reward_point'];				
			}
			else {
				$arrResponse['status'] = Config::get('constants.API_ERROR');
			}
			return $arrResponse;
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
	 * @return	integer
	 * @since	1.0.0 
	 */
	public static function getReservationCount($arrData) {
		$queryResult = Self::where('vendor_location_id',$arrData['vendorLocationID'])
						->where('reservation_date',$arrData['reservationDate'])
						->where('reservation_type',$arrData['reservationType'])
						->whereIn('reservation_status',array('new','edited'))
						->groupBy('vendor_location_id')
						->select(DB::raw('SUM(no_of_persons) as person_count'))
						->first();
		
		if($queryResult) {
			return $queryResult->person_count;
		}
		return 0;
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
	
	//-----------------------------------------------------------------
	
	/**
	 * Updates the reservation details stored in DB.
	 * 
	 * @access	public
	 * @param	array 	$arrData
	 * @return	array
	 * @since	1.0.0
	 */
	public static function updateReservationDetail($arrData) {
		//array to hold response
		$arrResponse = array();
		
		$queryResult = Self::where('id', $arrData['reservationID'])
						//->where('user_id',$arrData[])
						//->where('reservation_date',)
						->whereIn('reservation_status',array('new','edited'))
						->first();
		
		if($queryResult) {
			$reservation = Self::find($arrData['reservationID']);
			//initializing the data
			$reservation->reservation_status = 'edited';
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

			//setting up the value of the location id as per type
 			if($arrData['reservationType'] == 'alacarte') {
 				$reservation->vendor_location_id = $arrData['vendorLocationID'];
 			}
			else if($arrData['reservationType'] == 'experience') {
				$reservation->vendor_location_id = 0;
				$reservation->product_vendor_location_id = $arrData['vendorLocationID'];
			
			}
 		
			#saving the information into the DB
			$savedData = $reservation->save();
			$arrResponse['status'] = ($savedData) ? Config::get('constants.API_SUCCESS'): Config::get('constants.API_FAILED');
			
		}
		else {
			$arrResponse['status'] = Config::get('constants.API_ERROR');
		}
		
		return $arrResponse;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Reads the details of the reservation matching the passed 
	 * id.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer	$reservationID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getActiveReservationDetail($reservationID) {
		$queryResult = Self::where('id',$reservationID)
							->whereIn('reservation_status',array('new','edited'))
							->first();
		
		//array to store response
		$arrData = array();
		
		if($queryResult) {
			$arrData['status'] = Config::get('constants.API_SUCCESS');
			$arrData['id'] = $queryResult->id;
			$arrData['userID'] = $queryResult->user_id;
			$arrData['reservationStatus'] = $queryResult->reservation_status;
			$arrData['reservationDate'] = $queryResult->reservation_date;
			$arrData['reservationTime'] = $queryResult->reservation_time;
			$arrData['numOfPersons'] = $queryResult->no_of_persons;
			$arrData['reservationType'] = $queryResult->reservation_type;
			$arrData['vendorLocationID'] = $queryResult->vendor_location_id;
			$arrData['productVendorLocationID'] = $queryResult->product_vendor_location_id;
		}
		else {
			$arrData['status'] = Config::get('constants.API_ERROR');
			$arrData['msg'] = 'Data not found.';
		}
		
		return $arrData;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Reads the detial of the vendor by vendor location ID.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer	$vendorLocationID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function readVendorDetailByLocationID($vendorLocationID) {
		//array to store the data
		$arrData = array();
		
		$queryResult = DB::table('vendors')
						->join(DB::raw('vendor_locations as vl'),'vl.vendor_id','=','vendors.id')
						->leftJoin(DB::raw('vendor_attributes_integer as vai'),'vai.vendor_id','=','vendors.id')
						->join(DB::raw('vendor_attributes as va'),'va.id','=','vai.vendor_attribute_id')
						->where('vl.id',$vendorLocationID)
						->where('va.alias','reward_points_per_reservation')
						->select('vendors.id','vendors.name','vai.attribute_value as reward_point')
						->first();
		if($queryResult) {
			$arrData['id'] = $queryResult->id;
			$arrData['name'] = $queryResult->name;
			$arrData['reward_point'] = (empty($queryResult->reward_point))? 0.00 : $queryResult->reward_point;
		}
		
		return $arrData;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Reads the details of the product by vendor location ID.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer		$productVendorLocationID
	 * @return	array	
	 * @since	1.0.0
	 */
	public static function readProductDetailByProductVendorLocationID($productVendorLocationID) {
		//array to store the data
		$arrData = array();
		
		$queryResult = DB::table('products')
						->join(DB::raw('product_vendor_locations as pvl'),'pvl.product_id','=','products.id')
						->leftJoin(DB::raw('product_attributes_integer as pai'),'pai.product_id','=','products.id')
						->join(DB::raw('product_attributes as pa'),'pa.id','=','pai.product_attribute_id')
						->where('pvl.id',$productVendorLocationID)
						->where('pa.alias','reward_points_per_reservation')
						->select('products.id','products.name','pai.attribute_value as reward_point')
						->first();
		
		if($queryResult) {
			$arrData['id'] = $queryResult->id;
			$arrData['name'] = $queryResult->name;
			$arrData['reward_point'] = (empty($queryResult->reward_point))? 0.00 : $queryResult->reward_point;
		}
		
		return $arrData;
	} 
	
	//-----------------------------------------------------------------
	
	/**
	 * Returns the Reservation record of a user.
	 * 
	 * @static	true
	 * @access	public
	 * @param	integer		$userID
	 * @param	integer		$start
	 * @param	integer		$limit
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getReservationRecord($userID,$start=NULL,$limit=NULL) {
		$queryResult = DB::table(DB::raw('reservation_details as rd'))
						->leftJoin(DB::raw('vendor_locations as vl'),'vl.id','=', 'rd.vendor_location_id')
						->leftJoin(DB::raw('product_vendor_locations as pvl'),'pvl.id','=','rd.product_vendor_location_id')
						->leftJoin('products','products.id','=','pvl.product_id')
						->leftJoin('vendors','vendors.id','=','vl.vendor_id')
						->where('rd.user_id', $userID)
						->whereIn('reservation_status',array('new','edited'))
						->select('rd.id','rd.user_id','rd.reservation_status','rd.reservation_date',
									'rd.reservation_time','rd.no_of_persons', 'products.name as product_name',
									 'vendors.name as vendor_name','rd.reservation_type')
						->get();
		
		//array to store the information
		$arrData = array();
		
		//sub array to store the previous reservation information
		$arrData['data']['pastReservation'] = array();
		
		//sub array to store the upcoming reservation information
		$arrData['data']['upcomingReservation'] = array(); 
		
		if($queryResult) {
			//converting current day time to timestamp
			$currentTimestamp = strtotime(date('Y-m-d H:i:s'));
			
			foreach($queryResult as $row) {
				//converting reservation day time to timestamp
				$reservationTimestamp = strtotime($row->reservation_date.' '.$row->reservation_time);
				$arrDatum = array(
									'id' => $row->id,
									'status' => $row->reservation_status,
									'date' => $row->reservation_date,
									'time' => $row->reservation_time,
									'no_of_persons' => $row->no_of_persons,
									'name' => (empty($row->vendor_name)) ? $row->product_name : $row->vendor_name,
									'type' => $row->reservation_type 
								);
				
				if($reservationTimestamp >= $currentTimestamp ) {
					array_push($arrData['data']['upcomingReservation'],$arrDatum);
				}
				else {
					array_push($arrData['data']['pastReservation'],$arrDatum);
				}
				
			}
			
			$arrData['status'] = Config::get('constants.API_SUCCESS');
		}
		else {
			$arrData['status'] = Config::get('constants.API_ERROR');
			$arrData['msg'] = 'No matching record found.';
		}
		return $arrData;
	}
}
//end of class Reservation
//end of file app/Http/Models/Eloquent/Reservation.php