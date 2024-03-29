<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;


class ProductAttributesMultiSelect extends Model {

    protected $table = 'product_attributes_multiselect';

    protected $fillable = [];

    protected $with = ['attribute'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Products\ProductAttributesSelectOptions','product_attributes_select_option_id');
    }

}