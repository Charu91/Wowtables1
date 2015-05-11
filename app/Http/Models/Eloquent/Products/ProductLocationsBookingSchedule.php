<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;


class ProductLocationsBookingSchedule extends Model {

    protected $table = 'product_vendor_location_booking_schedules';

    protected $fillable = ['schedule_id'];

    protected $hidden = ['id','product_vendor_location_id','created_at','updated_at'];

    protected $with = ['schedule'];

    public function schedule()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Schedule','schedule_id','id');
    }

}
