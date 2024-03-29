<?php namespace WowTables\Http\Controllers\ConciergeApi;

use Carbon\Carbon;
use WowTables\Http\Models\Eloquent\ConciergeApi\UserDevice;
use WowTables\Http\Models\Eloquent\ConciergeApi\VendorLocationContact;
use WowTables\Http\Models\Eloquent\User;
use Rhumsaa\Uuid\Uuid;
use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocation;
use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationAttributesText;
use WowTables\Http\Models\Eloquent\Vendors\Vendor;
use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;

use Request;
use DB;
class UserController extends Controller {
    private static $seo_meta_desc_attr_id = 3;
    public function login()
    {
        try {
            $passwordMatch = false;
            $userDeviceUpdated = false;
            $access_token = '';
            $input = Request::all();
            $user = User::where('email', $input['email'])->first();

            if ($user) {
                if (crypt($input['password'], $user->password) == $user->password)
                    $passwordMatch = true;
            }
            if ($passwordMatch) {
                $userDevice = UserDevice::where('device_id', $input['device_id'])->first();
                $access_token = Uuid::uuid1()->toString();
                if ($userDevice) {
                    $userDeviceUpdated = $userDevice->update(['device_id' => $input['device_id'], 'rest_access_token' => $access_token
                        , 'rest_access_token_expires' => Carbon::now()->addDays(360),'rest_notification_id'=>$input['notification_id'], 'os_type' => $input['os_type']
                        , 'os_version' => $input['os_version'], 'hardware' => $input['hardware']
                        , 'rest_app_version' => $input['app_version'],'user_id' => $user->id]);
                } else {
                    $userDeviceUpdated = UserDevice::create(['device_id' => $input['device_id'], 'rest_access_token' => $access_token
                        , 'rest_access_token_expires' => Carbon::now()->addDays(360),'rest_notification_id'=>$input['notification_id'], 'os_type' => $input['os_type']
                        , 'os_version' => $input['os_version'], 'hardware' => $input['hardware']
                        , 'rest_app_version' => $input['app_version'], 'user_id' => $user->id]);
                }
            }
            if ($userDeviceUpdated) {
                $vendorLocationContact = VendorLocationContact::where('user_id', $user->id)->first();
                $vendorLocation = VendorLocation::where('id', $vendorLocationContact->vendor_location_id)->first();
                $vendor = Vendor::where('id', $vendorLocation->vendor_id)->first();

                return response()->json(['id' => $user->id, 'access_token' => $access_token, 'full_name' => $user->full_name, 'email' => $user->email, 'phone_number'
                => $user->phone_number, 'role' => $user->role->name,
                    'vendor_name' => $vendor->name], 200);
            } else {
                return response()->json([
                    'action' => 'Check if the email address and password match',
                    'message' => 'There is an email password mismatch. Please check and try again'
                ], 227);
            }
        }catch(\Exception $e){
            return response()->json([
            'message' => 'An application error occured.',
                'error' => $e->getMessage()
            ], 500);
        }


}

    public function addNotificationId(){
        try{
            $userDeviceUpdated = false;
            $input = Request::all();
            $userDevice = UserDevice::where(['device_id'=>$input['device_id'],'rest_access_token'=>$input['access_token']
            ]);
            if($userDevice) {
                $userDeviceUpdated = $userDevice->update(['rest_notification_id' => $input['notification_id']]);
            }
            if($userDeviceUpdated) {
                return [
                    'code' => 200,
                    'data' => new \stdClass()
                ];
            }else {
                return [
                    'code' => 227,
                    'data' => [
                        'action' => 'Check if access_token,device_id & user_id are valid',
                        'message' => 'Access_token,device_id or user_id is not valid. Please check an try again'
                    ]
                ];
            }
        }catch(\Exception $e){
            return response()->json([
                'message' => 'An application error occured.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function logout(){
        try{
            $input = Request::all();
            $userDevice = UserDevice::where(['device_id'=>$input['device_id'],'rest_access_token'=>$input['access_token'],'user_id'=>$input['user_id']])->first();
            if($userDevice)
                $userDevice->delete();
            return [
                'code' => 200,
                'data' => new \stdClass()
            ];
        }catch(\Exception $e){
            return response()->json([
                'message' => 'An application error occured.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

