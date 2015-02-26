<?php namespace WowTables\Http\Models\Eloquent\Vendors\Locations;

use Illuminate\Database\Eloquent\Model;


class VendorLocationAttributesSingleSelect extends Model {

    protected $table = 'vendor_location_attributes_singleselect';

    protected $fillable = [];

    protected $with = ['attribute','selectOptions'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorAttributes', 'vendor_attribute_id', 'id');
    }

    public function selectOptions()
    {
        return $this->hasOne('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesSelectOptions', 'id', 'vendor_locations_attributes_select_option_id');
    }
}
