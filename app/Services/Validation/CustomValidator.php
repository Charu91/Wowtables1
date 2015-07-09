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
		$reservationID = (isset($this->data['reservationID'])) ? $this->data['reservationID']:0; 
		//$reservationTime = $value;
		$reservationTime = date("H:i", strtotime($value));
		$value = date("H:i", strtotime($value));
		
		$query = DB::table('reservation_details as rd')
							->join('user_devices as ud','ud.user_id','=','rd.user_id')
							->where('rd.reservation_date','=',$reservationDate)
							->where('ud.access_token',$accessToken)
							->whereIn('rd.reservation_status',['new', 'edited'])
							->select('rd.id',
									DB::raw('(ABS(TIME_TO_SEC(TIMEDIFF("'.$value.'",rd.reservation_time))/3600)) as time_difference')	
									)
							->orderBy('time_difference','ASC');
		
		if($reservationID > 0) {
			$query->where('rd.id','!=',$reservationID);
		}
		
		//executing the query
		$queryResult = $query->get(); 
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

	//------------------------------------------------------------------------
	
	/**
	 * Checks whether user has sent a reservation request for the current day
	 * after the current day reservation cut off time limit.
	 * 
	 * @access	public
	 * @param	$attribute
	 * @param	$value
	 * @param	$parameter
	 * @return	boolean
	 * @since	1.0.0
	 */
	public function validateDayReservationCutOff($attribute, $value, $parameter) {
		$currentDay = date('Y-m-d');
		
		if($currentDay == $value) {
			$currentTime = strtotime(date("H:i:s"));
			$cutOffTime = strtotime(Config::get('constants.SERVER_TIME_CUTOFF_FOR_RESERVATION'));
		
			$timeDiff = $currentTime - $cutOffTime;
				
			if($timeDiff >= 0) {
				return FALSE;
			}
		}				
		return TRUE;
	}

	//-----------------------------------------------------------------

	/**
	 * Checks whether the token is valid and active or not	 
	 * 
	 * @access	public
	 * @param	$attribute
	 * @return	boolean
	 * @since	1.0.0
	 */
	public function validatePasswordResetToken($attribute, $value, $parameter) {
		
		$token=DB::table('password_request as pr')
                        ->where('pr.request_token',$value)
                        ->where('pr.status','=','active')
                        ->select('user_id')
                        ->first(); 
            if($token){
            		return TRUE;
            }
            else{
            	 	return FALSE;
            }

	}
	
}
//end of class CustomValidator
//end of file CustomValidator.php