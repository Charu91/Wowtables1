<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\RestaurantLocations;
use WowTables\Http\Requests\Api\FetchRestaurantLocationsRequest;
use WowTables\Http\Models\Eloquent\Api\UserBookmarks;

use Illuminate\Http\Request;

use Config;


class RestaurantsController extends Controller {

    protected $request;

    public function __construct(Request $request, UserBookmarks $userBookmarks)
    {
        $this->request = $request;
		$this->user_bookmarks = $userBookmarks;
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(RestaurantLocations $restaurantLocations, FetchRestaurantLocationsRequest $fetchRestaurantLocationsRequest)
	{
        $input = $this->request->all();

        $restaurantLocations->fetchAll($input);
		$arrResult = $restaurantLocations->arr_result;
		if(empty($arrResult)) {
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			$arrResponse['no_result_msg'] = 'No results found. Try again with different filters or slide left to check for Experiences matching your filters.';
			$arrResponse['data'] = array(
										'listing' => array()
									);
			$arrResponse['total_count'] = 0;
		}
		else {
			$arrResponse = array(
							'status' => Config::get('constants.API_SUCCESS'),
							'data' => array(
											'listing' => $arrResult,
            								'filters' => $restaurantLocations->filters,            								
            								'total_pages' => $restaurantLocations->total_pages,
            								'sort_options' => $restaurantLocations->sort_options,
            							),
            				'total_count' => $restaurantLocations->total_count,
            				'no_result_msg' => 'No results found. Try again with different filters or slide left to check for Experiences matching your filters.'
										
						);
		}
		

        return response()->json($arrResponse,200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	public function bookmark($type,$id){

		$data['access_token']=$_SERVER['HTTP_X_WOW_TOKEN'];
		$userID = UserDevices::getUserDetailsByAccessToken($data['access_token']);

		if(!empty($userID) && !empty($type) && !empty($id)){
			$userBookmark = new UserBookmarks();
			if($type == 'experience'){

				$userBookmark->user_id = $userID;
				$userBookmark->type = "Product";
				$userBookmark->product_id = $id;
				$userBookmark->save();
			}

			if($type == 'alacarte'){
				$userBookmark->user_id = $userID;
				$userBookmark->type = "VendorLocation";
				$userBookmark->vendor_location_id = $id;
				$userBookmark->save();
			}

			$lastSaveId = $userBookmark->id;
			if(!empty($lastSaveId)){
				$arrResponse['status'] = Config::get('constants.API_SUCCESS');
			} else {
				$arrResponse['status'] = Config::get('constants.API_ERROR');
			}
			return $arrResponse;
		}


	}
}
