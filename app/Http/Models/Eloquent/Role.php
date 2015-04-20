<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany('WowTables\Http\Models\Eloquent\User', 'role_id', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany('WowTables\Http\Models\Eloquent\Role','role_permissions');
    }
}
