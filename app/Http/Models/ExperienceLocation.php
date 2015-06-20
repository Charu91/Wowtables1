<?php namespace WowTables\Http\Models;

use DB;

class ExperienceLocation {

    public function create($data)
    {
        DB::beginTransaction();
        //$location_count = count($data['restaurant_location_id']);

        $productVendorLocationLastID = '';
        /*if($location_count > 1){



        }else if($location_count == 1){
            $productVendorLocationInsertData = [
                'product_id' => $data['experience_id'],
                'vendor_location_id' => $data['restaurant_location_id'][0],
                'location_parent_id' => 0,
                'descriptive_title' => $data['descriptive_title'],
                'show_status' => (isset($data['show_status']) && $data['show_status'] != "" ? $data['show_status'] : 'show_in_all'),
                'status' => $data['status']
            ];

            $productVendorLocationLastID = DB::table('product_vendor_locations')->insertGetId($productVendorLocationInsertData);
        }*/


        //$productVendorLocationId = $productVendorLocationLastID;

        //echo "last id == ".$productVendorLocationId; die;
        //echo "<prE>"; print_r($data); die;

        foreach($data['restaurant_location_id'] as $key => $location_id) {
            $productVendorLocationInsertData = [
                'product_id' => $data['experience_id'],
                'vendor_location_id' => $location_id,
                'location_parent_id' => ($productVendorLocationLastID ? $productVendorLocationLastID : 0),
                'descriptive_title' => $data['descriptive_title'],
                'show_status' => (isset($data['show_status']) && $data['show_status'] != "" ? $data['show_status'] : 'show_in_all'),
                'status' => $data['status']
            ];

            echo "location_id - ".$location_id." , ";

            /*$productVendorLocationId = DB::table('product_vendor_locations')->insertGetId($productVendorLocationInsertData);


            if ($productVendorLocationId) {
                if (!empty($data['attributes'])) {
                    $AttributesSaved = $this->saveReservationLimits($productVendorLocationId, $data['attributes']);

                    if ($AttributesSaved['status'] !== 'success') {
                        $AttributesSaved['message'] = 'Could not create the Experience Location Reservation Limits. Contact the system admin';
                        return $AttributesSaved;
                    }
                }

                if (!empty($data['schedules'])) {
                    $schedulesSaved = $this->saveSchedules($productVendorLocationId, $data['schedules']);

                    if ($schedulesSaved['status'] !== 'success') {
                        $schedulesSaved['message'] = 'Could not create the Experience Location Schedules. Contact the system admin';
                        return $schedulesSaved;
                    }
                }

                if (!empty($data['block_dates'])) {
                    $blockSchedulesSaved = $this->saveBlockDates($productVendorLocationId, $data['block_dates']);

                    if ($blockSchedulesSaved['status'] !== 'success') {
                        $blockSchedulesSaved['message'] = 'Could not create the Experience Location Block Schedules. Contact the system admin';
                        return $blockSchedulesSaved;
                    }
                }

                if (!empty($data['reset_time_range_limits'])) {
                    $resetTimeRangeLimtsSaved = $this->saveTimeRangeLimits($productVendorLocationId, $data['reset_time_range_limits']);

                    if ($resetTimeRangeLimtsSaved['status'] !== 'success') {
                        $resetTimeRangeLimtsSaved['message'] = 'Could not create the Experience Location Time Range Limits. Contact the system admin';
                        return $resetTimeRangeLimtsSaved;
                    }
                }

                DB::commit();
                return ['status' => 'success'];
            } else {
                DB::rollBack();
                return [
                    'status' => 'failure',
                    'action' => 'Create the restaurant based with the assigned params',
                    'message' => 'Could not create the Restaurant. Contact the system admin'
                ];
            }*/
        }
    }

