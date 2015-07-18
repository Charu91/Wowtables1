<?php namespace WowTables\Http\Models;

use DB;

/**
 * Model class AppVersion.
 * 
 * @version	1.0.0
 * @since	1.0.0
 * @author	Parth Shukla <shuklaparth@hotmail.com>
 */
class AppVersion {

	/**
	 * Update the app version  	 
	 * 
	 * @access	public
	 * @param	 	 
	 * @return	array
	 * @since	1.0.0
	 * @version	1.0.0
	 */
	public static function updateAppVersion($data) {

		$accessToken = (array_key_exists('HTTP_X_WOW_TOKEN', $_SERVER)) ? $_SERVER['HTTP_X_WOW_TOKEN']:"";
		$accessDevice = (array_key_exists('HTTP_X_WOW_DEVICE', $_SERVER)) ? $_SERVER['HTTP_X_WOW_DEVICE']:"";
		//$appVersion = (array_key_exists('HTTP_X_WOW_VERSION', $_SERVER)) ? $_SERVER['HTTP_X_WOW_VERSION']:"";
					
		if(!empty($accessDevice) && !empty($accessToken) && !empty(trim(($data['appVersion']))) ) {
			$queryResult = DB::table('user_devices as ud')
								->where('ud.device_id',$accessDevice)
								->where('ud.access_token',$accessToken)
								->select('ud.user_id', 'device_id', 
										'access_token', 'app_version', 'os_type')
								->first();

			if($queryResult) {

				if( version_compare($queryResult->app_version, $data['appVersion']) >= 0) {			
					return TRUE;						
				}
				else {
					$appUpdateStatus = DB::table('user_devices as ud')
				 						->where('ud.device_id', $queryResult->device_id)
				 						->where('ud.access_token', $queryResult->access_token)
										->update(['app_version' => $data['appVersion']]);
					return TRUE;
				}

			}
		}

		return FALSE;

	}
}
//end of class AppVersion.
//end of file WowTables\Http\Models\AppVersion.php