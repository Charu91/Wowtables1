<?php namespace WowTables\Http\Models\Eloquent\Vendors;

use Illuminate\Database\Eloquent\Model;

class VendorType extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vendor_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type','slug','description'];

    protected $hidden = ['id','created_at','updated_at'];

    public function vendors()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Vendors\Vendor', 'id', 'vendor_type_id');
    }
}
