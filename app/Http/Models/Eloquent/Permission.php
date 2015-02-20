<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['action','resource'];


    public function role()
    {
        return $this->belongsToMany('WowTables\Http\Models\Eloquent\Role','role_permissions');
    }
}
