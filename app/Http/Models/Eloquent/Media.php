<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Media extends Model {

    protected $table = 'media';

    protected $with = ['media_resized'];

    public function media_resized()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\MediaResized','media_id','id');
    }

}