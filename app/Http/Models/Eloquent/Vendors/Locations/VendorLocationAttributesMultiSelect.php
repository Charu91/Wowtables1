<?php namespace WowTables\Http\Models\Eloquent\Vendors\Locations;

use Illuminate\Database\Eloquent\Model;


class VendorLocationAttributesMultiSelect extends Model {

    protected $table = 'vendor_location_attributes_multiselect';

    protected $fillable = [];

    protected $with = ['attribute'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorAttributesSelectOptions','vendor_attributes_select_option_id');
    }

}