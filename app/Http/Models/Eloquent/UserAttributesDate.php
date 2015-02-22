<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;


class UserAttributesDate extends Model {

    protected $table = 'user_attributes_date';

    protected $fillable = ['attribute_value'];

    protected $with = ['attribute'];

    protected $hidden = ['created_at','updated_at'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\UserAttributes', 'user_attribute_id', 'id');
    }


    public function getDates()
    {
        return ['created_at','updated_at','attribute_value'];
    }

}