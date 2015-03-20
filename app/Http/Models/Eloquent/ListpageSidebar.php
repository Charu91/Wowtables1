<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ListpageSidebar extends Model {

    protected $table = 'listpage_sidebar';

    protected $fillable = ['link','title','media_id','description','promotion_title','city_id','show_in_experience','show_in_alacarte'];

    protected $visible = ['link','title','media_id','description','promotion_title','city_id'];

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