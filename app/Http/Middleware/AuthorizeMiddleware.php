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
class AuthorizeMiddleware {

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
								->select('ud.user_id')
								->first();
			if($queryResult){
				return $next($request);	
			}
		}
		$response=array();
		$response['status'] = Config::get('constants.API_ERROR');				
		$response['msg']= "Not a valid request";
			
			return response()->json($response, 200);
		}
}
//end of class AuthorizeMiddleware.php
//end of file WowTables/Http/Middleware/AuthorizeMiddleware.php