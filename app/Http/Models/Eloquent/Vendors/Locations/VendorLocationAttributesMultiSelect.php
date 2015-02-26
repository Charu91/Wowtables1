<?php namespace WowTables\Http\Models\Eloquent\Vendors\Locations;

use Illuminate\Database\Eloquent\Model;


class VendorLocationAttributesMultiSelect extends Model {

    protected $table = 'vendor_location_attributes_multiselect';

    protected $fillable = [];

    protected $with = ['attribute','selectOptions'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorAttributes', 'vendor_attribute_id', 'id');
    }

    public function selectOptions()
    {
        return $this->hasOne('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesSelectOptions', 'id', 'vendor_location_attributes_select_option_id');
    }

}