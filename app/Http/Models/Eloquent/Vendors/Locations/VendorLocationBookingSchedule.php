<?php namespace WowTables\Http\Models\Eloquent\Vendors\Locations;

use Illuminate\Database\Eloquent\Model;


class VendorLocationBookingSchedule extends Model {

    protected $table = 'vendor_location_booking_schedules';

    protected $fillable = ['schedule_id','off_peak_schedule'];

    protected $hidden = ['id','vendor_location_id','created_at','updated_at'];

    protected $with = ['schedule'];

    public function schedule()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Schedule','schedule_id','id');
    }

}
