<?php
namespace WowTables\Http\Models;

use DB;
use Config;
use DateTime;
use DatePeriod;
use DateInterval;

//use WowTables\Http\Models\Eloquent\Products\ProductVendorLocationBlockSchedule;
use WowTables\Http\Models\Eloquent\Vendors\VendorLocationBlockedSchedules;
use WowTables\Http\Models\Eloquent\ReservationDetails;
use WowTables\Http\Models\Eloquent\Vendors\VendorLocationBookingTimeRangeLimit;
use WowTables\Http\Models\Eloquent\Products\ProductVendorLocationBlockedSchedule;
use WowTables\Http\Models\Eloquent\Products\ProductVendorLocationBookingTimeRangeLimit;
use WowTables\Http\Models\Schedules;
use WowTables\Http\Models\Experiences;

/**
 * Model class Reservation.
 *
 * @package		wowtables
 * @since		1.0.0
 * @version		1.0.0
 * @author		Parth Shukla<parthshukla@ahex.co.in>
 */
class Reservation {
	
	public  static $arrRules = array(
									'reservationDate' => 'required|date|NotPreviousDate',
									'reservationDay' => 'required',
									'reservationTime' => 'required|OutsidePrevReservationTimeRange:reservationDate|DayReservationCutOff',
									'partySize' => 'required|integer',
									'vendorLocationID' => 'required|not_in:0',
									'guestName' => 'required|max:255',
									'guestEmail' => 'required|email|max:255',
									'phone' => 'required',
									'reservationType' => 'required|in:experience,alacarte,event',
									'specialRequest' => 'max:512',
									//'access_token' => 'required|exists:user_devices,access_token',
									'reservationID' => 'sometimes|required|exists:reservation_details,id'
								) ;
								
	//-----------------------------------------------------------------

