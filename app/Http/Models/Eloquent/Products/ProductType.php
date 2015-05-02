<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type','slug','description'];

    protected $hidden = ['id','created_at','updated_at'];

    public function products()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\Products\Product', 'id', 'product_type_id');
    }
}
