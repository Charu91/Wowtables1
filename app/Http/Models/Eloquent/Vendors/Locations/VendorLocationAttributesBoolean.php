<?php namespace WowTables\Http\Models\Eloquent\Vendors\Locations;

use Illuminate\Database\Eloquent\Model;


class VendorLocationAttributesBoolean extends Model {

    protected $table = 'vendor_location_attributes_boolean';

    protected $fillable = ['attribute_value'];

    protected $with = ['attribute'];

    protected $visible = ['attribute_value'];

    protected $casts = [
        'attribute_value' => 'boolean'
    ];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorAttributes', 'vendor_attribute_id', 'id');
    }


}