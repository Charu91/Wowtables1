<?php namespace WowTables\Services\Validation;

use Illuminate\Validation\Validator;
use DB;
use Config;

class CustomValidator extends Validator {
	
	/**
	 * Checks if the user has already done any reservation two hour
	 * before or after the current time for the given date.
	 * 
	 * @access	public
	 * @param	$attribute
	 * @param	$value
	 * @param	$parameters
	 * @return	boolean
	 * @since	1.0.0
	 */
	public function validateOutsidePrevReservationTimeRange($attribute, $value, $parameters) {
		
		$reservationDate = $this->data['reservationDate'];
		$accessToken = $this->data['access_token'];
		$reservationTime = $value;
		
		$queryResult = DB::table('reservation_details as rd')
							->join('user_devices as ud','ud.user_id','=','rd.user_id')
							->where('rd.reservation_date','=',$reservationDate)
							->where('ud.access_token',$accessToken)
							->select('rd.id',
									DB::raw('(ABS(TIME_TO_SEC(TIMEDIFF("'.$value.'",rd.reservation_time))/3600)) as time_difference')	
									)
							->orderBy('time_difference','ASC')
							->get();
		if($queryResult) {
			$hourLimit = Config::get('constants.NEXT_RESERVATION_TIME_RANGE_LIMIT');
			foreach($queryResult as $row) {
				if($row->time_difference <= $hourLimit) {
					return false;
				}
			}
		}		
		return true;		
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Checks if the date is not a previous date.
	 * 
	 * @access	public
	 * @param	$attribute
	 * @param	$value
	 * @param	$parameter
	 * @return	boolean
	 * @since	1.0.0
	 */
	public function validateNotPreviousDate($attribute, $value, $parameter) {
		if(strtotime($value) >= strtotime(date('Y-m-d'))) {
			return true;
		}		
		return false;
	}	
	
}
//end of class CustomValidator
//end of file CustomValidator.php