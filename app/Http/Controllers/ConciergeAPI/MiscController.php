<?php namespace WowTables\Http\Controllers\ConciergeAPI;

use Config;
use DB;
use Request;
use WowTables\Http\Models\Eloquent\ConciergeApi\RestaurantFaq;
use WowTables\Http\Models\Eloquent\ConciergeApi\UserDevice;
use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;


class MiscController extends Controller {


	public function checkVersion()
	{
		try {
			$input = Request::all();
			$retFlag = false;
			$accessToken = (array_key_exists('HTTP_X_WOW_TOKEN', $_SERVER)) ? $_SERVER['HTTP_X_WOW_TOKEN']:"";
			$accessDevice = (array_key_exists('HTTP_X_WOW_DEVICE', $_SERVER)) ? $_SERVER['HTTP_X_WOW_DEVICE']:"";
			//if(empty($accessToken)) {
				$iOSVersion = Config::get('constants.MIN_SUPPORTED_IOS_VERSION');
				$androidVersion = Config::get('constants.MIN_SUPPORTED_ANDROID_VERSION_CONCIERGE');

				if($input['os_type'] == 'iOS' && version_compare($input['app_version'], $iOSVersion) >= 0) {
					$retFlag = true;
				}
				else if ($input['os_type'] == 'Android' && version_compare($input['app_version'], $androidVersion) >= 0) {
					$retFlag = true;
				}
				else {
					$retFlag = false;
				}
			//}
			/*else if(!empty($accessDevice) && !empty($accessToken) && !empty(trim(($input['app_version']))) ) {
				$queryResult = DB::table('user_devices as ud')
					->where('ud.device_id',$accessDevice)
					->where('ud.access_token',$accessToken)
					->select('ud.user_id', 'device_id',
						'access_token', 'app_version', 'os_type')
					->first();

				if($queryResult) {

					if( version_compare($queryResult->rest_app_version, $input['app_version']) >= 0) {
						$retFlag = true;
					}
					else {
						$appUpdateStatus = DB::table('user_devices as ud')
							->where('ud.device_id', $queryResult->device_id)
							->where('ud.access_token', $queryResult->access_token)
							->update(['app_version' => $input['app_version']]);
						$retFlag = true;
					}

				}
			}else
				$retFlag = false;*/
			if ($retFlag) {
				DB::table('user_devices as ud')
					->where('ud.device_id', $accessDevice)
					->where('ud.rest_access_token', $accessToken)
					->update(['rest_app_version' => $input['app_version']]);
				$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			}else
				$arrResponse['status'] = Config::get('constants.API_ERROR');
			return response()->json($arrResponse, 200);
		}catch(\Exception $e){
			return response()->json([
				'message' => 'An application error occured.'
			], 500);
		}
	}

	public function getFaqs(){
		try {
			$faqArray = RestaurantFaq::orderBy('category')
				->get();
			return response()->json($faqArray, 200);
		}catch(\Exception $e){
			return response()->json([
				'message' => 'An application error occured.'
			], 500);
		}
	}
}
