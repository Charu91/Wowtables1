<?php namespace WowTables\Http\Controllers\Api;

use Illuminate\Http\Request;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Search;

/**
 * 
 */
 class SearchController extends Controller {
 	
	protected $search;
	
	
	public function __construct(Search $search) {
		$this->search = $search;
	}
	
	//-----------------------------------------------------------------
 	
	/**
	 * Handles request for searching the vendors
	 * based on parameters.
	 * 
	 * @access	public
	 * @param	object	Request
	 * @return	json
	 * @since	1.0.0
	 */
	public function searchExperience(Request $request) {
		//array to store information submitted by the user
		$arrSubmittedData = array();
		
		#reading the information submitted by the user
		$arrSubmittedData['time'] = $request->input('bookingTime');
		$arrSubmittedData['day'] = $request->input('bookingDay');
		$arrSubmittedData['minPrice'] = $request->input('minPrice');
		$arrSubmittedData['maxPrice'] = $request->input('maxPrice');
		$arrSubmittedData['arrLocation'] = $request->input('location');
		$arrSubmittedData['arrCuisine'] = $request->input('cuisine');
		$arrSubmittedData['arrTags'] = $request->input('tags');
		
		#reading the information from the DB
		$searchResult = $this->search->findMatchingExperience($arrSubmittedData);
		
		return response()->json($searchResult,200);
		
	}
 }
//end of class SearchController
//end of file WowTables/Http/Controllers/Api/SearchController.php