	/**
	 * Query to read the details of the vendor locations and
	 * reservation limits.
	 *
	 * @access	public
	 * @param	integer	$vendorID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getVendorLocationAndLimit($vendorLocationID) {
		// $queryResult = DB::table(DB::raw('vendor_locations as vl')) 
		// 				->leftJoin(DB::raw('vendor_location_address as vla'), 'vla.vendor_location_id', '=', 'vl.id') 
		// 				->join('locations', 'locations.id', '=', 'vl.location_id') 
		// 				->leftJoin('vendor_locations_limits as vll', 'vll.vendor_location_id', '=', 'vl.id') 
		// 				->where('vl.id', $vendorLocationID) 
		// 				->select('vl.id', 'locations.name as area', 'vla.latitude', 
		// 							'vla.longitude', 'vll.min_people_per_reservation', 
		// 							'vll.max_people_per_reservation', 'vll.min_people_increments') 
		// 				->get();

		if(array_key_exists('HTTP_X_WOW_CITY', $_SERVER)) {
			$cityID = $_SERVER['HTTP_X_WOW_CITY'];
		} 
		else {
			$cityID = 0;
		}

		$queryResult = DB::table(DB::raw('vendor_locations as vl')) 
								->leftJoin(DB::raw('vendor_location_address as vla'), 'vla.vendor_location_id', '=', 'vl.id') 
								->join('locations', 'locations.id', '=', 'vl.location_id') 
								->leftJoin('vendor_locations_limits as vll', 'vll.vendor_location_id', '=', 'vl.id') 
								->where('vl.id', $vendorLocationID) 
								->select('vl.id', 'locations.name as area', 'vla.latitude', 
											'vla.longitude', 'vll.min_people_per_reservation', 
											'vll.max_people_per_reservation', 'vll.min_people_increments'); 
								//->get();
		if($cityID != 0) {
					$queryResult = $queryResult->where('vla.city_id', $cityID)->get();
				}
				else {
					$queryResult = $queryResult->get();
				}
				
		//array to read the locations and limits
		$arrLocLmt = array();

		//array to keep all location id
		$arrLocation = array();

		#reading the blocked dates
		foreach ($queryResult as $row) {
			$arrLocation[] = $row -> id;
		}		
		$arrBlockedDates = VendorLocationBlockedSchedules::getBlockedDate($arrLocation);

		

		foreach ($queryResult as $row) {

			$blockedDates = (array_key_exists($row -> id, $arrBlockedDates)) ? $arrBlockedDates[$row -> id] : array();
			//Call to get available dates
			$availableDates = self::getAvailableDates($blockedDates);

			$arrLocLmt[] = array(
								'vl_id' => $row -> id, 
								'area' => $row -> area, 
								'min_people' => (is_null($row -> min_people_per_reservation)) ? '' : $row -> min_people_per_reservation, 
								'max_people' => (is_null($row -> max_people_per_reservation)) ? '' : $row -> max_people_per_reservation, 
								'increment' => (is_null($row -> min_people_increments)) ? '' : $row -> min_people_increments, 
								'latitude' => $row -> latitude, 
								'longitude' => $row -> longitude, 
								'blocked_dates' => (array_key_exists($row -> id, $arrBlockedDates)) ? $arrBlockedDates[$row -> id] : array(), 
								'available_dates' => $availableDates,
							);
		}

		return $arrLocLmt;
	}

	//-----------------------------------------------------------------

	/**
	 * Query to read the details of the experience locations and
	 * reservation limits.
	 *
	 * @access	public
	 * @param	integer	$experienceID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getExperienceLocationAndLimit($experienceID) {
		// $queryResult = DB::table('product_vendor_locations as pvl') 
		// 					->join('vendor_locations as vl', 'vl.id', '=', 'pvl.vendor_location_id') 
		// 					->join('locations', 'locations.id', '=', 'vl.location_id') 
		// 					->leftJoin('product_vendor_locations_limits as pvll', 'pvll.product_vendor_location_id', '=', 'pvl.id')
		// 					->leftJoin('vendor_location_address as vla', 'vla.vendor_location_id','=','vl.id') 
		// 					->where('pvl.product_id', $experienceID)
		// 					->where('pvl.status','Active') 
		// 					->select('pvl.vendor_location_id as id', 'locations.name as area', 
		// 							'vla.latitude', 'vla.longitude', 'pvll.min_people_per_reservation', 
		// 							'pvll.max_people_per_reservation', 'pvll.min_people_increments',
		// 							'pvl.product_id as experience_id','pvl.id as pvl_id') 
		// 					->get();

		if(array_key_exists('HTTP_X_WOW_CITY', $_SERVER)) {
			$cityID = $_SERVER['HTTP_X_WOW_CITY'];
		} 
		else {
			$cityID = 0;
		}

		$queryResult = DB::table('product_vendor_locations as pvl') 
									->join('vendor_locations as vl', 'vl.id', '=', 'pvl.vendor_location_id') 
									->join('locations', 'locations.id', '=', 'vl.location_id') 
									->leftJoin('product_vendor_locations_limits as pvll', 'pvll.product_vendor_location_id', '=', 'pvl.id')
									->leftJoin('vendor_location_address as vla', 'vla.vendor_location_id','=','vl.id') 
									->where('pvl.product_id', $experienceID)
									->where('pvl.status','Active') 
									->select('pvl.vendor_location_id as id', 'locations.name as area', 
											'vla.latitude', 'vla.longitude', 'pvll.min_people_per_reservation', 
											'pvll.max_people_per_reservation', 'pvll.min_people_increments',
											'pvl.product_id as experience_id','pvl.id as pvl_id'); 
									//->get();
		if($cityID != 0) {
					$queryResult = $queryResult->where('vla.city_id', $cityID)->get();
				}
				else {
					$queryResult = $queryResult->get();
				}

		#array to read experiences and location limits
		$arrLocLmt = array();

		//array to keep all location id
		$arrLocation = array();

		#reading the blocked dates
		foreach ($queryResult as $row) {
			$arrLocation[] = $row->pvl_id;
		}
		$arrBlockedDates = ProductVendorLocationBlockedSchedule::getBlockedDate($arrLocation);
		
		foreach ($queryResult as $row) {

			$blockedDates = (array_key_exists($row->pvl_id, $arrBlockedDates)) ? $arrBlockedDates[$row->pvl_id] : array();
			//Call to get available dates
			//$availableDates = self::getAvailableDates($blockedDates);
			$availableDates = self::getExperienceAvailableDates($experienceID, $blockedDates);

			$arrLocLmt[] = array(
									'experience_id' => $row->experience_id,
									'vl_id' => $row->pvl_id,
									'area' => $row->area, 
									'min_people' => (is_null($row->min_people_per_reservation)) ? '' : $row->min_people_per_reservation, 
									'max_people' => (is_null($row->max_people_per_reservation)) ? '' : $row->max_people_per_reservation, 
									'increment' => (is_null($row->min_people_increments)) ? '' : $row->min_people_increments, 
									'latitude' => $row->latitude, 
									'longitude' => $row->longitude, 
									'blocked_dates' => (array_key_exists($row->pvl_id, $arrBlockedDates)) ? $arrBlockedDates[$row->pvl_id] : array(),
									'available_dates' => $availableDates, 
								);
		}

		return $arrLocLmt;
	}
	//-----------------------------------------------------------------


	/**
	 * Function to read the available dates for experience only
	 *
	 * @access	public
	 * @param	array $blockedDates
	 * @param	int   $experienceID
	 * @return	array $dates
	 * @since	1.0.0
	 */
	public static function getExperienceAvailableDates($experienceID, $blockedDates) { 

		$queryResult = DB::table('products as p') 
									->join('product_attributes_date as pad1', 'pad1.product_id', '=', 'p.id')
									->join('product_attributes_date as pad2', 'pad2.product_id', '=', 'p.id')
									->join('product_attributes as pa1', 'pa1.id', '=', 'pad1.product_attribute_id')
									->join('product_attributes as pa2', 'pa2.id', '=', 'pad2.product_attribute_id')
									->where('pa1.alias', "start_date")
									->where('pa2.alias', "end_date")
									->where('p.id', $experienceID)
									->select('pad1.attribute_value as start_date', 'pad2.attribute_value as end_date')									
									->first(); 									

		//print_r($queryResult);  		

		$d1= date('Y-m-d');  
		$d2= strtotime(date("Y-m-d").' +2 Months');  
		$d2= date('Y-m-d', $d2); 
		$begin = new DateTime($d1);   
		$end = new DateTime($d2);
		//$end = $end->modify( '+1 day' ); //To include the last date

		//============Setting date range according to entry in product_attributes_date table =============
		if($queryResult){
			//Dates are available but not in the current range
			if($queryResult->start_date == $queryResult->end_date && $queryResult->start_date > $d2) {			
				return $data = array(); 
			}
			if($queryResult->start_date > $d2) {			
				return $data = array(); 
			}
			//Dates are available but both start & end date are same
			if($queryResult->start_date == $queryResult->end_date && $queryResult->start_date >= date('Y-m-d')) {
				$data = date('Y-m-d', strtotime($queryResult->start_date));
				return $data;
			}
			
			$begin = new DateTime(date('Y-m-d', strtotime($queryResult->start_date)));
			$end = new DateTime(date('Y-m-d', strtotime($queryResult->end_date)));
			//If start_date is more than range then restrict the start_date wthin range
			if($queryResult->start_date < $d1){
				$begin = new DateTime($d1);
			}		
			//If end_date is more than range then restrict the end_date wthin range
			if($queryResult->end_date > $d2){
				$end = new DateTime($d2);				
			}
		}
		//============================ END of setting date range ====================

		$end = $end->modify( '+1 day' ); //To include the last date
		$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);  
		//$dataes = array();
		
