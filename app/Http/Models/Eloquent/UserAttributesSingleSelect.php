<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;


class UserAttributesSingleSelect extends Model {

    protected $table = 'user_attributes_singleselect';

    protected $fillable = [];

    protected $with = ['attribute','selectOptions'];

    public function attribute()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\UserAttributes', 'user_attribute_id', 'id');
    }

    public function selectOptions()
    {
        return $this->hasOne('WowTables\Http\Models\Eloquent\UserAttributesSelectOptions', 'id', 'user_attribute_select_option_id');
    }
}
