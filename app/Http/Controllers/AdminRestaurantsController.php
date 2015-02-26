<?php namespace WowTables\Http\Controllers;

use WowTables\Core\Repositories\Restaurants\RestaurantRepository;
use WowTables\Http\Requests\CreateRestaurantRequest;
use Illuminate\Http\Request;

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
	 * @param RestaurantRepository $repo
	 */
    function __construct(Request $request,RestaurantRepository $repo)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
		$this->repo = $repo;
    }

	/**
	 * Display a listing of the resource.
	 *
     * @Get("/", as="AdminGetRestaurants")
	 * @return Response
	 */
	public function index()
	{
		$restaurants = $this->repo->getAll();

		return view('admin.restaurants.index',['restaurants'=>$restaurants]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
     * @Get("/create", as="AdminRestaurantCreate")
	 * @return Response
	 */
	public function create()
	{
		return view('admin.restaurants.create');
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
		$restaurant = $this->repo->getByRestaurantId($id);

		return view('admin.restaurants.edit',['restaurant'=>$restaurant]);
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
		dd($this->request->all());
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
