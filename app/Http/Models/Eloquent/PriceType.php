<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class PriceType extends Model {

    protected $table = 'price_types';

    protected $fillable = ['type_name'];


}