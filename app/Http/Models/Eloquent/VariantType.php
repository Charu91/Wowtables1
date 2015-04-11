<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class VariantType extends Model {

    protected $table = 'product_variant_options';

    protected $fillable = ['variation_name','variant_alias'];


}