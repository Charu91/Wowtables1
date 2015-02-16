<?php namespace WowTables\Http\Controllers;

use WowTables\Http\Models\Schedules;
use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;

use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;

class AdminRestaurantLocationsController extends Controller {

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
	 * @param Encrypter $encrypter
	 * @return Response
	 */
	public function index(Encrypter $encrypter)
	{
		$token = $encrypter->encrypt(csrf_token());

		return view('admin.restaurants.locations',['_token' => $token]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
		//
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

			$breakfast = [];
			$lunch = [];
			$dinner = [];

			foreach ( $fetchSchedules['schedules'] as $schedule )
			{
				if ( $schedule['slot_type'] == 'Breakfast' )
				{
					$breakfast [] = $schedule;
				}
				if ( $schedule['slot_type'] == 'Lunch' )
				{
					$lunch [] = $schedule;
				}
				if ( $schedule['slot_type'] == 'Dinner' )
				{
					$dinner [] = $schedule;
				}
			}

			$data = [
				'breakfast' => $breakfast,
				'lunch'		=> $lunch,
				'dinner'	=> $dinner
			];

			return view('admin.restaurants.schedules_table', $data);

		} else {
			return response('Something went wrong', 400);
		}
	}

}
