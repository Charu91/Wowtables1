<?php namespace WowTables\Http\Models\Eloquent\ConciergeApi;

use Illuminate\Database\Eloquent\Model;

class ReservationDetail extends Model {

    public function user()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\ConciergeApi\User', 'user_id', 'id');
    }

    public function scopeLocationIn($query,$locationArr,$reservationId){
        $locationIdArr = array();
        if(sizeof($locationArr)) {
            foreach ($locationArr as $location) {
                array_push($locationIdArr,$location->vendor_location_id);
            }
        }
        $query->whereIn('vendor_location_id',$locationIdArr)->where('id',$reservationId);

    }

}
