<?php namespace WowTables\Http\Controllers;

use WowTables\Core\Repositories\Restaurants\RestaurantLocationsRepository;
use WowTables\Http\Models\Schedules;
use Illuminate\Http\Request;
use WowTables\Http\Requests\Admin\CreateRestaurantLocationRequest;
use WowTables\Http\Models\RestaurantLocation;

class AdminRestaurantLocationsController extends Controller {


    protected $restaurantLocation;

    protected $request;
	/**
	 * The constructor Method
	 *
	 * @param RestaurantLocationsRepository $repository
	 */
    function __construct(RestaurantLocationsRepository $repository, RestaurantLocation $restaurantLocation, Request $request)
    {
        $this->middleware('admin.auth');
		$this->repository = $repository;

        $this->request = $request;
        $this->restaurantLocation = $restaurantLocation;
    }


	/**
	 * @return \Illuminate\View\View
     */
	public function index()
	{
		$RestaurantLocations = $this->repository->getAll();

		return view('admin.restaurants.locations.index',['RestaurantLocations' => $RestaurantLocations]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('admin.restaurants.locations.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CreateRestaurantLocationRequest $request
	 * @return Response
	 */
	public function store(CreateRestaurantLocationRequest $request)
	{
        $input = $this->request->all();

        $restaurantLocationCreate = $this->restaurantLocation->create($input);

        if($restaurantLocationCreate['status'] === 'success'){
            return response()->json(['status' => 'success'], 200);
        }else{
            return response()->json([
                'action' => $restaurantLocationCreate['action'],
                'message' => $restaurantLocationCreate['message']
            ], 400);
        }

        /*
		$this->dispatchFrom('WowTables\Commands\Admin\CreateRestaurantLocationCommand', $request);

		flash()->success('Restaurant Location has been successfully created!!!');

		return redirect()->route('AdminRestaurantLocations');
        */
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

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$restaurant = $this->repository->getByRestaurantLocationId($id);

		return view('admin.restaurants.locations.edit',['restaurant'=>$restaurant]);
	}

	/**
	 * Update the specified resource in storage.
	 *
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
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	public function available_time_slots(Request $request , Schedules $schedules)
	{
		$start_time = $request->get('start_time');
		$end_time = $request->get('end_time');

		$fetchSchedules = $schedules->available_time_slots($start_time,$end_time);

		if($fetchSchedules['status'] === 'success') {

			$data = $this->formatSchedules($fetchSchedules);

			return view('admin.restaurants.schedules_table', $data);

		} else {
			return response('Something went wrong', 400);
		}
	}

	/**
	 * @param $fetchSchedules
	 * @return array
	 */
	public function formatSchedules($fetchSchedules)
	{
		$breakfast = [];
		$lunch = [];
		$dinner = [];

		foreach ($fetchSchedules['schedules'] as $schedule) {
			if ($schedule['slot_type'] == 'Breakfast') {
				$breakfast [] = $schedule;
			}
			if ($schedule['slot_type'] == 'Lunch') {
				$lunch [] = $schedule;
			}
			if ($schedule['slot_type'] == 'Dinner') {
				$dinner [] = $schedule;
			}
		}

		$data = [
			'breakfast' => $breakfast,
			'lunch' => $lunch,
			'dinner' => $dinner
		];
		return $data;
	}

}
