<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\CollectionTags;
use WowTables\Http\Models\RestaurantLocations;
use WowTables\Http\Models\Search;
use Config;

/**
 * Model class CollectionController
 *
 * @package WowTables
 */
class CollectionController extends Controller {

	protected $listings, $experienceList;

	public function __construct(RestaurantLocations $listings, Search $experienceList) {

		$this->listings = $listings;
		$this->experienceList = $experienceList;
	}


	public function index() {
			return response()->json(CollectionTags::readAllCollection(),200);
	}

	//-----------------------------------------------------------------

	/**
	 * Displays the experiences matching the tag.
	 *
	 * @access    public
	 * @param     integer  $tagID
	 * @return    response
	 * @since     1.0.0
	 */
	public function show($tagID) {

		if(!is_numeric($tagID)) {
			$tagID = CollectionTags::getSlugID($tagID);
		}

		$filters = array(
								'city_id' => $_SERVER['HTTP_X_WOW_CITY'],
								'tag' => array($tagID)
							
						);
		//reading the alacarte details 
		$this->listings->fetchListings($filters);
        $arrAlacarte = (is_null($this->listings->arr_result)) ? array():$this->listings->arr_result;

        //reading details of the experiences
        $arrExperiences = $this->experienceList->findMatchingExperience($filters);

        $arrResponse['status'] = Config::get('constants.API_SUCCESS');

		$arrResponse['data'] = CollectionTags::getCollectionTagDetail($tagID);
		$arrResponse['data']['alacarte'] = $arrAlacarte;
		$arrResponse['data']['alacarteResultCount']  = count($arrAlacarte);
		$arrResponse['data']['experience'] = $arrExperiences['data'];
		$arrResponse['data']['experienceResultCount'] = $arrExperiences['resultCount'];				
		$arrResponse['no_result_msg'] = 'No matching data found.';

		return response()->json($arrResponse,200);      	
			
	}
}
//end of class CollectionController.
//end of file CollectionController.php