    public function update($productVendorLocationId, $data)
    {
        DB::beginTransaction();

        $q1 = 'SELECT product_id from product_vendor_locations WHERE id = ?';

        $productID = DB::select($q1,[$productVendorLocationId]);

        //echo "<pre>"; print_r($productID); die;

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

        $q1 = 'SELECT product_id from product_vendor_locations WHERE id = ?';

        $productID = DB::select($q1,[$productVendorLocationId]);

        //echo "<pre>"; print_r($productID); die;


        $deleteFromProductID = 'DELETE FROM product_vendor_locations WHERE product_id = ?';

        DB::delete($deleteFromProductID, [$productID[0]->product_id]);


        $location_count = count($data['restaurant_location_id']);



        $productVendorLocationLastID = '';
        if($location_count > 1){
            foreach($data['restaurant_location_id'] as $key => $location_id){
                $productVendorLocationInsertData = [
                    'product_id' => $data['experience_id'],
                    'vendor_location_id' => $location_id,
                    'location_parent_id' => ($productVendorLocationLastID ? $productVendorLocationLastID : 0),
                    'descriptive_title' => $data['descriptive_title'],
                    'show_status' => (isset($data['show_status']) && $data['show_status'] != "" ? $data['show_status'] : 'show_in_all'),
                    'status' => $data['status']
                ];

                $productVendorLocationLastID = DB::table('product_vendor_locations')->insertGetId($productVendorLocationInsertData);
            }


        }else if($location_count == 1){
            $productVendorLocationInsertData = [
                'product_id' => $data['experience_id'],
                'vendor_location_id' => $data['restaurant_location_id'][0],
                'location_parent_id' => 0,
                'descriptive_title' => $data['descriptive_title'],
                'show_status' => (isset($data['show_status']) && $data['show_status'] != "" ? $data['show_status'] : 'show_in_all'),
                'status' => $data['status']
            ];

            $productVendorLocationLastID = DB::table('product_vendor_locations')->insertGetId($productVendorLocationInsertData);
        }


        $productVendorLocationId = $productVendorLocationLastID;
        //echo "productVendorLocationId = ".$productVendorLocationId; die;
        /*$productVendorLocationUpdateData = [
            'vendor_location_id' => $data['restaurant_location_id'],
            'descriptive_title' => $data['descriptive_title'],
            'location_id' => $data['location_id'],
            'status' => $data['status']
        ];

        $productVendorLocationId = DB::table('product_vendor_locations')->where('id', $productVendorLocationId)->update($productVendorLocationUpdateData);*/

        if(!empty($data['limits'])){
            $AttributesSaved = $this->saveReservationLimits($productVendorLocationId, $data['limits']);

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

    public function getExperienceLocationDetails(){

        $experiencesLocationDetails = '
                    SELECT pvl.id,pvl.status,(SELECT MAX(pvl2.id) from product_vendor_locations as pvl2 WHERE pvl2.product_id = pvl.product_id) AS product_vendor_last_id,p.name as product_name,vl.slug,v.name as vendor_name
                    FROM product_vendor_locations as pvl
                    LEFT JOIN products as p on pvl.product_id = p.id
                    LEFT JOIN vendor_locations as vl on pvl.vendor_location_id = vl.id
                    LEFT JOIN vendors as v on vl.vendor_id = v.id
        ';

        $experienceLocationResults = DB::select($experiencesLocationDetails);
        return $experienceLocationResults;
    }

    protected function saveSchedules($product_vendor_location_id, $schedules)
    {
        $schedules_insert_map = [];
        foreach($schedules as $schedule){
            if(isset($schedule['id']) && ($schedule['id'] != "" || $schedule['id'] != 0)){
                $schedules_insert_map[] = [
                    'product_vendor_location_id' => $product_vendor_location_id,
                    'schedule_id' => $schedule['id'],
                    //'off_peak_schedule' => $schedule['off_peak'],
                    //'max_reservations' => $schedule['max_reservations']
                ];
            }

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
                        'limit_by' => $time_range_limit['limit_by'],
                        'start_time' => $time_range_limit['from_time'],
                        'end_time' => $time_range_limit['to_time'],
                        //'max_reservations_limit' => $time_range_limit['max_reservations_limit'],
                        'max_covers_limit' => ($time_range_limit['max_covers_limit'] ? $time_range_limit['max_covers_limit'] : 0),
                        'max_tables_limit' => ($time_range_limit['max_tables_limit'] ? $time_range_limit['max_tables_limit'] : 0),
                        'date' => $time_range_limit['date'],
                        'day' => null
                    ];
                }
            }else{
                $time_range_limit_insert_map[] = [
                    'product_vendor_location_id' => $product_vendor_location_id,
                    'limit_by' => $time_range_limit['limit_by'],
                    'start_time' => $time_range_limit['from_time'],
                    'end_time' => $time_range_limit['to_time'],
                    //'max_reservations_limit' => $time_range_limit['max_reservations_limit'],
                    'max_covers_limit' => ($time_range_limit['max_covers_limit'] ? $time_range_limit['max_covers_limit'] : 0),
                    'max_tables_limit' => ($time_range_limit['max_tables_limit'] ? $time_range_limit['max_tables_limit'] : 0),
                    'date' => null,
                    'day' => $time_range_limit['day']
                ];
            }
        }

