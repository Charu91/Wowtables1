<?php namespace WowTables\Http\Models\Eloquent\ConciergeApi;

use Illuminate\Database\Eloquent\Model;

class ReservationAttributesBooleanLog extends Model {

    protected $table = 'reservation_attributes_boolean_log';
    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributes', 'reservation_attribute_id', 'id');
    }

}
