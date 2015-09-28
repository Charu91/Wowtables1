<?php namespace WowTables;

use Illuminate\Database\Eloquent\Model;

class VendorLocationAddress extends Model {

	//
    protected $table = 'vendor_location_address';

    public function city_name()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Location','city_id','id');
    }
}
