<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Products\ProductLocationsBookingSchedule;
use WowTables\Http\Requests\Admin\CreateExperienceLocationRequest;
use WowTables\Http\Requests\Admin\UpdateExperienceLocationRequest;
use WowTables\Http\Requests\Admin\DeleteExperienceLocationRequest;
use WowTables\Http\Models\ExperienceLocation;

/**
 * Class AdminExperiencesController
 */

class AdminExperienceLocationsController extends Controller {


    protected $experienceLocation;

    /**
     * The constructor Method
     *
     * @param Request $request
     */
    function __construct(Request $request, ExperienceLocation $experienceLocation)
    {
        $this->middleware('admin.auth');
        $this->request = $request;

        $this->experienceLocation = $experienceLocation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $locations = [];
        $experienceLocationDetails = $this->experienceLocation->getExperienceLocationDetails();


        return view('admin.experiences.locations.index',['experienceLocationDetails'=>$experienceLocationDetails]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('admin.experiences.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store()
    { //CreateExperienceLocationRequest $createExperienceLocationRequest (needs to be checked)
        $input = $this->request->all();
        //dd($input);
        $experienceLocationCreate = $this->experienceLocation->create($input);
       //echo "<pre>"; print_r($experienceLocationCreate); die;
        if($experienceLocationCreate['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }

            flash()->success('The Experience Location has been successfully created.');
            return redirect('admin/experience/locations');
        }else{
            return response()->json([
                'action' => $experienceLocationCreate['action'],
                'message' => $experienceLocationCreate['message']
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show($id)
    {
        return view('admin.experiences.locations.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($id)
    {
        //echo "sad"; die;
        $productLocationDetails = $this->experienceLocation->getLocationsFromProduct($id);

        $productLocationLimits = $this->experienceLocation->populateProductLocationLimits($id);

        $productLocationBlockDates = $this->experienceLocation->populateProductLocationBlockDates($id);

        $productLocationBlockTimeLimits = $this->experienceLocation->populateProductLocationBlockTimeLimits($id);

        $availableSchedules = $this->formatSchedules($this->experienceLocation->available_time_slots('00:00','23:30'))['schedules'];

        $productLocationSchedules = ProductLocationsBookingSchedule::where('product_vendor_location_id',$id)->lists('schedule_id');

        $schedules = array_values($productLocationSchedules);

        $productLocation = array();
        $productID = '';
        $productDescriptiveTitle = '';
        $productStatus = '';
        $productShowStatus = '';
        //echo "<pre>"; print_r($productLocationDetails); die;
        foreach($productLocationDetails as $productLocationDetail){
            $productID = $productLocationDetail->product_id;
            $productDescriptiveTitle = $productLocationDetail->descriptive_title;
            $productStatus = $productLocationDetail->status;
            $productShowStatus = $productLocationDetail->show_status;
            $productLocation[] = $productLocationDetail->vendor_location_id;
        }
        $productLocationsDetails = ['product_id'=>$productID,'experience_location_id'=>$id,'experience_location_descriptive_title'=>$productDescriptiveTitle,'status'=>$productStatus,'show_status'=>$productShowStatus];


        return view('admin.experiences.locations.edit',[
                        'productLocationDetails' => $productLocationsDetails,
                        'productLocations' => $productLocation,
                        'productLocationsLimits' => $productLocationLimits[0],
                        'schedules'=>$schedules,
                        'availableSchedules' => $availableSchedules,
                        'productLocationSchedules' => $productLocationSchedules,
                        'productLocationBlockDates' => $productLocationBlockDates,
                        'productLocationBlockTimeLimits' => $productLocationBlockTimeLimits,
                    ]);
    }

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

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update($id, UpdateExperienceLocationRequest $updateExperienceLocationRequest)
    {
        $input = $this->request->all();
        //echo "id = ".$id." <br/> <pre>"; print_r($input); die;
        //dd($input);
        $experienceLocationUpdate = $this->experienceLocation->update($id, $input);

        if($experienceLocationUpdate['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }

            flash()->success('The Experience Location has been successfully updated.');
            return redirect('admin/experience/locations');
        }else{
            return response()->json([
                'action' => $experienceLocationUpdate['action'],
                'message' => $experienceLocationUpdate['message']
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id, DeleteExperienceLocationRequest $deleteExperienceLocationRequest)
    {
        $experienceLocationDelete = $this->experienceLocation->delete($id);

        if($experienceLocationDelete['status'] === 'success'){
            if($this->request->ajax()) {
                return response()->json(['status' => 'success'], 200);
            }

            flash()->success('The Experience Location has been successfully deleted.');
            return redirect()->route('AdminExperiences');
        }else{
            return response()->json([
                'action' => $experienceLocationDelete['action'],
                'message' => $experienceLocationDelete['message']
            ], 400);
        }
    }

    /**
     *
     *
     */
    public function getVendorLocationsDetails(){
        if(Request::ajax())
        {
            //trying to grab value of textbox here; however it doesn't work
            $userID = Input::post('vendor_id');
            echo $userID;
        }
    }

}
