<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Flag extends Model {

    protected $table = 'flags';

    protected $fillable = ['name','color'];


}