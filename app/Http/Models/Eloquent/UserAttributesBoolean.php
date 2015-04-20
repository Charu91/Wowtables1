<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;


class UserAttributesBoolean extends Model {

    protected $table = 'user_attributes_boolean';

    protected $fillable = ['attribute_value'];

    protected $with = ['attribute'];

    protected $hidden = ['id','user_id','user_attribute_id','created_at','updated_at'];

    protected $casts = [
        'attribute_value' => 'boolean'
    ];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\UserAttributes', 'user_attribute_id', 'id');
    }


}