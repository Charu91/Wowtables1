<?php namespace WowTables\Core\Repositories\Restaurants;

use DB;
use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocation;

class RestaurantLocationsRepository {


    protected $attributes = [];

    protected $schedules = [];

    public function getAll()
    {
        return VendorLocation::wherehas('vendor.vendorType', function($q)
        {
            $q->where('type','Restaurants');
        })->get();
    }

    public function getByRestaurantLocationId($id)
    {
        $vendorLocationWithAttributes = VendorLocation::with(
            'attributesBoolean',
            'attributesDate',
            'attributesInteger',
            'attributesFloat',
            'attributesText',
            'attributesVarChar',
            'attributesSingleSelect',
            'attributesMultiSelect',
            'schedules'
        )->findOrFail($id)->wherehas('vendor.vendorType',function($q) {
            $q->where('type','Restaurants');
        })->first();
        //dd(array_flatten($vendorLocationWithAttributes->schedules->toArray()));
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesBoolean);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesInteger);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesFloat);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesDate);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesText);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesVarChar);
        $this->populateVendorAttributes($vendorLocationWithAttributes->attributesSingleSelect);
        $this->populateVendorMultiSelectAttributes($vendorLocationWithAttributes->attributesMultiSelect);
        $this->populateVendorSchedules($id);

        return [ 'RestaurantLocation' => VendorLocation::find($id),'schedules'=>$this->schedules, 'attributes' => $this->attributes];
    }

    public function populateVendorAttributes ( $vendorLocationAttributes )
    {
        foreach($vendorLocationAttributes as $attribute)
        {
            $name  = $attribute->attribute->alias;
            $value = $attribute->attribute_value;

            $this->attributes[$name] = $value;
        }

    }

    public function populateVendorMultiSelectAttributes ( $vendorLocationMultiSelectAttributes )
    {
        foreach($vendorLocationMultiSelectAttributes as $attribute)
        {
            $name  = $attribute->attribute->attribute->alias;
            $value = $attribute->vendor_attributes_select_option_id;

            $this->attributes[$name][] = $value;
        }
    }

    public function populateVendorSchedules ( $id )
    {
        $schedules = '
                    SELECT
                        s.`id` as `schedule_id`,
                        s.`day`,
                        s.`day_short`,
                        s.`day_numeric`,
                        TIME_FORMAT(ts.`time`, "%l:%i:%p") AS `time_key`,
                        TIME_FORMAT(ts.`time`, "%l:%i %p") AS `time`,
                        ts.`slot_type`,
                        vlbs.`vendor_location_id`,
                        vlbs.`off_peak_schedule`
                    FROM schedules AS `s`
                    INNER JOIN time_slots AS `ts` ON s.`time_slot_id` = ts.`id`
                    Inner JOIN vendor_location_booking_schedules as `vlbs`ON vlbs.`schedule_id` = s.`id`
                    WHERE vlbs.`vendor_location_id` = ?
                    ORDER BY ts.`id`, ts.`slot_type`, ts.`time` ASC
        ';

        $scheduleResults = DB::select($schedules,[$id]);


        foreach($scheduleResults as $schedule)
            {

            if(!isset($this->schedules[$schedule->time_key]))
                $this->schedules[$schedule->time_key] = [];

            if(!isset($this->schedules[$schedule->time_key]['time']))
                $this->schedules[$schedule->time_key]['time'] = $schedule->time;

            if(!isset($this->schedules[$schedule->time_key]['slot_type']))
                $this->schedules[$schedule->time_key]['slot_type'] = $schedule->slot_type;

            if(!isset($this->schedules[$schedule->time_key][$schedule->day_short]))
                $this->schedules[$schedule->time_key][$schedule->day_short] = [];

            $this->schedules[$schedule->time_key][$schedule->day_short]['schedule_id'] = $schedule->schedule_id;
            $this->schedules[$schedule->time_key][$schedule->day_short]['day'] = $schedule->day;
            $this->schedules[$schedule->time_key][$schedule->day_short]['day_numeric'] = $schedule->day_numeric;

        }

    }

    public function add(VendorLocation $vendorLocation)
    {
        $vendorLocation->save();
    }

}
