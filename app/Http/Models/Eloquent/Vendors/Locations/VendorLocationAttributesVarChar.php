<?php namespace WowTables\Http\Models\Eloquent\Vendors\Locations;

use Illuminate\Database\Eloquent\Model;


class VendorLocationAttributesVarChar extends Model {

    protected $table = 'vendor_location_attributes_varchar';

    protected $fillable = ['attribute_value'];

    protected $with = ['attribute'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorAttributes', 'vendor_attribute_id', 'id');
    }


}