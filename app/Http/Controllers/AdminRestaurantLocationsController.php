<?php namespace WowTables\Http\Controllers;

use WowTables\Core\Repositories\Restaurants\RestaurantLocationsRepository;
use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocationBookingSchedule;
use WowTables\Http\Models\Schedules;
use Illuminate\Http\Request;
use WowTables\Http\Requests\Admin\CreateRestaurantLocationRequest;
use WowTables\Http\Requests\Admin\UpdateRestaurantLocationRequest;
use WowTables\Http\Requests\Admin\DeleteRestaurantLocationRequest;
use WowTables\Http\Models\RestaurantLocation;

class AdminRestaurantLocationsController extends Controller {


    protected $restaurantLocation;

    protected $request;
	/**
	 * The constructor Method
	 *
	 * @param RestaurantLocationsRepository $repository
	 */
    function __construct(RestaurantLocationsRepository $repository, RestaurantLocation $restaurantLocation, Request $request,Schedules $schedules)
    {
        $this->middleware('admin.auth');
		$this->repository = $repository;
		$this->schedules = $schedules;
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
		//dd($input);
		if(!isset($input['a_la_carte'])){
			$input['a_la_carte'] =  0;
			//$input['attributes']['menu'] =  $input['attributes']['old_menu'];
		}

        $restaurantLocationCreate = $this->restaurantLocation->create($input);

        if($restaurantLocationCreate['status'] === 'success'){
			if($this->request->ajax()) {
				return response()->json(['status' => 'success'], 200);
			}
			flash()->success('The restaurant location has been successfully created.');
			return redirect()->route('AdminRestaurantLocations');
        }else{
            return response()->json([
                'action' => $restaurantLocationCreate['action'],
                'message' => $restaurantLocationCreate['message']
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

        $vendorLocationLimits = $this->repository->populateVendorLocationLimits($id);

		$vendorLocationTags = $this->repository->populateVendorLocationTags($id);

		$vendorLocationFlags = $this->repository->populateVendorLocationFlags($id);

		$vendorLocationAddress = $this->repository->populateVendorLocationAddress($id);

		$vendorLocationBlockDates = $this->repository->populateVendorLocationBlockDates($id);

		$vendorLocationBlockTimeLimits = $this->repository->populateVendorLocationBlockTimeLimits($id);

		$vendorLocationContacts = $this->repository->populateVendorLocationContacts($id);

		$vendorLocationMedias = $this->repository->populateVendorLocationMedia($id);

		$vendorLocationCurators = $this->repository->populateVendorLocationCurators($id);

		$availableSchedules = $this->formatSchedules($this->schedules->available_time_slots('00:00','23:30'))['schedules'];


		$restaurantSchedules = VendorLocationBookingSchedule::where('vendor_location_id',$id)->lists('off_peak_schedule','schedule_id');

		//echo "<pre>"; print_r($restaurantSchedules); die;


		$schedules = array_keys($restaurantSchedules);

		$vendorLocationTagArray = array();

		foreach($vendorLocationTags as $vendorLocationTag){
			 array_push($vendorLocationTagArray,$vendorLocationTag->tag_id);
		}

		$gallery_media_array = array();
		$listing_media_array = array();
		$mobile_array = array();
		foreach($vendorLocationMedias as $vendorLocationMedia){

			if($vendorLocationMedia->media_type == "gallery"){
				//array_push($gallery_media_array,$vendorLocationMedia->file);
				$gallery_media_array[$vendorLocationMedia->media_id] = $vendorLocationMedia->file;
			}

			if($vendorLocationMedia->media_type == "listing"){
				//array_push($listing_media_array,$vendorLocationMedia->file);
				$listing_media_array[$vendorLocationMedia->media_id] = $vendorLocationMedia->file;
			}

			if($vendorLocationMedia->media_type == "mobile"){
				//array_push($mobile_array,$vendorLocationMedia->file);
				$mobile_array[$vendorLocationMedia->media_id] = $vendorLocationMedia->file;
			}
		}
		$vendorLocationMedia = array('listing'=>$listing_media_array,'gallery'=>$gallery_media_array,'mobile'=>$mobile_array);

       //echo "<pre>"; print_r($vendorLocationFlags); die; //echo "<hr/>"; print_r($restaurantSchedules); echo "<hr/>"; print_r($schedules);die;

		return view('admin.restaurants.locations.edit',[
					'restaurant'=>$restaurant,
					'restaurantLocationLimits'=>((isset($vendorLocationLimits[0]) && $vendorLocationLimits[0]->id != '') ? $vendorLocationLimits[0] : ''),
					'schedules'=>$schedules,
					'availableSchedules' => $availableSchedules,
					'restaurantSchedules' => $restaurantSchedules,
					'restaurantLocationTags' => $vendorLocationTagArray,
					'restaurantLocationAddress' => ((isset($vendorLocationAddress[0]) && $vendorLocationAddress[0]->id != '') ? $vendorLocationAddress[0] : ''),
					'restaurantLocationFlags' => ((isset($vendorLocationFlags[0]) && $vendorLocationFlags[0]->id != '') ? $vendorLocationFlags[0] : ''),
					'restaurantLocationBlockDates' => $vendorLocationBlockDates,
					'restaurantLocationBlockTimeLimits' => $vendorLocationBlockTimeLimits,
					'restaurantLocationContacts' => $vendorLocationContacts,
					'restaurantLocationMedias' => $vendorLocationMedia,
					'restaurantLocationCurators' => ((isset($vendorLocationCurators[0]) && $vendorLocationCurators[0]->id != '') ? $vendorLocationCurators[0] : ''),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, UpdateRestaurantLocationRequest $updateRestaurantLocationRequest)
	{
        $input = $this->request->all();

        $restaurantLocationUpdate = $this->restaurantLocation->update($id, $input);

        if($restaurantLocationUpdate['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }
            flash()->success('The restaurant location has been successfully updated.');
            return redirect()->route('AdminRestaurantLocations');
        }else{
            return response()->json([
                'action' => $restaurantLocationUpdate['action'],
                'message' => $restaurantLocationUpdate['message']
            ], 400);
        }
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, DeleteRestaurantLocationRequest $deleteRestaurantLocationRequest)
	{
		$deleteRestaurant = $this->restaurantLocation->delete($id);

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


	public function available_time_slots(Request $request , Schedules $schedules)
	{
		$start_time = $request->get('start_time');
		$end_time = $request->get('end_time');

		$fetchSchedules = $schedules->available_time_slots($start_time,$end_time);

		if($fetchSchedules['status'] === 'success') {

			$data = $this->formatSchedules($fetchSchedules);
			if ( $request->has('type') && $request->get('type') == 'experience' )
			{
				return view('admin.experiences.locations.schedules_table', $data);
			}
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
		$schedules = [];

		foreach ($fetchSchedules['schedules'] as $schedule) {

			$schedules [] = $schedule;
		}

		$data = [
			'schedules' => $schedules,
		];

		return $data;
	}

	public function getCityName($name){
		//echo "name == ".$_POST['locality_value'];
		//die;

		if(isset($_POST['locality_value']) && $_POST['locality_value'] != ""){
			$vendorLocationCurators = $this->repository->getCityFromLocation($_POST['locality_value']);

			//echo "<pre>"; print_r($vendorLocationCurators[0]);
			echo json_encode($vendorLocationCurators[0]);
		}

		//if($name)
		//echo "<prE>"; print_r($_GET['locality_value']);
	}

}
