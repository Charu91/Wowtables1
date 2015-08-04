<?php namespace WowTables\Http\Models;

	use DB;
	use Config;
	

	/**
	 * Model class Distance.
	 *
	 * @since		1.0.0
	 * @version		1.0.0
	 * @author		Parth Shukla <shuklaparth@hotmail.com>
	 */
	class Distance {

		static $arrRules = array(                            
	                             'slatitude'     => 'required',
	                             'slongitude'  	=> 'required',
	                             'dlatitude'     => 'required',
	                             'dlongitude'  	=> 'required'
	                           );
    	//-------------------------------------------------------------

		/**
		 * Reads the details of the restaurants matching the passed string.
		 * 
		 * @access	public
		 * @static	true
		 * @param	string	$matchString
		 * @return	array
		 * @since	1.0.0
		 */
		public static function getDistanceDetail($input) {
			
			//array to store the restaurant details
			$data = array();

			$lat1 = $input['slatitude'];
			$log1 = $input['slongitude'];

			$lat2 = $input['dlatitude'];
			$log2 = $input['dlongitude'];

			// $id  = '249';
			// $queryResult = DB::table('vendor_location_address as vla')
			// 			->where('vla.id', '=', $id)
			// 			->select('vla.longitude', 'vla.latitude')//->toSql();
			// 			// ->select((((acos(sin((".$latitude."*pi()/180)) * sin((`Latitude`*pi()/180))+cos((".$latitude."*pi()/180)) * cos((`Latitude`*pi()/180)) * cos(((".$longitude."- `Longitude`)*pi()/180))))*180/pi())*60*1.1515) as distance)				
			// 			->first();

			// $lat2 = $queryResult->latitude;
			// $log2 = "-".$queryResult->longitude;
			
			$dist = (((acos(sin(($lat1*pi()/180)) * sin(($lat2*pi()/180))+cos(($lat1*pi()/180)) *
 					cos(($lat2*pi()/180)) * cos((($log1 - $log2)*pi()/180))))*180/pi())*60*1.1515);

			$dist =$dist * 1.609344;
			$distance =round($dist,2);   //print_r($distance); die("nhi...11");

			return $arrResponse['data'] = $distance;

		} //End of function getDistanceDetail()

		//-----------------------------------------------------------------

	}
//end of class Distance
//end of file WowTables\Http\Models\Distance