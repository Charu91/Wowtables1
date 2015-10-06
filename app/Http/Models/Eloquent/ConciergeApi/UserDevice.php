<?php namespace WowTables\Http\Models\Eloquent\ConciergeApi;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model {

    protected $primaryKey = 'device_id';
    protected $fillable = [
        'user_id',
        'device_id',
        'access_token',
        'access_token_expires',
        'os_type',
        'os_version',
        'hardware',
        'app_version'
    ];

}
