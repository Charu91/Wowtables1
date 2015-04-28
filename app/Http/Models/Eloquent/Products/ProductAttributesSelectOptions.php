<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;


class ProductAttributesSelectOptions extends Model {

    protected $table = 'product_attributes_select_options';

    protected $fillable = ['option'];


    public function cuisines()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Products\ProductAttributes', 'product_type_id', 'id');
    }
    protected $with = ['attribute'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Products\ProductAttributes', 'product_attribute_id', 'id');
    }
}