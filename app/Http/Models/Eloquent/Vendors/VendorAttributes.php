<?php namespace WowTables\Http\Models\Eloquent\Vendors;

use Illuminate\Database\Eloquent\Model;

class VendorAttributes extends Model {

    protected $table = 'vendor_attributes';

    protected $fillable = ['name','alias','type'];

    protected $visible = ['alias'];


}