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

} 