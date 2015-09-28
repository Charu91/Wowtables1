<?php namespace WowTables\Http\Models\Eloquent\Reservations;

use Illuminate\Database\Eloquent\Model;

class ReservStatusLog extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reservation_status_log';

    /*public function statusValue()
    {
        return $this->hasOne('WowTables\Http\Models\Eloquent\Reservations\ReservationStatus','id','new_reservation_status_id');
    }*/

}
