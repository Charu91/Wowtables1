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

		if(!is_int($tagID)) {
			$tagID = CollectionTags::getSlugID($tagID);
		}

		$filters = array(
								'city_id' => $_SERVER['HTTP_X_WOW_CITY'],
								'tag' => array($tagID)
							
						);

        	$this->listings->fetchListings($filters);
        	$arrResult = $this->listings->arr_result;

        	if(empty($arrResult)) {
			$arrResult['status1'] = Config::get('constants.API_SUCCESS');
			$arrResult['no_result_msg1'] = 'No matching data found.';
			$arrResult['data1'] = array(
										'listing' => array()
									);
			$arrResult['total_count1'] = 0;
			}
			else {
			$arrResult = array(
							'status1' => Config::get('constants.API_SUCCESS'),
							'data1' => array(
											'listing' => $arrResult,
            								//'filters' => $this->listings->filters,            								
            								'total_pages' => $this->listings->total_pages,
            								'sort_options' => $this->listings->sort_options,
            							),
            				'total_count1' => $this->listings->total_count,
            				'no_result_msg1' => 'No matching result found.'
										
						);
			}	

        	$searchResult= $this->experienceList->findMatchingExperience($filters);
        			
				if(!empty($searchResult)) {
					//setting up the array to be formatted as json
					$arrResult['status2'] = Config::get('constants.API_SUCCESS');
					$arrResult['resultCount2'] = $searchResult['resultCount'];
					$arrResult['data2'] = (array_key_exists('data', $searchResult)) ? $searchResult['data']:array();
					//$arrResult['filters2'] = $this->experienceList->getExperienceSearchFilters();
					$arrResult['no_result_msg2'] = 'No matching data found.';
				}
				else {
					$arrResult['status2'] = Config::get('constants.API_SUCCESS');
					$arrResult['resultCount2'] = 0;
					$arrResult['data2'] = array();
					$arrResult['no_result_msg2'] = 'No matching data found.';
				}


				$arrResponse['status'] = Config::get('constants.API_SUCCESS');
				$arrResponse['data'] = array(
												"alacarte" => $arrResult['data1']['listing'],
												"alacarteResultCount" => $arrResult['total_count1'],
												"experience" => $arrResult['data2'],
												"experienceResultCount" => $arrResult['resultCount2']
											);
				$arrResponse['no_result_msg'] = 'No matching data found.';

				return response()->json($arrResponse,200);      	
			
	}
}
//end of class CollectionController.
//end of file CollectionController.php