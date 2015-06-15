<?php
namespace WowTables\Http\Models;

use DB;
use Config;

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
									'reservationTime' => 'required|OutsidePrevReservationTimeRange:reservationDate',
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
		$queryResult = DB::table(DB::raw('vendor_locations as vl')) 
						->leftJoin(DB::raw('vendor_location_address as vla'), 'vla.vendor_location_id', '=', 'vl.id') 
						->join('locations', 'locations.id', '=', 'vl.location_id') 
						->leftJoin('vendor_locations_limits as vll', 'vll.vendor_location_id', '=', 'vl.id') 
						->where('vl.id', $vendorLocationID) 
						->select('vl.id', 'locations.name as area', 'vla.latitude', 
									'vla.longitude', 'vll.min_people_per_reservation', 
									'vll.max_people_per_reservation', 'vll.min_people_increments') 
						->get();

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
			$arrLocLmt[] = array(
								'vl_id' => $row -> id, 
								'area' => $row -> area, 
								'min_people' => (is_null($row -> min_people_per_reservation)) ? '' : $row -> min_people_per_reservation, 
								'max_people' => (is_null($row -> max_people_per_reservation)) ? '' : $row -> max_people_per_reservation, 
								'increment' => (is_null($row -> min_people_increments)) ? '' : $row -> min_people_increments, 
								'latitude' => $row -> latitude, 
								'longitude' => $row -> longitude, 
								'blocked_dates' => (array_key_exists($row -> id, $arrBlockedDates)) ? $arrBlockedDates[$row -> id] : array(), 
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
		$queryResult = DB::table('product_vendor_locations as pvl') 
							->join('vendor_locations as vl', 'vl.id', '=', 'pvl.vendor_location_id') 
							->join('locations', 'locations.id', '=', 'vl.location_id') 
							->leftJoin('product_vendor_locations_limits as pvll', 'pvll.product_vendor_location_id', '=', 'pvl.id')
							->leftJoin('vendor_location_address as vla', 'vla.vendor_location_id','=','vl.id') 
							->where('pvl.product_id', $experienceID) 
							->select('pvl.vendor_location_id as id', 'locations.name as area', 
									'vla.latitude', 'vla.longitude', 'pvll.min_people_per_reservation', 
									'pvll.max_people_per_reservation', 'pvll.min_people_increments',
									'pvl.product_id as experience_id','pvl.id as pvl_id') 
							->get();

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
								);
		}

		return $arrLocLmt;
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
						->leftJoin('product_vendor_locations as pvl','pvl.id','=','rd.product_vendor_location_id')
						->leftJoin('products','products.id','=','pvl.product_id')
						->leftJoin('vendors','vendors.id','=','vl.vendor_id')
						->leftJoin('product_attributes_text as pat','pat.product_id','=','products.id')
						->leftJoin('product_attributes as pa','pa.id','=','pat.product_attribute_id')
						->leftJoin('vendor_location_attributes_text as vlat','vlat.vendor_location_id','=','vl.id')
						->leftJoin('vendor_attributes as va','va.id','=','vlat.vendor_attribute_id')
						->leftJoin('vendor_locations as vl2','vl2.id','=','pvl.vendor_location_id')
						->leftJoin('locations as ploc','ploc.id','=','vl2.location_id')
						->leftJoin('vendor_location_address as pvla','pvla.vendor_location_id','=','pvl.vendor_location_id')
						->leftJoin('vendor_location_address as vvla','vvla.vendor_location_id','=','rd.vendor_location_id')
						->leftJoin('locations as vloc', 'vloc.id','=', 'vl.location_id')
						->where('rd.user_id', $userID)
						->whereIn('reservation_status',array('new','edited'))
						->select('rd.id','rd.user_id','rd.reservation_status','rd.reservation_date',
									'rd.reservation_time','rd.no_of_persons', 'products.name as product_name','vendors.id as vendor_id',
									 'vendors.name as vendor_name', 'rd.reservation_type', 'products.id as product_id',
									 'rd.vendor_location_id', 'rd.product_vendor_location_id', 'rd.special_request',
									 'rd.giftcard_id', 'rd.guest_name', 'rd.guest_name', 'rd.guest_email',
									 'rd.guest_phone', 'rd.points_awarded',
									 DB::raw('MAX(IF(pa.alias="short_description", pat.attribute_value,"")) AS product_short_description'),
									 DB::raw('MAX(IF(va.alias="short_description", vlat.attribute_value, ""))AS vendor_short_description'),
									 'ploc.name as product_locality','pvla.address as product_address',
									 'vloc.name as vendor_locality', 'vvla.address as vendor_address')
						->orderBy('rd.reservation_date','asc')
						->orderBy('rd.reservation_time','asc')
						->groupBy('rd.id') 
						->get();
		//echo $queryResult->toSql(); 
		
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
				$reservationTimestamp = strtotime($row->reservation_date.' '.$row->reservation_time);
				if($reservationTimestamp >= $currentTimestamp) {
					if($row->reservation_type == 'experience') {
						$day = date('D',strtotime($row->reservation_date));
						$arrSchedule = Schedules::getExperienceLocationSchedule($row->product_id, NULL,  $day);
						$arrAddOn = Experiences::readExperienceAddOns($row->product_id);
						
					}
					else if($row->reservation_type == 'alacarte') {
						$day = date('D',strtotime($row->reservation_date));
						$arrSchedule = Schedules::getVendorLocationSchedule($row->vendor_location_id, $day);
					}
				}
				$arrDatum = array(
									'id' => $row->id,
									'short_description' => (empty($row->product_short_description)) ? $row->vendor_short_description : $row->product_short_description,
									'status' => (empty($row->reservation_status)) ? "" : $row->reservation_status,
									'date' => (empty($row->reservation_date)) ? "" : $row->reservation_date,
									'time' => (empty($row->reservation_time)) ? "" : $row->reservation_time,
									'no_of_persons' => (empty($row->no_of_persons)) ? "" : $row->no_of_persons,
									'name' => (empty($row->vendor_name)) ? $row->product_name : $row->vendor_name,
									'type' => (empty($row->reservation_type)) ? "" : $row->reservation_type,
									'product_id' => ($row->product_vendor_location_id == 0) ? $row->vendor_id:$row->product_id,
									'vl_id' => ($row->vendor_location_id == 0) ? $row->product_vendor_location_id:$row->vendor_location_id,
									'special_request' => (is_null($row->special_request)) ? "" : $row->special_request,
									'giftcard_id' => (is_null($row->giftcard_id)) ? "" : $row->giftcard_id,
									'guest_name' => (empty($row->guest_name)) ? "" : $row->guest_name,
									'guest_email' => (empty($row->guest_email)) ? "" : $row->guest_email,
									'guest_phone' => (empty($row->guest_phone)) ? "" : $row->guest_phone,
									'reward_point' => (empty($row->points_awarded)) ? "" : $row->points_awarded,
									'selected_addon' => (array_key_exists($row->id, $arrSelectedAddOn)) ? $arrSelectedAddOn[$row->id]:array(),
									'day_schedule' => $arrSchedule,
									'address' => array(
														'address' => (empty($row->product_address)) ? $row->vendor_address : $row->product_address,
														'locality' => (empty($row->product_locality)) ? $row->vendor_locality : $row->product_locality,
														
													),
									'addons' => (empty($arrAddOn)) ? "" : $arrAddOn,
								);
				
				if($reservationTimestamp >= $currentTimestamp ) {
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

	//-----------------------------------------------------------------
	
	/**
	 * Reads the details of the add-ons associated with a reservation.
	 * 
	 * @access	public
	 * @static
	 * @param	array 	$arrReservation
	 * @return	array 
	 */
	public static function getReservationAddonsDetails($arrReservation) {
		$queryResult = DB::table('reservation_addons_variants_details as ravd')
							->join('products as p','p.id','=','ravd.options_id')
							->whereIn('ravd.reservation_id',$arrReservation)
							->select('ravd.id','ravd.options_id as prod_id','ravd.no_of_persons as qty',
										'ravd.reservation_id')
							->get();
		
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