		foreach($daterange as $date){
		 //echo $date->format("Y-m-d") . "<br>";
		 $dates[] = $date->format("Y-m-d");
		}

		foreach ($blockedDates as $value) {			
			//echo $value; 
			$key = array_search($value, $dates);
			//echo $key;
			if($key!==false)  
				unset($dates[$key]);
		}

		//print_r($dates); die("jabardst");

		foreach($dates as $value) {
			$arrAvailableDates[] = $value;
		}
		//print_r($arrAvailableDates); die('dates');
		return $arrAvailableDates;
	}
	//-----------------------------------------------------------------

	/**
	 * Function to read the available dates 
	 *
	 * @access	public
	 * @param	array $blockedDates
	 * @return	array $dates
	 * @since	1.0.0
	 */
	public static function getAvailableDates($blockedDates) { 

		$d1= date('Y-m-d');  
		$d2= strtotime(date("Y-m-d").' +2 Months');  
		$d2= date('Y-m-d', $d2); 
		$begin = new DateTime($d1);   
		$end = new DateTime($d2);$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);//$dataes = array();
		
		foreach($daterange as $date){
		 //echo $date->format("Y-m-d") . "<br>";
		 $dates[] = $date->format("Y-m-d");
		}

		foreach ($blockedDates as $value) {			
			//echo $value; 
			$key = array_search($value, $dates);
			//echo $key;
			if($key!==false)  
				unset($dates[$key]);
		}

		//print_r($dates); die("jabardst");

		foreach($dates as $value) {
			$arrAvailableDates[] = $value;
		}

		return $arrAvailableDates;
	}
	//-----------------------------------------------------------------


	/**
	 * Validates the information provided by the user for booking
	 * reserving a seat.
	 *
	 * @static	true
	 * @access	public
	 * @param	array 	$arrData
	 * @since	1.0.0
	 */
	public static function validateReservationData($arrData) {
		//array to store response
		$arrResponse = array();

		//validation based on reservation type
		if ($arrData['reservationType'] == 'alacarte') {
			
			//validating that user has not selected blocked date
			$returnResult = VendorLocationBlockedSchedules::isDateBlocked($arrData['vendorLocationID'], $arrData['reservationDate']);
			if ($returnResult) {
				$arrResponse['status'] = Config::get('constants.API_ERROR');
				$arrResponse['msg'] = 'You cannot make any reservation on the selected date.';
				return $arrResponse;
			}

			//checking the availability for the booking
			$arrTimeRangeLimits = VendorLocationBookingTimeRangeLimit::checkBookingTimeRangeLimits($arrData);
			$existingReservationCount = ReservationDetails::getReservationCount($arrData);
			
			//converting the reservation time
			$reservationTime = strtotime($arrData['reservationTime']);
			
			if (!empty($arrTimeRangeLimits)) {
				foreach ($arrTimeRangeLimits as $key => $value) {
					$maxCount = ($value['max_covers_limit'] == 0) ? $value['max_tables_limit'] : $value['max_covers_limit'];
					
					if ($maxCount == $existingReservationCount) {
						$arrResponse['status'] = Config::get('constants.API_ERROR');
						$arrResponse['msg'] = 'Sorry. Currently the place is full. Please try another day.';
						return $arrResponse;
					} else if ($maxCount > $existingReservationCount) {
						if (($maxCount - ($existingReservationCount + $arrData['partySize'])) < 0) {
							$arrResponse['status'] = Config::get('constants.API_ERROR');
							$arrResponse['msg'] = "Sorry. We have only " . $maxCount - $arrReservationCount . ' seats available.';
							return $arrResponse;
						}
					}
				}
			}
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			return $arrResponse;
		} else if ($arrData['reservationType'] == 'experience') {
			//validating that user has not selected blocked date
			$returnResult = ProductVendorLocationBlockedSchedule::isDateBlocked($arrData['vendorLocationID'], $arrData['reservationDate']);
			if ($returnResult) {
				$arrResponse['status'] = Config::get('constants.API_ERROR');
				$arrResponse['msg'] = 'You cannot make any reservation on the selected date.';
				return $arrResponse;
			}

			//checking the availability for the booking
			$arrTimeRangeLimits = ProductVendorLocationBookingTimeRangeLimit::checkBookingTimeRangeLimits($arrData);
			$existingReservationCount = ReservationDetails::getReservationCount($arrData);

			//converting the reservation time
			$reservationTime = strtotime($arrData['reservationTime']);

			if (!empty($arrTimeRangeLimits)) {
				foreach ($arrTimeRangeLimits as $key => $value) {
					$maxCount = ($value['max_covers_limit'] == 0) ? $value['max_tables_limit'] : $value['max_covers_limit'];

					$startTime = strtotime($value['start_time']);
					$endTime = strtotime($value['end_time']);
					
					if ($startTime <= $reservationTime && $endTime >= $reservationTime) {
						if ($maxCount == $existingReservationCount) {
							$arrResponse['status'] = Config::get('constants.API_ERROR');
							$arrResponse['msg'] = 'Sorry. Currently the place is full. Please try another day.';
							return $arrResponse;
						} else if ($maxCount > $existingReservationCount) {
							if (($maxCount - ($existingReservationCount + $arrData['partySize'])) < 0) {
								$arrResponse['status'] = Config::get('constants.API_ERROR');
								$arrResponse['msg'] = "Sorry. We have only " . abs($maxCount - $existingReservationCount) . ' seats available.';
								return $arrResponse;
							}
						}
					}
				}
			}
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			return $arrResponse;
		}

		return -1;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Validates the data entered for the edit reservation.
	 * 
	 * @access	public
	 * @param	array 	$arrData
	 * @return	array
	 * @since	v1.0.0
	 */
	public static function validateEditReservationData($arrData) {
		//array to store response
		$arrResponse = array();
		
		if($arrData['reservationType'] == 'alacarte') {
			//validating that user has not selected blocked date
			$returnResult = VendorLocationBlockedSchedules::isDateBlocked($arrData['vendorLocationID'], $arrData['reservationDate']);
			if ($returnResult) {				
				$arrResponse['status'] = Config::get('constants.API_ERROR');
				$arrResponse['msg'] = 'You cannot make any reservation on the selected date.';
				return $arrResponse;
			}
			
			//reading details of the existing reservation from tables
			$arrCurrentReservation = ReservationDetails::getActiveReservationDetail($arrData['reservationID']);
			
		
			
			//checking the availability for the booking
			$arrTimeRangeLimits = VendorLocationBookingTimeRangeLimit::checkBookingTimeRangeLimits($arrData);
			$existingReservationCount = ReservationDetails::getReservationCount($arrData);
			
			if($arrData['vendorLocationID'] == $arrCurrentReservation['vendorLocationID']) {
				//removing the existing number with max number
				$existingReservationCount = $existingReservationCount - $arrCurrentReservation['numOfPersons'];
			}
			
			//converting the reservation time
			$reservationTime = strtotime($arrData['reservationTime']);
			
			if (!empty($arrTimeRangeLimits)) {
				foreach ($arrTimeRangeLimits as $key => $value) {
					$maxCount = ($value['max_covers_limit'] == 0) ? $value['max_tables_limit'] : $value['max_covers_limit'];
					
					if ($maxCount == $existingReservationCount) {
						$arrResponse['status'] = Config::get('constants.API_ERROR');
						$arrResponse['msg'] = 'Sorry. Currently the place is full. Please try another day.';
						return $arrResponse;
					} else if ($maxCount > $existingReservationCount) {
						if (($maxCount - ($existingReservationCount + $arrData['partySize'])) < 0) {
							$arrResponse['status'] = Config::get('constants.API_ERROR');
							$arrResponse['msg'] = "Sorry. We have only " . $maxCount - $arrReservationCount . ' seats available.';
							return $arrResponse;
						}
					}
				}
			}
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			return $arrResponse;
			
			
		} else if($arrData['reservationType'] == 'experience') {
			//validating that user has not selected blocked date
			$returnResult = ProductVendorLocationBlockedSchedule::isDateBlocked($arrData['vendorLocationID'], $arrData['reservationDate']);
			if ($returnResult) {
				$arrResponse['status'] = Config::get('constants.API_ERROR');
				$arrResponse['msg'] = 'You cannot make any reservation on the selected date.';
				return $arrResponse;
			}			
			
			//reading details of the existing reservation from tables
			$arrCurrentReservation = ReservationDetails::getActiveReservationDetail($arrData['reservationID']);
			
			//checking the availability for the booking
			$arrTimeRangeLimits = ProductVendorLocationBookingTimeRangeLimit::checkBookingTimeRangeLimits($arrData);
			$existingReservationCount = ReservationDetails::getReservationCount($arrData);
			
			if($arrData['vendorLocationID'] == $arrCurrentReservation['vendorLocationID']) {
				//removing the existing number with max number
				$existingReservationCount = $existingReservationCount - $arrCurrentReservation['numOfPersons'];
			}
			
			//converting the reservation time
			$reservationTime = strtotime($arrData['reservationTime']);

			if (!empty($arrTimeRangeLimits)) {
				foreach ($arrTimeRangeLimits as $key => $value) {
					$maxCount = ($value['max_covers_limit'] == 0) ? $value['max_tables_limit'] : $value['max_covers_limit'];

					$startTime = strtotime($value['start_time']);
					$endTime = strtotime($value['end_time']);
					
					if ($startTime <= $reservationTime && $endTime >= $reservationTime) {
						if ($maxCount == $existingReservationCount) {
							$arrResponse['status'] = Config::get('constants.API_ERROR');
							$arrResponse['msg'] = 'Sorry. Currently the place is full. Please try another day.';
							return $arrResponse;
						} else if ($maxCount > $existingReservationCount) {
							if (($maxCount - ($existingReservationCount + $arrData['partySize'])) < 0) {
								$arrResponse['status'] = Config::get('constants.API_ERROR');
								$arrResponse['msg'] = "Sorry. We have only " . abs($maxCount - $existingReservationCount) . ' seats available.';
								return $arrResponse;
							}
						}
					}
				}
			}
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			return $arrResponse;
		}
		return -1;
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
		$queryResult = DB::table('reservation_details as rd')
						->leftJoin('vendor_locations as vl','vl.id','=', 'rd.vendor_location_id')
						->leftJoin('product_vendor_locations as pvl', function($join){
							$join->on('pvl.product_id','=','rd.product_id')
								 ->on('pvl.vendor_location_id','=','rd.vendor_location_id')
								 ->where('pvl.status', '=','Active');
						})
						->leftJoin('products','products.id','=','rd.product_id')
						->leftJoin('vendors','vendors.id','=','vl.vendor_id')
						->leftJoin('product_attributes_text as pat','pat.product_id','=','products.id')
						->leftJoin('product_attributes as pa','pa.id','=','pat.product_attribute_id')
						->leftJoin('vendor_location_attributes_text as vlat','vlat.vendor_location_id','=','vl.id')
						->leftJoin('vendor_attributes as va','va.id','=','vlat.vendor_attribute_id')
						//->leftJoin('vendor_locations as vl2','vl2.id','=','pvl.vendor_location_id')
						->leftJoin('locations as ploc','ploc.id','=','vl.location_id')
						->leftJoin('vendor_location_address as pvla','pvla.vendor_location_id','=','rd.vendor_location_id')
						->leftJoin('vendor_location_address as vvla','vvla.vendor_location_id','=','rd.vendor_location_id')
						->leftJoin('locations as pvlaloc', 'pvlaloc.id','=', 'pvla.city_id')
						->leftJoin('locations as vvlaloc', 'vvlaloc.id','=', 'vvla.city_id')						
						->leftJoin('locations as vloc', 'vloc.id','=', 'vl.location_id')
						//=======Image Logic Start ================	
						->leftJoin('product_media_map as pmm','pmm.product_id', '=', 'products.id')						
						->leftJoin('media_resized_new as mrn3', function($join) {
												$join->on('mrn3.media_id', '=', 'pmm.media_id')
													  ->where('mrn3.image_type', '=' , 'mobile_listing_ios_experience');
						})
						->leftJoin('media_resized_new as mrn4', function($join) {
												$join->on('mrn4.media_id', '=', 'pmm.media_id')
													  ->where('mrn4.image_type', '=', 'mobile_listing_android_experience');
						})								
						->leftJoin('vendor_locations_media_map as vlmm', 'vlmm.vendor_location_id','=', 'vl.id')
						->leftJoin('media_resized_new as mrn1', function($join) {
												$join->on('mrn1.media_id', '=', 'vlmm.media_id')
													  ->where('mrn1.image_type', '=' , 'mobile_listing_ios_alacarte');
						})
						->leftJoin('media_resized_new as mrn2', function($join) {
												$join->on('mrn2.media_id', '=', 'vlmm.media_id')
													  ->where('mrn2.image_type', '=', 'mobile_listing_android_alacarte');
						})
						//=======Image Logic End ================
						->where('rd.user_id', $userID)
						//->where('pvl.status', 'Active')
						//->whereIn('reservation_status',array('new','edited'))
						->select('rd.id','rd.user_id','rd.reservation_status','rd.reservation_date',
									'rd.reservation_time','rd.no_of_persons', 'products.name as product_name','vendors.id as vendor_id',
									 'vendors.name as vendor_name', 'rd.reservation_type', 'products.id as product_id',
									 'rd.vendor_location_id', 'rd.product_vendor_location_id', 'rd.special_request',
									 'rd.giftcard_id', 'rd.guest_name', 'rd.guest_name', 'rd.guest_email',
									 'rd.guest_phone', 'rd.points_awarded', 'pvl.id as pvl_id',
									 DB::raw('MAX(IF(pa.alias="short_description", pat.attribute_value,"")) AS product_short_description'),
									 DB::raw('MAX(IF(va.alias="short_description", vlat.attribute_value, ""))AS vendor_short_description'),
									 'ploc.name as product_locality','pvla.address as product_address',
									 'vloc.name as vendor_locality', 'vvla.address as vendor_address',
									 'pvla.city_id as product_city_id', 'vvla.city_id as vendor_city_id', //Added for city-id
									 'pvlaloc.name as product_city_name', 'vvlaloc.name as vendor_city_name', //Added for city-name
									 'products.slug as experience_slug', 'vl.slug as alacarte_slug', 'vendors.name as restaurant_name',
									 'mrn1.file as ios_image_alacarte', 'mrn2.file as android_image_alacarte',
									 'mrn3.file as ios_image_experience', 'mrn4.file as android_image_experience')
						->orderBy('rd.reservation_date','asc')
						->orderBy('rd.reservation_time','asc')
						->groupBy('rd.id') 
						->get();    
		//echo $queryResult->toSql(); die(); 
					
		//array to store the information
		$arrData = array();
		
		//sub array to store the previous reservation information
		$arrData['data']['pastReservation'] = array();
		
		//sub array to store the upcoming reservation information
		$arrData['data']['upcomingReservation'] = array(); 
		
		if($queryResult) {
			//converting current day time to timestamp
			$currentTimestamp = strtotime(date('Y-m-d H:i:s'));
			
			//getting each reservation addons
			foreach($queryResult as $row) {
				$arrReservation[] = $row->id;
			}
			
			//array to keep record of addons of reservation
			$arrSelectedAddOn = array();
			$arrSchedule = array();
			$arrAddOn = array();
			
			
			$arrSelectedAddOn = self::getReservationAddonsDetails($arrReservation);
			//$arrAddOn = Experiences::readExperienceAddOns($row->product_id);
			
			foreach($queryResult as $row) {
				//converting reservation day time to timestamp
				$reservationTimestamp = strtotime($row->reservation_date.' '.date('H:i:s',strtotime($row->reservation_time)));
				if($reservationTimestamp >= $currentTimestamp) {
					if($row->reservation_type == 'experience' || $row->reservation_type == 'event') {
						$day = date('D',strtotime($row->reservation_date));
						$arrSchedule = Schedules::getExperienceLocationSchedule($row->product_id, NULL,  $day, $row->vendor_location_id);
						$arrAddOn = Experiences::readExperienceAddOns($row->product_id);
						$slug = $row->experience_slug; 	
					}
					else if($row->reservation_type == 'alacarte') {
						$day = date('D',strtotime($row->reservation_date));
						$arrSchedule = Schedules::getVendorLocationSchedule($row->vendor_location_id, $day);
						$slug = $row->alacarte_slug;
					}
				}
				
				if( empty($row->vendor_name) && empty($row->product_name) ) {
					continue;
					// $name = "";
					// $product_id = "";
					// $address = "";
					// $locality = "";
				}
				else {
					$name = (empty($row->product_name)) ? $row->vendor_name : $row->product_name;
					$product_id = ($row->product_vendor_location_id == 0) ? $row->vendor_id:$row->product_id;
					$address = (empty($row->product_address)) ? $row->vendor_address : $row->product_address;
					$locality = (empty($row->product_locality)) ? $row->vendor_locality : $row->product_locality;
					$city = (empty($row->product_city_id)) ? $row->vendor_city_id : $row->product_city_id;
					$cityName = (empty($row->product_city_name)) ? $row->vendor_city_name : $row->product_city_name;		

				}
				
				
				$arrDatum = array(
									'id' => $row->id,
									'short_description' => (empty($row->product_short_description)) ? $row->vendor_short_description : $row->product_short_description,
									'status' => (empty($row->reservation_status)) ? "" : $row->reservation_status,
									'date' => (empty($row->reservation_date)) ? "" : $row->reservation_date,
									'time' => (empty($row->reservation_time)) ? "" : date('H:i:s',strtotime($row->reservation_time)),
									'no_of_persons' => (empty($row->no_of_persons)) ? "" : $row->no_of_persons,
									'name' => $name,
									'type' => (empty($row->reservation_type)) ? "" : $row->reservation_type,
									'product_id' => $product_id,
									'vl_id' => (empty($row->pvl_id)) ? $row->vendor_location_id:$row->pvl_id,
									'special_request' => (is_null($row->special_request)) ? "" : $row->special_request,
									'giftcard_id' => (is_null($row->giftcard_id)) ? "" : $row->giftcard_id,
									'guest_name' => (empty($row->guest_name)) ? "" : $row->guest_name,
									'guest_email' => (empty($row->guest_email)) ? "" : $row->guest_email,
									'guest_phone' => (empty($row->guest_phone)) ? "" : $row->guest_phone,
									'reward_point' => (empty($row->points_awarded)) ? 0 : $row->points_awarded,
									'selected_addon' => (array_key_exists($row->id, $arrSelectedAddOn)) ? $arrSelectedAddOn[$row->id]:array(),
									'day_schedule' => $arrSchedule,
									'address' => array(
														'address' => (empty($address)) ? "" : $address,
														'locality' => (empty($locality)) ? "" : $locality,
													),
									'addons' => (empty($arrAddOn)) ? [] : $arrAddOn,
									'slug' => (empty($slug)) ? "" : $slug,
									'city_id' => (empty($city)) ? "" : $city,	
									'city' => (empty($cityName)) ? "" : $cityName,	
									'restaurant_name' => (empty($row->restaurant_name)) ? "" : $row->restaurant_name,
									'image' => array(
													'mobile_listing_ios_alacarte' => (empty($row->ios_image_alacarte))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->ios_image_alacarte,
													'mobile_listing_android_alacarte' => (empty($row->android_image_alacarte))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->android_image_alacarte,
													'mobile_listing_android_experience' => (empty($row->android_image_experience))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->android_image_experience,
													'mobile_listing_ios_experience' => (empty($row->ios_image_experience)) ? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->ios_image_experience,
												 ),							
								);
				
				if($reservationTimestamp >= $currentTimestamp && $row->reservation_status != 'cancel' ) {
					array_push($arrData['data']['upcomingReservation'],$arrDatum);
				}
				else {
					array_push($arrData['data']['pastReservation'],$arrDatum);
				}
				
			}  
			$arrData['data']['pastReservationCount'] = count($arrData['data']['pastReservation']);
			$arrData['data']['upcomingReservationCount'] = count($arrData['data']['upcomingReservation']);
			$arrData['status'] = Config::get('constants.API_SUCCESS');
		}
		else {
			$arrData['status'] = Config::get('constants.API_SUCCESS');
			$arrData['msg'] = 'No matching record found.';
			$arrData['data']['pastReservationCount'] = 0;  
			$arrData['data']['upcomingReservationCount'] = 0; 
		}
		return $arrData;
	}

	//-------------------------------------------------------------------
	
	/**
	 * Reads the details of the add-ons associated with a reservation.
	 * 
	 * @access	public
	 * @static
	 * @param	array 	$arrReservation
	 * @return	array 
	 */
	public static function getReservationAddonsDetails($arrReservation) {		
		
		$reservationIDString = implode(",", $arrReservation);
		$queryResult = DB::select('select * from (    
						select `ravd`.`id`, `ravd`.`options_id` as `prod_id`, 
						`ravd`.`no_of_persons` as `qty`, `ravd`.`reservation_id` 
						from `reservation_addons_variants_details` as `ravd` 
						inner join `products` as `p` on `p`.`id` = `ravd`.`options_id` 
						where `ravd`.`reservation_id` in ('.$reservationIDString .') 
						order by `ravd`.`created_at` desc    
						) as abcd group by `prod_id`');							
		
		//array to store the addons details
		$arrData = array();
		
		foreach($queryResult as $row) {
			if(array_key_exists($row->reservation_id, $arrData)) {
				$arrData[$row->reservation_id][] = array(
														'id' => $row->id,
														'prod_id' => $row->prod_id,
														'qty' => $row->qty
													);
			}
			else {
				$arrData[$row->reservation_id][] = array(
														'id' => $row->id,
														'prod_id' => $row->prod_id,
														'qty' => $row->qty
													);
			}
		}
		return $arrData;
	}	
}
//end of class Reservation
//end of file Reservation.php