        if(count($time_range_limit_insert_map)){
            if(DB::table('product_vendor_location_booking_time_range_limits')->insert($time_range_limit_insert_map)){
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

    protected function saveReservationLimits($product_vendor_location_id, $limits)
    {   //dd($attributes);
        $limitData = [];

        if(isset($limits['min_people_per_reservation'])){
            $limitData['min_people_per_reservation'] = $limits['min_people_per_reservation'];
        }

        if(isset($limits['max_people_per_reservation'])){
            $limitData['max_people_per_reservation'] = $limits['max_people_per_reservation'];
        }

        if(isset($limits['max_reservations_per_time_slot'])){
            $limitData['max_reservations_per_time_slot'] = $limits['max_reservations_per_time_slot'];
        }

        if(isset($limits['min_people_increments_per_reservation'])){
            $limitData['min_people_increments'] = $limits['min_people_increments_per_reservation'];
        }

        if(isset($limits['max_people_per_day'])){
            $limitData['max_people_per_day'] = $limits['max_people_per_day'];
        }

        if(isset($limits['minimum_reservation_time_buffer'])){
            $limitData['minimum_reservation_time_buffer'] = $limits['minimum_reservation_time_buffer'];
        }

        if(isset($limits['maximum_reservation_time_buffer'])){
            $limitData['maximum_reservation_time_buffer'] = $limits['maximum_reservation_time_buffer'];
        }

        if(isset($limits['max_reservations_per_day'])){
            $limitData['max_reservations_per_day'] = $limits['max_reservations_per_day'];
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

    public function getProductLocationsDetails(){
        $experiencesLocationDetails = '
                    SELECT pvl.id,pvl.status,(SELECT MAX(pvl2.id) from product_vendor_locations as pvl2 WHERE pvl2.product_id = pvl.product_id) AS product_vendor_last_id,p.name as product_name,vl.slug,v.name as vendor_name
                    FROM product_vendor_locations as pvl
                    LEFT JOIN products as p on pvl.product_id = p.id
                    LEFT JOIN vendor_locations as vl on pvl.vendor_location_id = vl.id
                    LEFT JOIN vendors as v on vl.vendor_id = v.id
        ';

        $experienceLocationResults = DB::select($experiencesLocationDetails);
        return $experienceLocationResults;
    }

    public function getLocationsFromProduct($id){
        $experienceLocations = 'SELECT T2.vendor_location_id,T2.product_id,T2.descriptive_title,T2.status,T2.show_status FROM (
                                    SELECT
                                        @r AS _id,
                                        (
                                          SELECT @r := location_parent_id
                                          FROM product_vendor_locations
                                          WHERE id = _id
                                        ) AS parent_id1,
                                      @l := @l + 1 AS lvl
                                      FROM
                                        (SELECT @r := ?, @l := 0) vars,
                                        product_vendor_locations m
                                    WHERE @r <> 0) T1
                                JOIN product_vendor_locations T2
                                ON T1._id = T2.id
                                ORDER BY T1.lvl DESC;';

        $experienceLocationResults = DB::select($experienceLocations,[$id]);

        return $experienceLocationResults;
    }

    public function populateProductLocationLimits($id){
        $vendor_location_details = DB::table('product_vendor_locations_limits')->where('product_vendor_location_id', $id);
        return $vendor_location_details->get();
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

    public function populateProductLocationBlockDates( $id )
    {

        $vendor_location_flags_details = DB::table('product_vendor_location_block_schedules')->where('product_vendor_location_id', $id);
        return $vendor_location_flags_details->get();

    }

    public function populateProductLocationBlockTimeLimits( $id )
    {

        $vendor_location_flags_details = DB::table('product_vendor_location_booking_time_range_limits')->where('product_vendor_location_id', $id);
        return $vendor_location_flags_details->get();

    }

} 