<?php namespace WowTables\Http\Controllers;

use WowTables\Http\Requests\CreateRestaurantRequest;
use WowTables\Http\Models\Restaurants;
use Illuminate\Support\Facades\Request;

/**
 * Class AdminRestaurantsController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="/admin/restaurants")
 */

class AdminRestaurantsController extends Controller {

    /**
     * The constructor Method
     *
     * @param Request $request
     */
    function __construct(Request $request)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
    }

	/**
	 * Display a listing of the resource.
	 *
     * @Get("/", as="AdminGetRestaurants")
	 * @return Response
	 */
	public function index()
	{
		return view('admin.restaurants.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
     * @Get("/create", as="AdminRestaurantCreate")
	 * @return Response
	 */
	public function create()
	{
		return view('admin.restaurants.add_update');
	}

	/**
	 * Store a newly created resource in storage.
	 *
     * @Post("/", as="AdminRestaurantStore")
	 * @return Response
	 */
	public function store()
	{
		$input = $this->request->all();

		dd($input);
	}

	/**
	 * Display the specified resource.
	 *
     * @Get("/{id}", as="AdminRestaurantShow")
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return view('admin.restaurants.single');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
     * @Get("/edit/{id}", as="AdminRestaurantEdit")
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return view('admin.restaurants.add_update');
	}

	/**
	 * Update the specified resource in storage.
	 *
     * @Put("/{id}", as="AdminRestaurantUpdate")
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
     * @Delete("/", as="AdminRestaurantsDelete")
	 * @return Response
	 */
	public function destroy()
	{
		//
	}

}
