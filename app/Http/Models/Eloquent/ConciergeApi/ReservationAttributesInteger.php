<?php namespace WowTables\Http\Models\Eloquent\ConciergeApi;

use Illuminate\Database\Eloquent\Model;
use WowTables\Http\Controllers\ConciergeApi\ReservationController;

class ReservationAttributesInteger extends Model {

    protected $table = 'reservation_attributes_integer';

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributes', 'reservation_attribute_id', 'id');
    }

    public function scopeStatusIn($query,$statuses){
        $statusStrArr = explode('~',$statuses);
        $statusIntArr = [];
        foreach($statusStrArr as $statusStr){
            array_push($statusIntArr,(int)$statusStr);
        }
        $query->whereIn('attribute_value',$statusIntArr)->where('reservation_attribute_id',ReservationController::$status_attr_id);
    }

    public function scopeReservationIdIn($query,$reservationIdArr){
        $query->whereIn('reservation_id',$reservationIdArr);
    }

}
