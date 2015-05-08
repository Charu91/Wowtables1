<?php namespace WowTables\Http\Models\Eloquent;

//use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
use URL;
use WowTables\Http\Models\Eloquent\ReservationAddonsVariantsDetails;

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
	public static function addReservationDetails($arrData, $userID) {
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
		$reservation->user_id = $userID;
		
		//setting up the variables that may be present
		if(isset($arrData['specialRequest'])) {
			$reservation->special_request = $arrData['specialRequest'];
		}
		
		if(isset($arrData['addedBy'])) {
			$reservation->added_by = $arrData['addedBy'];
		}
		
		if(isset($arrData['giftCardID'])) {
			$reservation->giftcard_id = $arrData['giftCardID'];
		}
		
		//setting up the value of the location id as per type
 		if($arrData['reservationType'] == 'alacarte') {
 			
			//reading the resturants detail
			$aLaCarteDetail = self::readVendorDetailByLocationID($arrData['vendorLocationID']);
			
			$reservation->points_awarded = $aLaCarteDetail['reward_point'];
 			$reservation->vendor_location_id = $arrData['vendorLocationID'];
			$reservation->product_vendor_location_id = 0;
 		}
		else if($arrData['reservationType'] == 'experience') {
			
			//reading the product detail
			$productDetail = self::readProductDetailByProductVendorLocationID($arrData['vendorLocationID']);
			
			$reservation->points_awarded = $productDetail['reward_point'];
			$reservation->vendor_location_id = 0;
			$reservation->product_vendor_location_id = $arrData['vendorLocationID'];
		}
		#saving the information into the DB
		$savedData = $reservation->save();
		
		if($savedData) {
			if($arrData['reservationType'] == 'alacarte') {
				
				$arrResponse['status'] = Config::get('constants.API_SUCCESS');
				
				
				$arrResponse['data']['name'] = $aLaCarteDetail['name'];
				$arrResponse['data']['url'] = URL::to('/').'/alacarte/'.$aLaCarteDetail['id'];
				$arrResponse['data']['reservationDate'] = $arrData['reservationDate'];
				$arrResponse['data']['reservationTime'] = $arrData['reservationTime'];
				$arrResponse['data']['partySize'] = $arrData['partySize'];
				$arrResponse['data']['reward_point'] = $aLaCarteDetail['reward_point'];				
			}
			else if($arrData['reservationType'] == 'experience') {
				
				$arrResponse['status'] = Config::get('constants.API_SUCCESS');
				if(array_key_exists('addons', $arrData) && !empty($arrData['addon'])) {
					self::addReservationAddonDetails($reservation->id, $arrData['addon']);
				}				
				
				$arrResponse['data']['name'] = $productDetail['name'];
				$arrResponse['data']['url'] = URL::to('/').'/experiences/'.$productDetail['id'];
				$arrResponse['data']['reservationDate'] = $arrData['reservationDate'];
				$arrResponse['data']['reservationTime'] = $arrData['reservationTime'];
				$arrResponse['data']['partySize'] = $arrData['partySize'];
				$arrResponse['data']['reward_point'] = $productDetail['reward_point'];				
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
						->select(\DB::raw('SUM(no_of_persons) as person_count'))
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
		
		$queryResult = \DB::table('vendors')
						->join('vendor_locations as vl','vl.vendor_id','=','vendors.id')
						->leftJoin('vendor_location_attributes_integer as vai','vai.vendor_location_id','=','vl.id')
						->join('vendor_attributes as va','va.id','=','vai.vendor_attribute_id')
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
		
		$queryResult = \DB::table('products')
						->join('product_vendor_locations as pvl','pvl.product_id','=','products.id')
						->leftJoin('product_attributes_integer as pai','pai.product_id','=','products.id')
						->join('product_attributes as pa','pa.id','=','pai.product_attribute_id')
						->where('pvl.vendor_location_id',$productVendorLocationID)
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
	 * Writes the addons details associated with a reservation into
	 * the DB.
	 * 
	 * @access	public
	 * @param	integer	$arrReservationID
	 * @param	array 	$arrAddon
	 * @since	1.0.0
	 */
	public static function addReservationAddonDetails($reservationID, $arrAddon) {
		//array to hold the data to be written into the DB
		$arrInsertData = array();
		
		foreach($arrAddon as $key => $detail) {
			$arrInsertData[] = array(
									'reservation_id' => $reservationID,
									'no_of_persons' => $detail['qty'],
									'options_id' => $detail['prod_id'],
									'option_type' => 'addon',
									'reservation_type' => 'experience',
									'created_at' => date('Y-m-d H:i:m'),
									'updated_at' => date('Y-m-d H:i:m'),
								);
		}
		
		//writing data to reservation_addons_variants_details table
		ReservationAddonsVariantsDetails::insert($arrInsertData);
	}
}
//end of class Reservation
//end of file app/Http/Models/Eloquent/Reservation.php