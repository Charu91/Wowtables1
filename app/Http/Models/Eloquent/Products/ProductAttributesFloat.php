<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;


class ProductAttributesFloat extends Model {

    protected $table = 'product_attributes_float';

    protected $fillable = ['attribute_value'];

    protected $with = ['attribute'];

    protected $visible = ['attribute_value'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Products\ProductAttributes', 'product_attribute_id', 'id');
    }


}