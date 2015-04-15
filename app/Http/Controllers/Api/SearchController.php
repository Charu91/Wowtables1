<?php namespace WowTables\Http\Controllers\Api;

use Illuminate\Http\Request;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Search;
use Config;

/**
 * Controller class SearchController.
 * 
 * @version		1.0.0
 * @since		1.0.0
 * @author		Parth Shukla <shuklaparth@hotmail.com>
 */
 class SearchController extends Controller {
 	
	/**
	 * Instance of search model.
	 * 
	 * @var		object
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $search;
	
	//-----------------------------------------------------------------
	
	/**
	 * Default constructor.
	 * 
	 * @access	public
	 * @param	object	$search
	 * @since	1.0.0
	 */
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
	public function searchExperience( Request $request ) {
		//array to store information submitted by the user
		$arrSubmittedData = array();
		
		//reading the input data
		$input = $request->all();
		
		if(array_key_exists('filters', $input)) {
			
			$arrSubmittedData = $input['filters'];
			
			//validating the input data
			$arrReturn = Search::validateExperienceSearchData($arrSubmittedData);
			
			if($arrReturn['status'] == Config::get('constants.API_ERROR')) {
				
				//validation failed
				$arrResult['status'] = $arrReturn['status'];
				$arrResult['error'] = $arrReturn['msg'];
			
			} else {
				//reading the matching experiences details from the DB
				$searchResult = $this->search->findMatchingExperience($arrSubmittedData);		
		
				//setting up the filters
				$searchFilters = $this->search->getExperienceSearchFiltersTest($arrSubmittedData['city_id']);
		
				//setting up the array to be formatted as json
				$arrResult['status'] = Config::get('constants.API_SUCCESS');
				$arrResult['resultCount'] = $searchResult['resultCount'];
				$arrResult['data'] = $searchResult['data'];
				$arrResult['filters'] = $searchFilters['filters'];
			}
		}
		else {
			
			
			//validation failed
			$arrResult['status'] = Config::get('constants.API_ERROR');
			$arrResult['error'] = 'Invalid Request';
		}				
		
		return response()->json($arrResult,200);		
	}
 }
//end of class SearchController
//end of file WowTables/Http/Controllers/Api/SearchController.php