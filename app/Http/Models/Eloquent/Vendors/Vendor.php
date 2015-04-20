<?php namespace WowTables\Http\Models\Eloquent\Vendors;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vendors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','slug','status'];

    protected $hidden = ['vendor_type_id'];

    protected $with = ['vendorType'];

    public function vendorType()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Vendors\VendorType', 'vendor_type_id', 'id');
    }

    public function attributesBoolean()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\VendorAttributesBoolean', 'vendor_id', 'id');
    }

    public function attributesDate()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\VendorAttributesDate', 'vendor_id', 'id');
    }

    public function attributesInteger()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\VendorAttributesInteger', 'vendor_id', 'id');
    }

    public function attributesFloat()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\VendorAttributesFloat', 'vendor_id', 'id');
    }

    public function attributesText()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\VendorAttributesText', 'vendor_id', 'id');
    }

    public function attributesVarChar()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\VendorAttributesVarChar', 'vendor_id', 'id');
    }

    public function attributesSingleSelect()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\VendorAttributesSingleSelect', 'vendor_id', 'id');
    }

    public function attributesMultiSelect()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\VendorAttributesMultiSelect', 'vendor_id', 'id');
    }
}
