<?php namespace WowTables\Http\Controllers\Api;

use Illuminate\Http\Requests;
use WowTables\Http\Controllers\Controller;
use Config;
use Illuminate\Http\Request;
use WowTables\Http\Models\AppVersion;

/**
 * Controller class CheckVersionController.
 * 
 * @since	1.0.0
 * @version	1.0.0
 * @author	Parth Shukla<shuklaparth@hotmail.com>
 */
class CheckVersionController extends Controller { 

	/**
	 * Instance of Request class.
	 * 
	 * @var		Request
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $request;

	public function __construct(Request $request) {
		$this->request = $request;		
	}

	/**
	 * Check for device version is compatible or not
	 * 
	 * 
	 * @access	public
	 * @param	string	 
	 * @return	json
	 * @since	1.0.0
	 */
	public function checkVersion( ) {

		//$deviceType = "Android";
		//$appVersion = "1.0.3";
		$data =  $this->request->all();

		$iOSVersion = Config::get('constants.MIN_SUPPORTED_IOS_VERSION');
		$androidVersion = Config::get('constants.MIN_SUPPORTED_ANDROID_VERSION');
		
		if($data['deviceType'] == 'iOS' && version_compare($data['appVersion'], $iOSVersion) >= 0) {			
				$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			}
		else if ($data['deviceType'] == 'Android' && version_compare($data['appVersion'], $androidVersion) >= 0) {		
				$arrResponse['status'] = Config::get('constants.API_SUCCESS');				
		}
		else {
				$appVersion = (array_key_exists('HTTP_X_WOW_VERSION', $_SERVER)) ? $_SERVER['HTTP_X_WOW_VERSION']:"";
				
				if(!empty($appVersion)) {
					$status = AppVersion::updateAppVersion();
					if($status == "TRUE")
						$arrResponse['status'] = Config::get('constants.API_SUCCESS');
					else
						$arrResponse['status'] = Config::get('constants.API_ERROR');
				}
				else {
					$arrResponse['status'] = Config::get('constants.API_ERROR');
				}				
		}			
		
		return response()->json($arrResponse,200);
		
	}
}
//end of class CheckVersionController
//end of file WowTables\Http\Controllers\Api\CheckVersionController.php