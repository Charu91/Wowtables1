<?php namespace WowTables\Http\Models\Eloquent\Reservations;

use DB;
use Image;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Filesystem\Cloud;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Database\Eloquent\Model;

class ReservationAttributeListFloat extends Model{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reservation_attributes_float';

    public function attributesAlias()
    {
        return $this->hasOne('WowTables\Http\Models\Eloquent\Reservations\ReservationAttributes', 'id', 'reservation_attribute_id');
    }
} 