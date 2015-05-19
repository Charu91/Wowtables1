<?php namespace WowTables\Http\Models\Frontend;

use Illuminate\Database\Eloquent\Model;

class CodeModel extends Model {
	protected $table = 'codes';
    protected $fillable = [];

    protected $hidden = [];

    function getAll(){
    	$query = Self::all();   
        return $query->toArray();
    }
}