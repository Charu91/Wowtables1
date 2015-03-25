<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\RestaurantLocations;
use WowTables\Http\Requests\Api\FetchRestaurantLocationsRequest;

use Illuminate\Http\Request;

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

        return response()->json([
            'listing' => $restaurantLocations->listing,
            'filters' => $restaurantLocations->filters,
            'total_count' => $restaurantLocations->total_count,
            'total_pages' => $restaurantLocations->total_pages,
            '$sort_options' => $restaurantLocations->sort_options,
        ]);
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
