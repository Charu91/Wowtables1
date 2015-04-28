<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;


class ProductAttributesInteger extends Model {

    protected $table = 'product_attributes_integer';

    protected $fillable = ['attribute_value'];

    protected $with = ['attribute'];

    protected $visible = ['attribute_value'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Products\ProductAttributes', 'product_attribute_id', 'id');
    }


}
