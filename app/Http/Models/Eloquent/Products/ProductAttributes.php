<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model {

    protected $table = 'product_attributes';

    protected $fillable = ['name','alias','type'];

    protected $visible = ['alias'];


}