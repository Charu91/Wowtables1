<?php namespace WowTables\Http\Models\Eloquent\Vendors\Locations;

use Illuminate\Database\Eloquent\Model;


class VendorLocationAttributesSingleSelect extends Model {

    protected $table = 'vendor_location_attributes_singleselect';

    protected $fillable = [];

    protected $with = ['attribute'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorAttributes', 'vendor_attribute_id', 'id');
    }

}
