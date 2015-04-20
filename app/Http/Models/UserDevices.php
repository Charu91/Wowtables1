<?php namespace WowTables\Http\Models;

use DB;

class UserDevices {

    public function unlink(array $data){

        DB::table('user_devices')->where([
            'device_id' => $data['device_id'],
            'access_token' => $data['access_token'],
            'user_id' => $data['user_id']
        ])->delete();

        return [
            'code' => 200,
            'data' => new \stdClass()
        ];
    }

    public function addNotificationId(array $data)
    {
        DB::table('user_devices')->where([
            'device_id' => $data['device_id'],
            'access_token' => $data['access_token'],
            'user_id' => $data['user_id']
        ])->update([
            'notification_id' => $data['notification_id']
        ]);

        return [
            'code' => 200,
            'data' => new \stdClass()
        ];
    }

} 