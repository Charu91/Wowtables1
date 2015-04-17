<?php namespace WowTables\Http\Models;

use DB;

class Schedules {

    public function fetchAll(){
        $query = '
            SELECT
                s.`id` as `schedule_id`,
                s.`day`,
                s.`day_short`,
                s.`day_numeric`,
                TIME_FORMAT(ts.`time`, "%l:%i:%p") AS `time_key`,
                TIME_FORMAT(ts.`time`, "%l:%i %p") AS `time`,
                ts.`slot_type`
            FROM schedules AS `s`
            INNER JOIN time_slots AS `ts` ON s.`time_slot_id` = ts.`id`
            ORDER BY ts.`id`, ts.`slot_type`, ts.`time` ASC
        ';

        $scheduleResults = DB::select($query);

        if($scheduleResults){
            $schedules = [];

            foreach($scheduleResults as $schedule){
                if(!isset($schedules[$schedule->time_key]))
                    $schedules[$schedule->time_key] = [];

                if(!isset($schedules[$schedule->time_key]['time']))
                    $schedules[$schedule->time_key]['time'] = $schedule->time;

                if(!isset($schedules[$schedule->time_key]['slot_type']))
                    $schedules[$schedule->time_key]['slot_type'] = $schedule->slot_type;

                if(!isset($schedules[$schedule->time_key][$schedule->day_short]))
                    $schedules[$schedule->time_key][$schedule->day_short] = [];

                $schedules[$schedule->time_key][$schedule->day_short]['schedule_id'] = $schedule->schedule_id;
                $schedules[$schedule->time_key][$schedule->day_short]['day'] = $schedule->day;
                $schedules[$schedule->time_key][$schedule->day_short]['day_numeric'] = $schedule->day_numeric;

            }

            return [
                'status' => 'success',
                'schedules' => $schedules
            ];
        }else{
            return [
                'status' => 'failure',
                'message' => 'Contact the sys admin for help'
            ];
        }
    }

    public function available_time_slots( $start_time, $end_time )
    {
        $query = '
            SELECT
                s.`id` as `schedule_id`,
                s.`day`,
                s.`day_short`,
                s.`day_numeric`,
                TIME_FORMAT(ts.`time`, "%l:%i:%p") AS `time_key`,
                TIME_FORMAT(ts.`time`, "%l:%i %p") AS `time`,
                ts.`slot_type`
            FROM schedules AS `s`
            INNER JOIN time_slots AS `ts` ON s.`time_slot_id` = ts.`id`
            WHERE ts.`time` between ? and ?
            ORDER BY ts.`id`, ts.`slot_type`, ts.`time` ASC
        ';

        $scheduleResults = DB::select($query, [$start_time,$end_time]);

        if($scheduleResults){
            $schedules = [];

            foreach($scheduleResults as $schedule){
                if(!isset($schedules[$schedule->time_key]))
                    $schedules[$schedule->time_key] = [];

                if(!isset($schedules[$schedule->time_key]['time']))
                    $schedules[$schedule->time_key]['time'] = $schedule->time;

                if(!isset($schedules[$schedule->time_key]['slot_type']))
                    $schedules[$schedule->time_key]['slot_type'] = $schedule->slot_type;

                if(!isset($schedules[$schedule->time_key][$schedule->day_short]))
                    $schedules[$schedule->time_key][$schedule->day_short] = [];

                $schedules[$schedule->time_key][$schedule->day_short]['schedule_id'] = $schedule->schedule_id;
                $schedules[$schedule->time_key][$schedule->day_short]['day'] = $schedule->day;
                $schedules[$schedule->time_key][$schedule->day_short]['day_numeric'] = $schedule->day_numeric;

            }

            return [
                'status' => 'success',
                'schedules' => $schedules
            ];
        }else{
            return [
                'status' => 'failure',
                'message' => 'Contact the sys admin for help'
            ];
        }
    }

	//-----------------------------------------------------------------
	
	/**
	 * Returns the schedule for a experience location.
	 * 
	 * @access	public
	 * @param	integer	$productVendorLocationID
	 * @param	string	$day
	 * @since	1.0.0
	 * @return	array
	 * @author	Parth Shukla <parthshukla@ahex.co.in>
	 */
	public static function getExperienceLocationSchedule($productVendorLocationID, $day=NULL) {
		if(is_null($day)) {
			$day = strtolower(date("D"));
		}	
		
		$schedules = DB::table('schedules')
						->join(DB::raw('time_slots as ts'),'ts.id','=','schedules.time_slot_id')
						->join(DB::raw('product_vendor_location_booking_schedules as pvlbs'),'pvlbs.schedule_id','=','schedules.id')
						->where('schedules.day_short',$day)
						->where('pvlbs.product_vendor_location_id', $productVendorLocationID)
						->select('schedules.id','ts.time','ts.slot_type')
						->get();
						
		#array to hold information
		$arrData = array();
		
		if($schedules) {
			foreach($schedules as $row) {
				$arrData[] = array(
									'schedule_id' => $row->id,
									'time' => $row->time,
									'slot_type' => $row->slot_type
								);
			}
		}
		return $arrData;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Returns the schedule for a vendor location.
	 * 
	 * @access	public
	 * @param	integer	$vendorLocationID
	 * @param	string	$day
	 * @since	1.0.0
	 * @return	array
	 * @author	Parth Shukla <parthshukla@ahex.co.in>
	 */
	public static function getVendorLocationSchedule($vendorLocationID, $day=NULL) {
		if(is_null($day)) {
			$day = strtolower(date("D"));
		}	
		
		$schedules = DB::table('schedules')
						->join(DB::raw('time_slots as ts'),'ts.id','=','schedules.time_slot_id')
						->join(DB::raw('vendor_location_booking_schedules as vlbs'),'vlbs.schedule_id','=','schedules.id')
						->where('schedules.day_short',$day)
						->where('vlbs.vendor_location_id', $vendorLocationID)
						->select('schedules.id','ts.time','ts.slot_type')
						->get();
						
		#array to hold information
		$arrData = array();
		
		if($schedules) {
			foreach($schedules as $row) {
				$arrData[] = array(
									'schedule_id' => $row->id,
									'time' => $row->time,
									'slot_type' => $row->slot_type
								);
			}
		}
		return $arrData;
	}

} 
//end of class Schedules.php
//end of file WowTables\Http\Models\Schedules.php