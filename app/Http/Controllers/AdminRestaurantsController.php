<?php namespace WowTables\Http\Controllers;

use WowTables\Core\Repositories\Restaurants\RestaurantRepository;
use WowTables\Http\Requests\Admin\CreateRestaurantRequest;
use WowTables\Http\Requests\Admin\UpdateRestaurantRequest;
use WowTables\Http\Requests\Admin\DeleteRestaurantRequest;
use WowTables\Http\Models\Restaurant;
use Illuminate\Http\Request;

/**
 * Class AdminRestaurantsController
 * @package WowTables\Http\Controllers
 *
 */

class AdminRestaurantsController extends Controller {

    /**
     * The Single Restaurant Object
     *
     * @var object
     */
    protected $restaurant;

	/**
	 * The constructor Method
	 *
	 * @param Request $request
	 * @param RestaurantRepository $repo
	 */
    function __construct(Request $request, RestaurantRepository $repo, Restaurant $restaurant)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
		$this->repo = $repo;

        $this->restaurant = $restaurant;
    }

	/**
	 * Display a listing of the resource.
	 *
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
	 * @return Response
	 */
	public function create()
	{
		return view('admin.restaurants.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateRestaurantRequest $createRestaurantRequest)
	{
		$input = $this->request->all();

        $createRestaurant = $this->restaurant->create($input);

        if($createRestaurant['status'] === 'success'){
			if($this->request->ajax()) {
				return response()->json(['status' => 'success'], 200);
			}
			flash()->success('The restaurant has been successfully created.');
			return redirect()->route('AdminGetRestaurants');
		}else{
            return response()->json([
                'action' => $createRestaurant['action'],
                'message' => $createRestaurant['message']
            ], 400);
        }


	}

	/**
	 * Display the specified resource.
	 *
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
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$restaurant = $this->repo->getByRestaurantId($id);

		return view('admin.restaurants.edit', ['restaurant' => $restaurant]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, UpdateRestaurantRequest $updateRestaurantRequest)
	{
        $input = $this->request->all();

        $updateRestaurant = $this->restaurant->update($id, $input);

        if($updateRestaurant['status'] === 'success'){
            return response()->json(['status' => 'success'], 200);
        }else{
            return response()->json([
                'action' => $updateRestaurant['action'],
                'message' => $updateRestaurant['message']
            ], 400);
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @return Response
	 */
	public function destroy($id, DeleteRestaurantRequest $deleteRestaurantRequest)
	{
		$deleteRestaurant = $this->restaurant->delete($id);

        if($deleteRestaurant['status'] === 'success'){
			flash()->success('The restaurant has been successfully deleted.');
			return response()->json(['status' => 'success'], 200);
        }else{
            return response()->json([
                'action' => $deleteRestaurant['action'],
                'message' => $deleteRestaurant['message']
            ], 400);
        }
	}

}
