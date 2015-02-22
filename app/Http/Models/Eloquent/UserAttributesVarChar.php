<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;


class UserAttributesVarChar extends Model {

    protected $table = 'user_attributes_varchar';

    protected $fillable = ['attribute_value'];

    protected $with = ['attribute'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\UserAttributes', 'user_attribute_id', 'id');
    }


}