<?php namespace WowTables\Http\Models\Eloquent\Vendors\Locations;

use Illuminate\Database\Eloquent\Model;


class VendorLocationAttributesInteger extends Model {

    protected $table = 'vendor_location_attributes_integer';

    protected $fillable = ['attribute_value'];

    protected $with = ['attribute'];

    protected $visible = ['attribute_value'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorAttributes', 'vendor_attribute_id', 'id');
    }


}
