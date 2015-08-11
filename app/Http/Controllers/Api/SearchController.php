<?php namespace WowTables\Http\Controllers\Api;

use Config;

use Illuminate\Http\Request;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Search;
use WowTables\Http\Models\ALaCarte;
use Validator;
use WowTables\Http\Models\RestaurantLocations;

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
				$arrResult['msg'] = $arrReturn['msg'];			
			} else {							
				//reading the matching experiences details from the DB
				$searchResult = $this->search->findMatchingExperience($arrSubmittedData);		
				
				if(!empty($searchResult)) {
					//setting up the array to be formatted as json
					$arrResult['status'] = Config::get('constants.API_SUCCESS');
					$arrResult['resultCount'] = $searchResult['resultCount'];
					$arrResult['data'] = (array_key_exists('data', $searchResult)) ? $searchResult['data']:array();
					$arrResult['filters'] = $this->search->getExperienceSearchFilters();
					$arrResult['no_result_msg'] = 'No results found. Try again with different filters or slide right to check for A la carte options matching your filters.';
				}
				else {
					$arrResult['status'] = Config::get('constants.API_SUCCESS');
					$arrResult['resultCount'] = 0;
					$arrResult['data'] = array();
					$arrResult['no_result_msg'] = 'No results found. Try again with different filters or slide right to check for A la carte options matching your filters.';
				}
			}
		}
		else {
			
			
			//validation failed
			$arrResult['status'] = Config::get('constants.API_ERROR');
			$arrResult['msg'] = 'Invalid Request';
		}				
		
		return response()->json($arrResult,200);		
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
     public function searchExperienceNew( Request $request, RestaurantLocations $restaurantLocations ) {

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
                 $arrResult['msg'] = $arrReturn['msg'];
             } else {
                 //reading the matching experiences details from the DB
                 $searchResult = $this->search->findMatchingExperience($arrSubmittedData);

                 if(!empty($searchResult)) {
                     //setting up the array to be formatted as json
                     $arrResult['status'] = Config::get('constants.API_SUCCESS');
                     $arrResult['resultCount'] = $searchResult['resultCount'];
                     $arrResult['data'] = (array_key_exists('data', $searchResult)) ? $searchResult['data']:array();
                     $arrResult['filters'] = $this->search->getExperienceSearchFilters();
                     $arrResult['no_result_msg'] = 'No matching data found.';
                 }
                 else {
                     $arrResult['status'] = Config::get('constants.API_SUCCESS');
                     $arrResult['resultCount'] = 0;
                     $arrResult['data'] = array();
                     $arrResult['no_result_msg'] = 'No matching data found.';
                 }
             }
         }
         else {


             //validation failed
             $arrResult['status'] = Config::get('constants.API_ERROR');
             $arrResult['msg'] = 'Invalid Request';
         }

         //return response()->json($arrResult,200);


         $restaurantLocations->fetchAll($input);
         $arrResult1 = $restaurantLocations->arr_result;



         if(empty($arrResult1)) {
             $arrResponse['status'] = Config::get('constants.API_SUCCESS');
             $arrResponse['no_result_msg'] = 'No matching data found.';
             $arrResponse['data'] = array(
                 'listing' => array()
             );
             $arrResponse['total_count'] = 0;
         }
         else {
             $arrResponse = array(
                 'status' => Config::get('constants.API_SUCCESS'),
                 'data' => array(
                     'listing' => $arrResult1,
//                     'filters' => $restaurantLocations->filters,
//                     'total_pages' => $restaurantLocations->total_pages,
//                     'sort_options' => $restaurantLocations->sort_options,
                 ),
                 'total_count' => $restaurantLocations->total_count,
                 'no_result_msg' => 'No matching result found.'

             );
         }


         //return response()->json($arrResponse,200);

         $arrResult['filters'] = $this->search->getExperienceSearchFilters();
         $arrResult1['filters']=$restaurantLocations->filters;


         foreach($arrResult['filters']['locations'] as $key => $value)
         {
             foreach($arrResult1['filters']['areas']['options'] as $key => $value1)
             {
                 if($value['id']==$value1['id'])
                 {
                    //Logic to increment the count value
                    // echo "hi";
                 }
                 else
                 {//echo "hi";
                     $arrResult['filters']['locations'][]=array(
                                                                 'id'=>$value1['id'],
                                                                 'name' =>$value1['name'],
                                                                 'count' =>$value1['count'],
                                                                 );
                 }

             }
         }
         //die();
         //print_r($arrResult['filters']['locations']);   die();

         $finalResult=array(
                        'experiences'=>$arrResult['data'],
                        'restaurants'=>$arrResponse['data']
         );



         //array_unique( array_merge($arrResult['filters']['locations'], $arrResult1['filters']['areas']['options']) );


        // $finalResult['experiences']=$arrResult['data'];
        // $finalResult['restaurants']=$arrResponse['data'];

         return response()->json($finalResult,200);
     }

     //-----------------------------------------------------------------



     /**
	 * Handles requests for showing restaurants names matching
	 * the passed name.
	 * 
	 * @access	public
	 * @param	string	$strName
	 * @return	response
	 * @since	1.0.0
	 */
	public function getMatchingRestaurantsName($strName) {
		return response()->json(Search::readRestaurantsByMatchingNames($strName), 200);
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Handles requests for showing the details of the 
	 * passed resource type matchning passed resource id.
	 * 
	 * @access	public
	 * @param	string		$resourceType
	 * @param	integer		$resourceID
	 * @return	response
	 * @since	1.0.0
	 */
	public function getResourceDetail($resourceType, $resourceID) {

		$data=array('vendorID' => $resourceID);
		//Validate vendor exist or not
        $validator = Validator::make($data,ALaCarte::$arrRules);

        if($validator->fails()){
            $message=$validator->messages();
            $errorMessage="";
            foreach($data as $key => $value) {
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n ';
                }
            }
            $arrResponse['status'] = Config::get('constants.API_SUCCESS');
            $arrResponse['msg'] = $errorMessage;
            return response()->json($arrResponse,200);
        }
        else{
            return response()->json(ALaCarte::getResturantBranchesInformation($resourceID),200);
        } 

		
	}

    //-----------------------------------------------------------------

    /**
     * Handles request for searching the nearby experiences
     * based on parameters.
     * 
     * @access  public
     * @param   object  Request
     * @return  json
     * @since   1.0.0
     */
    public function getNearbyResource(Request $request) {
        
        //reading the input data
         $input = $request->all();
        
        return response()->json(Search::getNearbyResturantInformation($input),200);
        
        
    }

 }
//end of class SearchController
//end of file WowTables/Http/Controllers/Api/SearchController.php