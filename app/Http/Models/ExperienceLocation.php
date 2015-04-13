<?php namespace WowTables\Http\Models;

use DB;

class ExperienceLocation {

    public function create($data)
    {
        DB::beginTransaction();

        $productVendorLocationInsertData = [
            'product_id' => $data['experience_id'],
            'vendor_location_id' => $data['restaurant_location_id'],
            //'slug' => $data['slug'],
            //'location_id' => $data['location_id'],
            'status' => $data['status']
        ];

        $productVendorLocationId = DB::table('product_vendor_locations')->insertGetId($productVendorLocationInsertData);

        if($productVendorLocationId){
            if(!empty($data['attributes'])){
                $AttributesSaved = $this->saveReservationLimits($productVendorLocationId, $data['attributes']);

                if($AttributesSaved['status'] !== 'success'){
                    $AttributesSaved['message'] = 'Could not create the Experience Location Reservation Limits. Contact the system admin';
                    return $AttributesSaved;
                }
            }

            if(!empty($data['schedules'])){
                $schedulesSaved = $this->saveSchedules($productVendorLocationId, $data['schedules']);

                if($schedulesSaved['status'] !== 'success'){
                    $schedulesSaved['message'] = 'Could not create the Experience Location Schedules. Contact the system admin';
                    return $schedulesSaved;
                }
            }

            if(!empty($data['block_dates'])){
                $blockSchedulesSaved = $this->saveBlockDates($productVendorLocationId, $data['block_dates']);

                if($blockSchedulesSaved['status'] !== 'success'){
                    $blockSchedulesSaved['message'] = 'Could not create the Experience Location Block Schedules. Contact the system admin';
                    return $blockSchedulesSaved;
                }
            }

            if(!empty($data['reset_time_range_limits'])){
                $resetTimeRangeLimtsSaved = $this->saveTimeRangeLimits($productVendorLocationId, $data['reset_time_range_limits']);

                if($resetTimeRangeLimtsSaved['status'] !== 'success'){
                    $resetTimeRangeLimtsSaved['message'] = 'Could not create the Experience Location Time Range Limits. Contact the system admin';
                    return $resetTimeRangeLimtsSaved;
                }
            }

            DB::commit();
            return ['status' => 'success'];
        }else{
            DB::rollBack();
            return [
                'status' => 'failure',
                'action' => 'Create the restaurant based with the assigned params',
                'message' => 'Could not create the Restaurant. Contact the system admin'
            ];
        }
    }

    public function update($productVendorLocationId, $data)
    {
        DB::beginTransaction();

        $query = '
            DELETE pvlbs, pvlbls, pvlbtrl
            FROM product_vendor_locations AS `pvl`
            LEFT JOIN product_vendor_location_booking_schedules AS `pvlbs` ON pvlbs.`product_vendor_location_id` = pvl.`id`
            LEFT JOIN product_vendor_location_block_schedules AS `pvlbls` ON pvlbls.`product_vendor_location_id` = pvl.`id`
            LEFT JOIN product_vendor_location_booking_time_range_limits AS `pvlbtrl` ON pvlbtrl.`product_vendor_location_id` = pvl.`id`
            LEFT JOIN product_vendor_locations_limits AS `pvll` ON pvll.`product_vendor_location_id` = pvl.`id`
            WHERE pvl.id = ?
        ';

        DB::delete($query, [$productVendorLocationId]);

        $productVendorLocationUpdateData = [
            'vendor_location_id' => $data['restaurant_location_id'],
            'slug' => $data['slug'],
            'location_id' => $data['location_id'],
            'status' => $data['status']
        ];

        $productVendorLocationId = DB::table('product_vendor_locations')->where('id', $productVendorLocationId)->update($productVendorLocationUpdateData);

        if(!empty($data['limits'])){
            $AttributesSaved = $this->saveReservationLimits($productVendorLocationId, $data['attributes']);

            if($AttributesSaved['status'] !== 'success'){
                $AttributesSaved['message'] = 'Could not create the Experience Location Reservation Limits. Contact the system admin';
                return $AttributesSaved;
            }
        }

        if(!empty($data['schedules'])){
            $schedulesSaved = $this->saveSchedules($productVendorLocationId, $data['schedules']);

            if($schedulesSaved['status'] !== 'success'){
                $schedulesSaved['message'] = 'Could not create the Experience Location Schedules. Contact the system admin';
                return $schedulesSaved;
            }
        }

        if(!empty($data['block_dates'])){
            $blockSchedulesSaved = $this->saveBlockDates($productVendorLocationId, $data['block_dates']);

            if($blockSchedulesSaved['status'] !== 'success'){
                $blockSchedulesSaved['message'] = 'Could not create the Experience Location Block Schedules. Contact the system admin';
                return $blockSchedulesSaved;
            }
        }

        if(!empty($data['reset_time_range_limits'])){
            $resetTimeRangeLimtsSaved = $this->saveTimeRangeLimits($productVendorLocationId, $data['reset_time_range_limits']);

            if($resetTimeRangeLimtsSaved['status'] !== 'success'){
                $resetTimeRangeLimtsSaved['message'] = 'Could not create the Experience Location Time Range Limits. Contact the system admin';
                return $resetTimeRangeLimtsSaved;
            }
        }

        DB::commit();
        return ['status' => 'success'];

    }

