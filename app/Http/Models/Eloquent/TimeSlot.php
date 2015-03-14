<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'time_slots';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $gaurded = [];

    protected $hidden = ['id','created_at','updated_at'];

}
