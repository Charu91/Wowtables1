<?php namespace WowTables\Http\Models\Eloquent\ConciergeApi;

use Illuminate\Database\Eloquent\Model;

class ReservationAddonsVariantsDetail extends Model {
    protected $table = 'reservation_addons_variants_details';
    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\ConciergeApi\ReservationAttributes', 'reservation_id', 'id');
    }

}
