<?php namespace WowTables\Http\Middleware;

use Closure;
use DB;
use Config;
/**
 * Middleware class to verify user access for 
 * API requests.
 * 
 * @package Wowtables
 * @since	1.0.0
 * @version	1.0.0
 * @author  Parth Shukla<shuklaparth@hotmail.com>
 */
class ConciergeMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		$accessToken = (array_key_exists('HTTP_X_WOW_TOKEN', $_SERVER)) ? $_SERVER['HTTP_X_WOW_TOKEN']:"";
		$accessDevice = (array_key_exists('HTTP_X_WOW_DEVICE', $_SERVER)) ? $_SERVER['HTTP_X_WOW_DEVICE']:"";
		
		//$accessToken = $_SERVER['HTTP_X_WOW_TOKEN'];
		//$accessDevice = $_SERVER['HTTP_X_WOW_DEVICE'];
		
		//print_r($accessToken); print_r($accessDevice); die();
		if(!empty($accessDevice) && !empty($accessToken)) {
			$queryResult = DB::table('user_devices as ud')
								->where('ud.device_id',$accessDevice)
								->where('ud.access_token',$accessToken)
								->select('ud.user_id','rest_app_version','app_version', 'os_type')
								->first();
			if($queryResult){
				
				
				$iOSVersion = Config::get('constants.MIN_SUPPORTED_IOS_VERSION');
				$androidVersion = Config::get('constants.MIN_SUPPORTED_ANDROID_VERSION_CONCIERGE');

				if($queryResult->os_type == 'iOS' && version_compare($queryResult->rest_app_version, $iOSVersion) >= 0) {
						return $next($request);
				}
				else if ($queryResult->os_type == 'Android' && version_compare($queryResult->rest_app_version, $androidVersion) >= 0) {
						return $next($request);
				}
				else {
						$arrResponse['status'] = Config::get('constants.API_UPDATE');
						$arrResponse['msg'] = "Update your app version";
						return $arrResponse;
				}
					
			}
		}
		$response=array();
		$response['status'] = Config::get('constants.API_ERROR');				
		$response['msg']= "Please login into your Wowtables account.";
			return response()->json($response, 200);
		}
}
//end of class AuthorizeMiddleware.php
//end of file WowTables/Http/Middleware/AuthorizeMiddleware.php