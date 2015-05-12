<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\RestaurantLocations;
use WowTables\Http\Requests\Api\FetchRestaurantLocationsRequest;

use Illuminate\Http\Request;

use Config;

class RestaurantsController extends Controller {

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
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
											'listing' => $arrResult,
            								'filters' => $restaurantLocations->filters,            								
            								'total_pages' => $restaurantLocations->total_pages,
            								'sort_options' => $restaurantLocations->sort_options,
            							),
            				'total_count' => $restaurantLocations->total_count,
            				'no_result_msg' => 'No matching result found.'
										
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
}
