<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Locations;

use Illuminate\Http\Request;

class LocationsController extends Controller {

    /**
     * The locations object
     *
     * @var object
     */
    protected $locations;

    /**
     * The constructor method
     *
     * @param Locations $locations
     */
    public function __construct(Locations $locations)
    {
        $this->locations = $locations;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function cities()
	{
        $locations = $this->locations->getLocationsByType('City');
        $cities = [];

        foreach($locations as $key => $location){
            $cities[] = ['locations_id' => $key, 'name' => $location];
        }

        return response()->json(['cities' => $cities], 200);
	}

}
