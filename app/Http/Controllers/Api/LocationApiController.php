<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Locations;

/**
 * Location API controller class.
 * 
 * @since	1.0.0
 * @version	1.0.0
 * @author	Parth Shukla <shuklaparth@hotmail.com>
 */
class LocationApiController extends Controller {
	
	/**
	 * Instance of Locations class
	 * 
	 * @var		object
	 * @access	protected
	 */
	protected $locations;
	
	//-----------------------------------------------------------------
	
	/**
	 * Constructor method.
	 * 
	 * @access	public
	 * @param	object $location
	 * @since	1.0.0
	 */
	public function __construct(Locations $locations) {
		//parent::__construct();
		$this->locations = $locations;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Displays the locations as per their type
	 * 
	 * @access	public
	 * @param	string	$type
	 * @return	json
	 * @since	1.0.0
	 */
	public function show($type) {
		$result  = $this->locations->getLocationsByType(ucfirst($type));
        $arrLocation = array();

        foreach($result as $key => $location){
            $cities[] = array('locations_id' => $key, 'name' => $location);
        }

        return response()->json(array('cities' => $cities), 200);
	}
}
//end of class LocationController
//end of file WowTables\Http\Controllers\Api\LocationController.php