    public function delete($productVendorLocationId)
    {
        if(DB::table('product_vendor_locations')->where('id', $productVendorLocationId)->count()){
            if(DB::table('product_vendor_locations')->delete($productVendorLocationId)){
                return ['status' => 'success'];
            }else{
                return [
                    'status' => 'failure',
                    'action' => 'Delete the Experience Location using the id',
                    'message' => 'There was a problem while deleting the Experience Location. Please check if the restaurant still exists or contact the system admin'
                ];
            }
        }else{
            return [
                'status' => 'failure',
                'action' => 'Check if Experience Location exists based on the id',
                'message' => 'Could not find the Experiecne Location you are trying to delete. Try again or contact the sys admin'
            ];
        }
    }

    public function fetch()
    {

    }

    protected function saveSchedules($product_vendor_location_id, $schedules)
    {
        $schedules_insert_map = [];

        foreach($schedules as $schedule){
            $schedules_insert_map[] = [
                'product_vendor_location_id' => $product_vendor_location_id,
                'schedule_id' => $schedule['id'],
                //'off_peak_schedule' => $schedule['off_peak'],
                'max_reservations' => $schedule['max_reservations']
            ];
        }

        if(DB::table('product_vendor_location_booking_schedules')->insert($schedules_insert_map)){
            return ['status' => 'success'];
        }else{
            DB::rollback();
            return [
                'status' => 'failure',
                'action' => 'Inserting the Experience Location schedule into the DB'
            ];
        }
    }

    protected function saveBlockDates($product_vendor_location_id, $block_dates)
    {
        $block_dates_insert_map = [];

        foreach($block_dates as $date){
            if(strtotime($date) > strtotime('midnight')){
                $block_dates_insert_map[] = [
                    'product_vendor_location_id' => $product_vendor_location_id,
                    'block_date' => $date
                ];
            }
        }

        if(count($block_dates_insert_map)){
            if(DB::table('product_vendor_location_block_schedules')->insert($block_dates_insert_map)){
                return ['status' => 'success'];
            }else{
                DB::rollback();
                return [
                    'status' => 'failure',
                    'action' => 'Inserting the Experience Location Block Schedules into the DB'
                ];
            }
        }else{
            return ['status' => 'success'];
        }
    }

    protected function saveTimeRangeLimits($product_vendor_location_id, $time_range_limits)
    {
        $time_range_limit_insert_map = [];

        foreach($time_range_limits as $time_range_limit){
            if($time_range_limit['limit_by'] === 'Date'){
                if(strtotime($time_range_limit['date']) > strtotime('midnight')){
                    $time_range_limit_insert_map[] = [
                        'product_vendor_location_id' => $product_vendor_location_id,
                        'start_time' => $time_range_limit['from_time'],
                        'end_time' => $time_range_limit['to_time'],
                        //'max_reservations_limit' => $time_range_limit['max_reservations_limit'],
                        'max_covers_limit' => $time_range_limit['max_covers_limit'],
                        'date' => $time_range_limit['date'],
                        'day' => null
                    ];
                }
            }else{
                $time_range_limit_insert_map[] = [
                    'product_vendor_location_id' => $product_vendor_location_id,
                    'start_time' => $time_range_limit['from_time'],
                    'end_time' => $time_range_limit['to_time'],
                    //'max_reservations_limit' => $time_range_limit['max_reservations_limit'],
                    'max_covers_limit' => $time_range_limit['max_covers_limit'],
                    'date' => null,
                    'day' => $time_range_limit['day']
                ];
            }
        }

        if(count($time_range_limit_insert_map)){
            if(DB::table('product_vendor_location_booking_time_range_limits_old')->insert($time_range_limit_insert_map)){
                return ['status' => 'success'];
            }else{
                DB::rollback();
                return [
                    'status' => 'failure',
                    'action' => 'Inserting the Experience Location Time Range Limits into the DB'
                ];
            }
        }else{
            return ['status' => 'success'];
        }
    }

    protected function saveReservationLimits($product_vendor_location_id, $attributes)
    {   //dd($attributes);
        $limitData = [];

        if(isset($attributes['min_people_per_reservation'])){
            $limitData['min_people_per_reservation'] = $attributes['min_people_per_reservation'];
        }

        if(isset($attributes['max_people_per_reservation'])){
            $limitData['max_people_per_reservation'] = $attributes['max_people_per_reservation'];
        }

        if(isset($attributes['max_reservations_per_time_slot'])){
            $limitData['max_reservations_per_time_slot'] = $attributes['max_reservations_per_time_slot'];
        }

        if(isset($attributes['min_people_increments_per_reservation'])){
            $limitData['min_people_increments'] = $attributes['min_people_increments_per_reservation'];
        }

        if(isset($attributes['max_people_per_day'])){
            $limitData['max_people_per_day'] = $attributes['max_people_per_day'];
        }

        if(isset($attributes['minimum_reservation_time_buffer'])){
            $limitData['minimum_reservation_time_buffer'] = $attributes['minimum_reservation_time_buffer'];
        }

        if(isset($attributes['maximum_reservation_time_buffer'])){
            $limitData['maximum_reservation_time_buffer'] = $attributes['maximum_reservation_time_buffer'];
        }

        if(isset($attributes['max_reservations_per_day'])){
            $limitData['max_reservations_per_day'] = $attributes['max_reservations_per_day'];
        }


        if(count($limitData)){
            $limitData['product_vendor_location_id'] = $product_vendor_location_id;

            if(DB::table('product_vendor_locations_limits')->insert($limitData)){
                return ['status' => 'success'];
            }else{
                DB::rollback();
                return [
                    'status' => 'failure',
                    'action' => 'Inserting the Experience Location Reservations to the DB'
                ];
            }
        }else{
            return ['status' => 'success'];
        }
    }
} 