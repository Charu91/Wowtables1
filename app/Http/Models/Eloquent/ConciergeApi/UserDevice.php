<?php namespace WowTables\Http\Models\Eloquent\ConciergeApi;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model {

    protected $primaryKey = 'device_id';
    protected $fillable = [
        'user_id',
        'device_id',
        'access_token',
        'rest_access_token',
        'access_token_expires',
        'rest_access_token_expires',
        'rest_notification_id',
        'os_type',
        'os_version',
        'hardware',
        'app_version',
        'rest_app_version'
    ];

}
