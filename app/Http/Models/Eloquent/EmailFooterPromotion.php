<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class EmailFooterPromotion extends Model {

    protected $table = 'email_footer_promotions';

    protected $fillable = ['link','media_id','city_id','show_in_experience','show_in_alacarte'];

    protected $visible = ['link','media_id','city_id'];

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