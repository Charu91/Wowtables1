<?php namespace WowTables\Http\Controllers;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;

use Illuminate\Http\Request;
use WowTables\Http\Models\Schedules;
use Illuminate\Contracts\Filesystem\Cloud;
use WowTables\Http\Models\RestaurantLocation;

class TestController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Schedules $schedules, RestaurantLocation $restaurantLocation)
	{
        $restaurantLocation->fetch(21, []);

        /*
        $fetchSchedules = $schedules->fetchAll();

        if($fetchSchedules['status'] === 'success'){
            return view('test', ['schedules' => $fetchSchedules['schedules']]);
        }else{
            return response('Something went wrong', 400);
        }
        */
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

}
