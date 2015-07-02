<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Products\ProductLocationsBookingSchedule;
use WowTables\Http\Requests\Admin\CreateExperienceLocationRequest;
use WowTables\Http\Requests\Admin\UpdateExperienceLocationRequest;
use WowTables\Http\Requests\Admin\DeleteExperienceLocationRequest;
use WowTables\Http\Models\ExperienceLocation;
use DB;
use Input;

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
     * Display a listing of the review.
     *
     * @return Response
     */
    public function review()
    {
        $experiences_review_query = DB::select("select `rd`.`id`, `rd`.`user_id`, `rd`.`reservation_status`, `rd`.`reservation_date`, `rd`.`reservation_time`, `rd`.`no_of_persons`,
                                   `products`.`name` as `product_name`, `vendors`.`id` as `vendor_id`, `vendors`.`name` as `vendor_name`,
                                    `rd`.`reservation_type`, `products`.`id` as `product_id`, `rd`.`vendor_location_id`,
                                     `rd`.`product_vendor_location_id`,
                                   `rd`.`special_request`, `rd`.`giftcard_id`, `rd`.`guest_name`, 
                                   `rd`.`guest_name`, `rd`.`guest_email`, `rd`.`guest_phone`, 
                                   `rd`.`points_awarded`, MAX(IF(pa.alias='short_description', pat.attribute_value,'')) AS product_short_description,
                                    MAX(IF(va.alias='short_description', vlat.attribute_value, ''))AS vendor_short_description, `ploc`.`name` as `product_locality`,
                                     `pvla`.`address` as `product_address`, `vloc`.`name` as `vendor_locality`,
                                     `vvla`.`address` as `vendor_address`, `products`.`slug` as `product_slug`, `ploc`.`name` as `city`,
                                       DAYNAME(rd.reservation_date) as dayname,pvl.id as product_vendor_location_id,`vloc1`.name as city_name,`vloc1`.id as city_id, pr.review,pr.id as review_id, pr.status as review_status
                                    from `reservation_details` as `rd` 
                                    inner join product_reviews as pr on pr.reserv_id = rd.id
                                    left join `vendor_locations` as `vl` on `vl`.`id` = `rd`.`vendor_location_id`
                                    left join `product_vendor_locations` as `pvl` on `pvl`.`product_id` = `rd`.`product_id` and pvl.vendor_location_id = `rd`.`vendor_location_id` 
                                    left join `products` on `products`.`id` = `pvl`.`product_id` 
                                    left join `vendors` on `vendors`.`id` = `vl`.`vendor_id` 
                                    left join `product_attributes_text` as `pat` on `pat`.`product_id` = `products`.`id` 
                                    left join `product_attributes` as `pa` on `pa`.`id` = `pat`.`product_attribute_id` 
                                    left join `vendor_location_attributes_text` as `vlat` on `vlat`.`vendor_location_id` = `vl`.`id` 
                                    left join `vendor_attributes` as `va` on `va`.`id` = `vlat`.`vendor_attribute_id` 
                                    left join `vendor_locations` as `vl2` on `vl2`.`id` = `pvl`.`vendor_location_id` 
                                    left join `locations` as `ploc` on `ploc`.`id` = `vl2`.`location_id` 
                                    left join `vendor_location_address` as `pvla` on `pvla`.`vendor_location_id` = `pvl`.`vendor_location_id` 
                                    left join `vendor_location_address` as `vvla` on `vvla`.`vendor_location_id` = `rd`.`vendor_location_id` 
                                    left join `locations` as `vloc` on `vloc`.`id` = `vl`.`location_id`
                                    left join `locations` as `vloc1` on `vloc1`.`id` = vvla.city_id
                                     where`reservation_status` in ('new', 'edited') 
                                    group by `rd`.`id` order by `rd`.`reservation_date` asc, `rd`.`reservation_time` asc");
        //print_r($experiences_review_query);
        $experience_review = array();
        if(!empty($experiences_review_query))
        {
        foreach ($experiences_review_query as $row) {
            $experience_review[] = array('reservid'   => $row->id,
                          'user_id'                   => $row->user_id,
                          'reservation_date'          => $row->reservation_date,
                          'product_name'              => $row->product_name,
                          //'vendor_id'               => $queryResult[0]->vendor_id,
                          'vendor_name'               => $row->vendor_name,
                          'reservation_type'          => $row->reservation_type,
                          'product_id'                => $row->product_id,
                          'vendor_location_id'        => $row->vendor_location_id,
                          'guest_name'                => $row->guest_name,
                          'guest_email'               => $row->guest_email,
                          'product_short_description' => $row->product_short_description,
                          'product_locality'          => $row->product_locality,
                          'vendor_locality'           => $row->vendor_locality,
                          'city_name'                 => $row->city_name,
                          'city_id'                   => $row->city_id,
                          'product_address'           => $row->product_address,
                          'review'                    => $row->review,
                          'review_id'                 => $row->review_id,
                          'review_status'                 => $row->review_status,
                           );
            }
        }
       /* print_r($experience_review);
        exit;*/
        return view('admin.review.experience_review',['experienceReviewDetails'=>$experience_review]);
    }

     /**
     * Display a showExperienceReview of the update experience review.
     *
     * @return Response
     */
    public function showExperienceReview()
    {
        $review_id = Input::get('review_id');

        $check_review = DB::select("select status from product_reviews where id = '$review_id'");
        $status = $check_review[0]->status;
        if($status == "Pending")
        {
            $statusUpdate = "Approved";
        }
        else if($status == "Approved")
        {
            $statusUpdate = "Pending";
        }

        $update = DB::update("update product_reviews set status ='$statusUpdate' where id = $review_id");

        echo '1';

    }

      /**
     * Display a listing of the review.
     *
     * @return Response
     */
    public function reviewAlacarte()
    {

        $alacartReviewQuery = DB::select("select `rd`.`id`, `rd`.`user_id`, `rd`.`reservation_status`, `rd`.`reservation_date`, `rd`.`reservation_time`, `rd`.`no_of_persons`,
                                   `products`.`name` as `product_name`, `vendors`.`id` as `vendor_id`, `vendors`.`name` as `vendor_name`,
                                    `rd`.`reservation_type`, `products`.`id` as `product_id`, `rd`.`vendor_location_id`,
                                     `rd`.`product_vendor_location_id`,
                                   `rd`.`special_request`, `rd`.`giftcard_id`, `rd`.`guest_name`, 
                                   `rd`.`guest_name`, `rd`.`guest_email`, `rd`.`guest_phone`, 
                                   `rd`.`points_awarded`, MAX(IF(pa.alias='short_description', pat.attribute_value,'')) AS product_short_description,
                                    MAX(IF(va.alias='short_description', vlat.attribute_value, ''))AS vendor_short_description, `ploc`.`name` as `product_locality`,
                                     `pvla`.`address` as `product_address`, `vloc`.`name` as `vendor_locality`,
                                     `vvla`.`address` as `vendor_address`, `products`.`slug` as `product_slug`, `ploc`.`name` as `city`,
                                       DAYNAME(rd.reservation_date) as dayname,pvl.id as product_vendor_location_id,`vloc1`.name as city_name,`vloc1`.id as city_id, vlr.review,vlr.id as review_id, vlr.status as review_status
                                    from `reservation_details` as `rd` 
                                    inner join vendor_location_reviews as vlr on vlr.reserv_id = rd.id
                                    left join `vendor_locations` as `vl` on `vl`.`id` = `rd`.`vendor_location_id`
                                    left join `product_vendor_locations` as `pvl` on `pvl`.`product_id` = `rd`.`product_id` and pvl.vendor_location_id = `rd`.`vendor_location_id` 
                                    left join `products` on `products`.`id` = `pvl`.`product_id` 
                                    left join `vendors` on `vendors`.`id` = `vl`.`vendor_id` 
                                    left join `product_attributes_text` as `pat` on `pat`.`product_id` = `products`.`id` 
                                    left join `product_attributes` as `pa` on `pa`.`id` = `pat`.`product_attribute_id` 
                                    left join `vendor_location_attributes_text` as `vlat` on `vlat`.`vendor_location_id` = `vl`.`id` 
                                    left join `vendor_attributes` as `va` on `va`.`id` = `vlat`.`vendor_attribute_id` 
                                    left join `vendor_locations` as `vl2` on `vl2`.`id` = `pvl`.`vendor_location_id` 
                                    left join `locations` as `ploc` on `ploc`.`id` = `vl2`.`location_id` 
                                    left join `vendor_location_address` as `pvla` on `pvla`.`vendor_location_id` = `pvl`.`vendor_location_id` 
                                    left join `vendor_location_address` as `vvla` on `vvla`.`vendor_location_id` = `rd`.`vendor_location_id` 
                                    left join `locations` as `vloc` on `vloc`.`id` = `vl`.`location_id`
                                    left join `locations` as `vloc1` on `vloc1`.`id` = vvla.city_id
                                     where`reservation_status` in ('new', 'edited') 
                                    group by `rd`.`id` order by `rd`.`reservation_date` asc, `rd`.`reservation_time` asc");
        
        $alacart_review = array();

        if(!empty($alacartReviewQuery))
        {
            foreach ($alacartReviewQuery as $row) {
            $alacart_review[] = array('reservid'   => $row->id,
                          'user_id'                   => $row->user_id,
                          'reservation_date'          => $row->reservation_date,
                          'product_name'              => $row->product_name,
                          //'vendor_id'               => $queryResult[0]->vendor_id,
                          'vendor_name'               => $row->vendor_name,
                          'reservation_type'          => $row->reservation_type,
                          'product_id'                => $row->product_id,
                          'vendor_location_id'        => $row->vendor_location_id,
                          'guest_name'                => $row->guest_name,
                          'guest_email'               => $row->guest_email,
                          'product_short_description' => $row->product_short_description,
                          'product_locality'          => $row->product_locality,
                          'vendor_locality'           => $row->vendor_locality,
                          'city_name'                 => $row->city_name,
                          'city_id'                   => $row->city_id,
                          'vendor_address'            => $row->vendor_address,
                          'review'                    => $row->review,
                          'review_id'                 => $row->review_id,
                          'review_status'             => $row->review_status,
                           );
            }
        }
        /*print_r($alacart_review);
        exit;*/

        return view('admin.review.alacarte_review',['alacartReviewDetails'=>$alacart_review]);
    }

    /**
     * Display a showExperienceReview of the update experience review.
     *
     * @return Response
     */
    public function showAlacarteReview()
    {
        $review_id = Input::get('review_id');

        $check_review = DB::select("select status from vendor_location_reviews where id = '$review_id'");
        $status = $check_review[0]->status;
        if($status == "Pending")
        {
            $statusUpdate = "Approved";
        }
        else if($status == "Approved")
        {
            $statusUpdate = "Pending";
        }

        $update = DB::update("update vendor_location_reviews set status ='$statusUpdate' where id = $review_id");

        echo '1';

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
