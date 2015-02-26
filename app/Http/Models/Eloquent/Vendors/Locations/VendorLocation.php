<?php namespace WowTables\Http\Models\Eloquent\Vendors\Locations;

use Illuminate\Database\Eloquent\Model;

class VendorLocation extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vendor_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug','status'];

    protected $hidden = ['vendor_id','location_id'];

    protected $with = ['vendor','location'];

    public function vendor()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\Vendor','vendor_id','id');
    }

    public function vendorType()
    {
        return $this->hasManyThrough('WowTables\Http\Models\Eloquent\Vendors\VendorType','WowTables\Http\Models\Eloquent\Vendors\Vendor','vendor_type_id','id');
    }

    public function location()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Location','location_id','id');
    }

    public function attributesBoolean()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesBoolean', 'vendor_location_id', 'id');
    }

    public function attributesDate()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesDate', 'vendor_location_id', 'id');
    }

    public function attributesInteger()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesInteger', 'vendor_location_id', 'id');
    }

    public function attributesFloat()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesFloat', 'vendor_location_id', 'id');
    }

    public function attributesText()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesText', 'vendor_location_id', 'id');
    }

    public function attributesVarChar()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesVarChar', 'vendor_location_id', 'id');
    }

    public function attributesSingleSelect()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesSingleSelect', 'vendor_location_id', 'id');
    }

    public function attributesMultiSelect()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesMultiSelect', 'vendor_location_id', 'id');
    }

}
