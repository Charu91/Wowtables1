<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schedules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $gaurded = [];

    protected $hidden = ['id','created_at','updated_at'];

    protected $with = ['time_slot'];

    public function time_slot()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\TimeSlot','time_slot_id','id');
    }

}
