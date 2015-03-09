<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $visible = ['id','name'];

}
