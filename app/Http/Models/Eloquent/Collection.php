<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model {

    protected $table = 'tags';

    protected $fillable = ['name','slug','media_id','description','seo_title','seo_meta_description','seo_meta_keywords','status'];

    protected $hidden = ['collection','filterable','seo_title','seo_meta_description','seo_meta_keywords','status','created_on','updated_on'];

    protected $with = ['media','collection_media'];

    public function media()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Media','media_id','id');
    }

    public function collection_media()
    {
        return $this->belongsTo('WowTables\Http\Models\Eloquent\Media','web_media_id','id');
    }


}