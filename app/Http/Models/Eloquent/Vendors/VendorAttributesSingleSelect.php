<?php namespace WowTables\Http\Models\Eloquent\Vendors;

use Illuminate\Database\Eloquent\Model;


class VendorAttributesSingleSelect extends Model {

    protected $table = 'vendor_attributes_singleselect';

    protected $fillable = [];

    protected $with = ['attribute','selectOptions'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorAttributes', 'vendor_attribute_id', 'id');
    }

    public function selectOptions()
    {
        return $this->hasOne('WowTables\Http\Models\Eloquent\Vendors\VendorAttributesSelectOptions', 'id', 'vendor_attribute_select_option_id');
    }
}
