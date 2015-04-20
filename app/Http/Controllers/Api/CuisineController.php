<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Eloquent\Cuisine;

/**
 * CuisineController class.
 * 
 * @since	1.0.0
 * @version	1.0.0
 * @author	Parth Shukla <shuklaparth@hotmail.com>
 */
class CuisineController extends Controller {
	
	/**
	 * Lists all the cuisine options available in database.
	 * 
	 * @return	json
	 * @since	1.0.0
	 */
	public function index() {
		//reading data from the DB
		$result = Cuisine::readAll();
		
		return response()->json($result,200);
	}
}
//end of class CuisineController
//end of file WowTables\Http\Controllers\Api