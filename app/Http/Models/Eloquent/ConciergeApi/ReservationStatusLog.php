<?php namespace WowTables\Http\Models\Eloquent\ConciergeApi;

use Illuminate\Database\Eloquent\Model;

class ReservationStatusLog extends Model {

    protected $table = 'reservation_status_log';
    protected $fillable = [
        'reservation_id',
        'user_id',
        'old_reservation_status_id',
        'new_reservation_status_id'
    ];

}
