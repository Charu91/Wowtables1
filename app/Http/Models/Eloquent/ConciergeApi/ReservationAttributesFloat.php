<?php namespace WowTables\Http\Models\Eloquent\ConciergeApi;

use Illuminate\Database\Eloquent\Model;

class ReservationAttributesFloat extends Model {

    protected $table = 'reservation_attributes_float';
    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributes', 'reservation_attribute_id', 'id');
    }

    public function scopeReservationIdIn($query,$reservationIdArr){
        $query->whereIn('reservation_id',$reservationIdArr);
    }

}
