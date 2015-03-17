<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Curator extends Model {

    protected $table = 'curators';

    protected $fillable = ['name','media_id','bio','link','city_id'];

    protected $visible = ['id','name','media_id','link','city_id'];

    protected $with = ['media','location'];

    public function media()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Media','media_id','id');
    }

    public function location()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Location','city_id','id');
    }
}