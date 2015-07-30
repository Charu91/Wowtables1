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
		$reservationTime = $value;
		//$reservationTime = date("H:i A", strtotime($value));
		//$value = date("h:i A", strtotime($value));
		
		$query = DB::table('reservation_details as rd')
							->join('user_devices as ud','ud.user_id','=','rd.user_id')
							->where('rd.reservation_date','=',$reservationDate)
							->where('ud.access_token',$accessToken)
							->whereIn('rd.reservation_status',['new', 'edited'])
							->select('rd.id', 'rd.reservation_date', 'rd.reservation_time'
									//DB::raw('(ABS(TIME_TO_SEC(TIMEDIFF("'.$value.'",rd.reservation_time))/3600)) as time_difference')	
									)
							->get();
							//->orderBy('time_difference','ASC');

		if(!empty($query))
	      { 
	        foreach ($query as $value) {
	           //$reserv_date = $value->reservation_date;
	           //$reserv_time = $value->reservation_time;

	                  $last_reserv_date = date('Y-m-d',strtotime($value->reservation_date));
	                  $last_reserv_time =  strtotime($value->reservation_time);

	                  $last_reserv_time_2_hours_after = strtotime('+2 Hour',$last_reserv_time);	                   
	                  $last_reserv_time_2_hours_before = strtotime('-2 Hour',$last_reserv_time);
	                  	
	                  if($reservationDate == $last_reserv_date){
	                        
	                        $new_reserv = strtotime($reservationTime);

	                        if( $new_reserv >= $last_reserv_time_2_hours_before && 
	                        	$new_reserv <= $last_reserv_time_2_hours_after) {
	                            //$success =1; 	                            
	                            return FALSE;
	                           //break; 
	                        }
	                    }

	        }
	      }

		return TRUE;   
			
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
		$reservationDate = date( "Y-m-d", strtotime($this->data['reservationDate']));
		$currentDay = date('Y-m-d');

		$cutOffTime = date( "H:i", strtotime( Config::get('constants.SERVER_TIME_CUTOFF_FOR_RESERVATION') ) );
		//$value = date( "H:i", strtotime($value));
		$currentTime = date( "H:i"); 
					
		if($currentDay == $reservationDate && $cutOffTime <= $currentTime ) { 
			// $currentTime = strtotime(date("H:i:s"));
			// $cutOffTime = strtotime(Config::get('constants.SERVER_TIME_CUTOFF_FOR_RESERVATION'));
		
			// $timeDiff = $currentTime - $cutOffTime;
				
			// if($timeDiff >= 0) {
			// 	return FALSE;
			// }
			return FALSE;
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