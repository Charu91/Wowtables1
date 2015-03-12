<?php namespace WowTables\Http\Models\Eloquent\Vendors;

use Illuminate\Database\Eloquent\Model;


class VendorAttributesSelectOptions extends Model {

    protected $table = 'vendor_attributes_select_options';

    protected $fillable = ['option'];


    public function cuisines()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorAttributes', 'vendor_type_id', 'id');
    }
    protected $with = ['attribute'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorAttributes', 'vendor_attribute_id', 'id');
    }
}