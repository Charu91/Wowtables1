<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class UserAttributes extends Model {

	protected $table = 'user_attributes';

    protected $fillable = ['name','alias','type'];


}
