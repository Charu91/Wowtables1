<?php namespace WowTables\Http\Models;

use DB;
use Config;

use WowTables\Http\Models\Eloquent\Products\ProductVendorLocationBlockSchedule;
use WowTables\Http\Models\Eloquent\Vendors\VendorLocationBlockedSchedules;
use WowTables\Http\Models\Eloquent\ReservationDetails;
use WowTables\Http\Models\Eloquent\Vendors\VendorLocationBookingTimeRangeLimit;
/**
 * 
 */
class Reservation {
	
	/**
	 * Query to read the details of the vendor locations and
	 * reservation limits.
	 * 
	 * @access	public
	 * @param	integer	$vendorID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getVendorLocationAndLimit($vendorID) {
		$queryResult = DB::table(DB::raw('vendor_locations as vl'))
						->join(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','vl.id')
						->join('locations','locations.id','=','vla.area_id')
						->leftJoin(DB::raw('vendor_locations_limits as vll'),'vll.vendor_location_id','=','vl.id')
						->where('vl.vendor_id',$vendorID)
						->select('vl.id', DB::raw('locations.name as area'), 'vla.latitude', 'vla.longitude', 
									'vll.min_people_per_reservation', 'vll.max_people_per_reservation',
									'vll.min_people_increments')
						->get();
		
		//array to read the locations and limits
		$arrLocLmt = array();
		
		//array to keep all location id
		$arrLocation = array();
		
		#reading the blocked dates
		foreach($queryResult as $row){
			$arrLocation[] = $row->id;
		}
		$arrBlockedDates = VendorLocationBlockedSchedules::getBlockedDate($arrLocation);
		
		foreach( $queryResult as $row ) {
			$arrLocLmt[] = array(
								'vl_id' => $row->id,
								'area' => $row->area,
								'min_people' => (is_null($row->min_people_per_reservation)) ? '':$row->min_people_per_reservation,
								'max_people' => (is_null($row->max_people_per_reservation)) ? '':$row->max_people_per_reservation,
								'increment' => (is_null($row->min_people_increments))? '':$row->min_people_increments,
								'latitude' => $row->latitude,
								'longitude' => $row->longitude,
								'blocked_dates' => (array_key_exists($row->id, $arrBlockedDates))?$arrBlockedDates[$row->id]:array(),
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
		$queryResult = DB::table(DB::raw('product_vendor_locations as pvl'))
						->join(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','pvl.vendor_location_id')
						->join('locations','locations.id','=','vla.area_id')
						->leftJoin(DB::raw('product_vendor_locations_limits as pvll'), 'pvll.product_vendor_location_id','=','pvl.id')
						->where('pvl.product_id',$experienceID)
						->select('pvl.vendor_location_id as id',DB::raw('locations.name as area'),'vla.latitude',
									'vla.longitude', 'pvll.min_people_per_reservation', 'pvll.max_people_per_reservation',
									'pvll.min_people_increments')
						->get();
		
		#array to read experiences and location limits
		$arrLocLmt = array();
		
		//array to keep all location id
		$arrLocation = array();
		
		#reading the blocked dates
		foreach($queryResult as $row){
			$arrLocation[] = $row->id;
		}
		$arrBlockedDates = ProductVendorLocationBlockSchedule::getBlockedDate($arrLocation);
		
		foreach( $queryResult as $row ) {
			$arrLocLmt[] = array(
								'vl_id' => $row->id,
								'area' => $row->area,
								'min_people' => (is_null($row->min_people_per_reservation)) ? '': $row->min_people_per_reservation,
								'max_people' => (is_null($row->max_people_per_reservation)) ? '': $row->max_people_per_reservation,
								'increment' => (is_null($row->min_people_increments)) ? '': $row->min_people_increments,
								'latitude' => $row->latitude,
								'longitude' => $row->longitude,
								'blocked_dates' => (array_key_exists($row->id, $arrBlockedDates))?$arrBlockedDates[$row->id]:array(),
							);
		}
		
		return $arrLocLmt; 
	}

	//-----------------------------------------------------------------
	
	/**
	 * Checks whether the passed date is 
	 */
	public static function checkDateAvailability($date) {
		DB::table(DB::raw('product_vendor_location_block_schedules'))
				->where(DB::raw('block_date',$date));
			
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * 
	 */
	public static function addReservationDetails($data)  {
		
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
		if($arrData['reservationType'] == 'alacarte') {
			
			//validating that user has not selected blocked date
			$returnResult = VendorLocationBlockedSchedules::isDateBlocked($arrData['vendorLocationID'], $arrData['reservationDate']);
			if($returnResult) {
				$arrResponse['status'] = Config::get('constants.API_ERROR');
				$arrResponse['msg'] = 'You cannot make any reservation on the selected date.';
				
			}
			
			//checking the availability for the booking
			$arrTimeRangeLimits = VendorLocationBookingTimeRangeLimit::checkBookingTimeRangeLimits($arrData);
			$arrReservationCount = ReservationDetails::getReservationCount($arrData);
			
			//setting the value of current day and date
			//$currentDay = strtolower(date('D'));
			//$currentDate = date('Y-m-d');
			
			if(!empty($arrTimeRangeLimits)) {
				
				foreach($arrTimeRangeLimits as $key => $value) {					
						$maxCount = ($value['max_covers_limit'] == 0 ) ? $value['max_tables_limit'] : $value['max_covers_limit'];
						
						if($maxCount == $arrReservationCount ) {
							$arrResponse['status'] = Config::get('constants.API_ERROR');
							$arrResponse['msg'] = 'Sorry. Currently the place is full. Please try another day.';
							return $arrResponse;
						}
						else if($maxCount > $arrReservationCount ) {
							if(($maxCount - ($arrReservationCount+$arrData['partyCount'])) < 0) {
								$arrResponse['status'] = Config::get('constants.API_ERROR');
								$arrResponse['msg'] = "Sorry. We have only ". $maxCount - $arrReservationCount .'seats available.';
								return $arrResponse;
							}
						}					
				}
			}
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			 return $arrResponse;
		}
		else if($arrData['type'] == 'experience') {
			
		}
		
		return -1;
	}

}
//end of class Reservation
//end of file Reservation.php
