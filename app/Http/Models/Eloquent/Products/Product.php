<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','slug','status','type'];

    public function add($vendor_id, $name, $slug, $status, $type)
    {
        $experience = new static(compact('vendor_id', 'name','slug','status','type'));

        return $experience;
    }

}
