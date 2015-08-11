<?php namespace WowTables\Http\Controllers\Api;

use Config;

//use Illuminate\Http\Request;
use WowTables\Http\Controllers\Controller;
//use WowTables\Http\Models\Search;
//use WowTables\Http\Models\ALaCarte;
use WowTables\Http\Models\Distance;
use Validator;
use Request;
//use WowTables\Http\Models\RestaurantLocations;

/**
 * Controller class DistanceController.
 * 
 * @version		1.0.0
 * @since		1.0.0
 * @author		Parth Shukla <shuklaparth@hotmail.com>
 */
 class DistanceController extends Controller { 	

 	/**
	 * Handles request for finding distance
	 * between two locations.
	 * 
	 * @access	public
	 * @param	object	Request
	 * @return	json
	 * @since	1.0.0
	 */
	public function getDistance( Request $request ) {
		
		//array to store information submitted by the user
		$arrSubmittedData = array();
			
		//reading the input data
		$input = Request::all();  	

		$validator = Validator::make($input,Distance::$arrRules);

        if($validator->fails()){
            $message=$validator->messages();
            $errorMessage="";
            foreach(Distance::$arrRules as $key => $value) {
                if($message->has($key)) {
                    //$errorMessage .= $message->first($key).'\n ';
                    $errorMessage .= $message->first($key).' ';
                }
            }
            $arrResponse['status'] = Config::get('constants.API_ERROR');
            $arrResponse['msg'] = $errorMessage;  
        }
        else{

            $distanceResult = Distance::getDistanceDetail($input);
			
			if($distanceResult) {	
				$arrResponse['status'] = Config::get('constants.API_SUCCESS');
				$arrResponse['data'] = array('distance' => $distanceResult); //(array_key_exists('data', $distanceResult)) ? $distanceResult['data']:array();

			} else {
				$arrResponse['status'] = Config::get('constants.API_ERROR');
				$arrResponse['data'] = array();
			}
        }				
		
		return response()->json($arrResponse,200);		
	}

	//-----------------------------------------------------------------
 }
 //end of class DistanceController
//end of file WowTables/Http/Controllers/Api/DistanceController.php	
	