<?php namespace WowTables\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use WowTables\Http\Requests;
use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Reservations\ReservationDetails;
use Mail;
use WowTables\Http\Models\Eloquent\User;
use Illuminate\Support\Facades\DB;
use WowTables\Core\Repositories\Restaurants\RestaurantLocationsRepository;
use WowTables\Core\Repositories\Experiences\ExperiencesRepository;
use WowTables\Http\Models\Frontend\ExperienceModel;
use WowTables\Http\Controllers\ConciergeApi\ReservationController as RestaurantApp;


class ReservationController extends Controller {

	protected $reservationDetails;
	protected $reservStatuses = array(1=>'new',2=>'edited',3=>'cancelled',4=>'unpaid',5=>'prepaid',6=>'accepted',7=>'rejected',8=>'closed',9=>'no show');

	protected $request;
	/**
	 * The constructor Method
	 *
	 *
	 */
	function __construct(ExperienceModel $expModel,ReservationDetails $reservationDetails, Request $request,RestaurantLocationsRepository $alacarterepository,ExperiencesRepository $repository,RestaurantApp $restaurantapp)
	{
		$this->middleware('admin.auth');
		$this->request = $request;
		$this->reservationDetails = $reservationDetails;
		$this->alacarterepository = $alacarterepository;
		$this->experiencemodel = $expModel;
		$this->repository = $repository;
		$this->restaurantapp = $restaurantapp;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{



		$un_bookings = array();
		$bookings = array();
		$todayBookings = array();
		$postReservation = array();
		$count = 1;

		//unconfirmed bookings
		$statusCancelledNew = DB::select(DB::raw('select * from reservation_status_log having new_reservation_status_id in (1,2,7,3,6,7,8,9) and created_at in (SELECT MAX(created_at) FROM reservation_status_log group by reservation_id)'));
		$reservationIdArr = array();
		foreach($statusCancelledNew as $reservId){
			$reservationIdArr[] = $reservId->reservation_id;
		}

		$reservStatusArr = $this->reservationDetails->getReservationStatus($reservationIdArr,[1,2,7,3,6,7,8,9]);

		//print_r($reservStatusArr);die;

		foreach (ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
					 /*->with(['reservationStatus' => function($query)
							{
								$query->whereIn('reservation_statuses.id',[1,2,7])
									  ->orderBy('reservation_statuses.id','desc')
									  ->select(DB::raw('reservation_statuses.*, user_id'));

					 }])*/
					 ->where('vendor_location_id','!=','0')
					 ->where('vendor_location_id','!=','54')
					 ->whereIn('id',$reservationIdArr)
					 ->where('created_at','>=','2015-10-12 15:20:00')
					 ->where('id','!=','27355')
					 ->orderBy('reservation_details.created_at','desc')->get() as $unconfirmedBookings)
		{
			//print_r($unconfirmedBookings->attributesDatetime->attribute_value);die;
			$booking = new \stdClass();
			$booking->id = $unconfirmedBookings->id;
			//echo "<pre>".print_r($unconfirmedBookings);

			if($unconfirmedBookings->product_id == 0){
				$booking->name = "Classic Reservation";
			} else {
				$booking->name = $unconfirmedBookings->experience->name;
			}
			$booking->cust_name = $unconfirmedBookings->guest_name;
			$booking->restaurant_name = $unconfirmedBookings->vendor_location->vendor->name;
			if(empty($unconfirmedBookings->vendor_location->address->city_name)){
				$booking->city =  "";
			} else {
				$booking->city = $unconfirmedBookings->vendor_location->address->city_name->name;
			}
			//$reservStatus = $unconfirmedBookings->reservationStatus->first();
			//dd($reservStatus->status);
			$booking->email = $unconfirmedBookings->guest_email;
			$booking->phone_no = $unconfirmedBookings->guest_phone;
			$booking->no_of_persons = $unconfirmedBookings->no_of_persons;
			//$booking->status = $reservStatus->status;
			$userModel = User::find($unconfirmedBookings->user_id);
			$booking->lastmodified = $userModel->role->name;
			$booking->user_id = $unconfirmedBookings->user_id;

			$statusArr = $this->reservStatuses;
			$statusKey = array_search($reservStatusArr[$unconfirmedBookings->id],$statusArr);
			//echo $statusKey."<br/>";
			if($statusKey != -1){
				unset($statusArr[$statusKey]);
			}
			//echo $reservStatusArr[$unconfirmedBookings->id];
			$booking->reserv_status = $reservStatusArr[$unconfirmedBookings->id][0];
			$booking->reservation_status_id = $reservStatusArr[$unconfirmedBookings->id][1];
			$booking->statusArr = $statusArr;
			//echo $reservStatusArr[$unconfirmedBookings->id][0];
			//if($reservStatusArr[$unconfirmedBookings->id][1] == 3){
			if(array_search($reservStatusArr[$unconfirmedBookings->id][1],array(3,6,7,8,9)) != -1){
				$booking->zoho_update = 1;
			} else {
				$booking->zoho_update = 0;
			}

			$reservationDetailsAttr = $this->reservationDetails->getByReservationId($unconfirmedBookings->id);
			$booking->special_request = (isset($reservationDetailsAttr['attributes']['special_request']) ? $reservationDetailsAttr['attributes']['special_request'] : "");
			$booking->gift_card_id = (isset($reservationDetailsAttr['attributes']['gift_card_id_reserv']) ? $reservationDetailsAttr['attributes']['gift_card_id_reserv'] : "");
			$booking->outlet = (isset($reservationDetailsAttr['attributes']['outlet']) ? $reservationDetailsAttr['attributes']['outlet'] : "");
			$booking->reserv_type = $reservationDetailsAttr['attributes']['reserv_type'];

			$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$reservationDetailsAttr['attributes']['reserv_datetime']);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');
			//echo $unconfirmedBookings->id."<pre>".print_r($reservationDetailsAttr['attributes']['zoho_booking_cancelled']);
			//echo $unconfirmedBookings->id;
			if(!isset($reservationDetailsAttr['attributes']['zoho_booking_update'])){
				$un_bookings[$count] = $booking;
			}
			//$un_bookings[$count] = $booking;
			//print_r($booking);die;

			$count++;


		}
		//die;
		//post reservation bookings
		$statusCancelledNew = DB::select(DB::raw('select * from reservation_status_log having new_reservation_status_id in (6) and created_at in (SELECT MAX(created_at) FROM reservation_status_log group by reservation_id)'));
		$reservationIdArr = array();
		foreach($statusCancelledNew as $reservId){
			$reservationIdArr[] = $reservId->reservation_id;
		}

		$reservStatusArr = $this->reservationDetails->getReservationStatus($reservationIdArr,[6]);
		//print_r($reservStatusArr);die;

		foreach (ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
					 /*->with(['reservationStatus' => function($query)
							{
								$query->whereIn('reservation_statuses.id',[1,2,7])
									  ->orderBy('reservation_statuses.id','desc')
									  ->select(DB::raw('reservation_statuses.*, user_id'));

					 }])*/
					 ->where('vendor_location_id','!=','0')
					 ->where('vendor_location_id','!=','54')
					 ->whereIn('id',$reservationIdArr)
					 ->where('reservation_date','=',Carbon::yesterday()->format('Y-m-d'))
					 ->where('created_at','>=','2015-10-12 15:20:00')
					 ->orderBy('reservation_details.created_at','desc')->get() as $postBookings)
		{
			//print_r($unconfirmedBookings->attributesDatetime->attribute_value);die;
			$booking = new \stdClass();
			$booking->id = $postBookings->id;
			/*$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$postBookings->attributesDatetime->attribute_value);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');*/
			if($postBookings->product_id == 0){
				$booking->name = "Classic Reservation";
			} else {
				$booking->name = $postBookings->experience->name;
			}
			$booking->cust_name = $postBookings->guest_name;
			$booking->restaurant_name = $postBookings->vendor_location->vendor->name;
			if(empty($postBookings->vendor_location->address->city_name)){
				$booking->city =  "";
			} else {
				$booking->city = $postBookings->vendor_location->address->city_name->name;
			}
			//$reservStatus = $unconfirmedBookings->reservationStatus->first();
			//dd($reservStatus->status);
			$booking->email = $postBookings->guest_email;
			$booking->phone_no = $postBookings->guest_phone;
			$booking->no_of_persons = $postBookings->no_of_persons;
			//$booking->status = $reservStatus->status;
			$userModel = User::find($postBookings->user_id);
			$booking->lastmodified = $userModel->role->name;
			$booking->user_id = $postBookings->user_id;

			$statusArr = $this->reservStatuses;
			$statusKey = array_search($reservStatusArr[$postBookings->id],$statusArr);
			//echo $statusKey."<br/>";
			if($statusKey != -1){
				unset($statusArr[$statusKey]);
			}
			//echo $reservStatusArr[$unconfirmedBookings->id];
			$booking->reserv_status = $reservStatusArr[$postBookings->id][0];
			$booking->reservation_status_id = $reservStatusArr[$postBookings->id][1];
			$booking->statusArr = $statusArr;

			if($reservStatusArr[$postBookings->id][1] == 6){
				$booking->zoho_update = 1;
			} else {
				$booking->zoho_update = 0;
			}

			$reservationDetailsAttr = $this->reservationDetails->getByReservationId($postBookings->id);
			$booking->special_request = (isset($reservationDetailsAttr['attributes']['special_request']) ? $reservationDetailsAttr['attributes']['special_request'] : "");
			$booking->gift_card_id = (isset($reservationDetailsAttr['attributes']['gift_card_id_reserv']) ? $reservationDetailsAttr['attributes']['gift_card_id_reserv'] : "");
			$booking->outlet = (isset($reservationDetailsAttr['attributes']['outlet']) ? $reservationDetailsAttr['attributes']['outlet'] : "");
			$booking->reserv_type = $reservationDetailsAttr['attributes']['reserv_type'];

			$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$reservationDetailsAttr['attributes']['reserv_datetime']);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');
			//print_r($booking);die;
			$postReservation[$count] = $booking;
			$count++;


		}




		$statusCancelledNew = DB::select(DB::raw('select * from reservation_status_log having new_reservation_status_id in (1,2,3,4,5,6,7,8) and created_at in (SELECT MAX(created_at) FROM reservation_status_log group by reservation_id)'));
		$reservationIdArr = array();
		foreach($statusCancelledNew as $reservId){
			$reservationIdArr[] = $reservId->reservation_id;
		}
		$reservStatusArr = $this->reservationDetails->getReservationStatus($reservationIdArr,[1,2,3,4,5,6,7,8]);
		foreach (ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
					 /*->with(['reservationStatus' => function($query)
					 {
						 $query->whereIn('status',[3,8,6])
							 ->orderBy('reservation_statuses.id','desc')
							 ->select(DB::raw('reservation_statuses.*, user_id'));

					 }])*/
			         ->with(['attributesInteger' => function($query){
						 $query->where('reservation_attribute_id',function($q1){
							 		$q1->select('id')
									   ->from('reservation_attributes')
									   ->where('alias','=','order_completed');
						 });

					 }])
					 ->where('vendor_location_id','!=','0')
					 ->where('vendor_location_id','!=','54')
					 ->whereIn('id',$reservationIdArr)
					 ->where('created_at','>=','2015-10-12 15:20:00')
					 ->orderBy('created_at','desc')->get() as $allbookings)
		{


			$booking = new \stdClass();
			$booking->id = $allbookings->id;
			//print_r($allbookings);
			//echo "<br/><br/>";
			/*$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$allbookings->attributesDatetime->attribute_value);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');*/
			if($allbookings->product_id == 0){
				$booking->name = "Classic Reservation";
			} else {
				$booking->name = $allbookings->experience->name;
			}
			$booking->cust_name = $allbookings->guest_name;
			$booking->restaurant_name = $allbookings->vendor_location->vendor->name;
			if(empty($allbookings->vendor_location->address->city_name)){
				$booking->city =  "";
			} else {
				$booking->city = $allbookings->vendor_location->address->city_name->name;
			}
			//$reservStatus = $allbookings->reservationStatus->first();
			//dd($reservStatus);
			$booking->email = $allbookings->guest_email;
			$booking->phone_no = $allbookings->guest_phone;
			$booking->no_of_persons = $allbookings->no_of_persons;
			//$booking->status = $reservStatus->status;
			$userModel = User::find($allbookings->user_id);
			$booking->lastmodified = $userModel->role->name;
			$booking->user_id = $allbookings->user_id;

			if(!$allbookings->attributesInteger->isEmpty()){
				$booking->order_completed = 1;
			} else {
				$booking->order_completed = 0;
			}

			$statusArr = $this->reservStatuses;
			$statusKey = array_search($reservStatusArr[$allbookings->id],$statusArr);
			if($statusKey != -1){
				unset($statusArr[$statusKey]);
			}
			$booking->reserv_status = $reservStatusArr[$allbookings->id][0];
			$booking->statusArr = $statusArr;

			$reservationDetailsAttr = $this->reservationDetails->getByReservationId($allbookings->id);
			$booking->special_request = (isset($reservationDetailsAttr['attributes']['special_request']) ? $reservationDetailsAttr['attributes']['special_request'] : "");
			$booking->gift_card_id = (isset($reservationDetailsAttr['attributes']['gift_card_id_reserv']) ? $reservationDetailsAttr['attributes']['gift_card_id_reserv'] : "");
			$booking->outlet = (isset($reservationDetailsAttr['attributes']['outlet']) ? $reservationDetailsAttr['attributes']['outlet'] : "");
			$booking->reserv_type = $reservationDetailsAttr['attributes']['reserv_type'];
			$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$reservationDetailsAttr['attributes']['reserv_datetime']);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');
			$bookings[$count] = $booking;
			$count++;

			//var_dump();


		}
		//die;
		//today's bookings

		$statusCancelledNew = DB::select(DB::raw('select rd.id as id from reservation_details as rd left join reservation_attributes_date as rad on rd.id = rad.reservation_id where DATE(rad.attribute_value) = \''.Carbon::now()->format('Y-m-d').'\''));
		$reservationIdArr = array();
		foreach($statusCancelledNew as $reservId){
			$reservationIdArr[] = $reservId->id;
		}
		$reservStatusArr = $this->reservationDetails->getReservationStatus($reservationIdArr,[1,2,3,6,7,8]);
		foreach (ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
					 /*->with(['reservationStatus' => function($query)
					 {
						 $query->whereIn('status',[1,2,3,6,7,8])
							 ->orderBy('reservation_statuses.id','desc')
							 ->select(DB::raw('reservation_statuses.*, user_id'));

					 }])*/
					 ->where('vendor_location_id','!=','0')
					 ->where('vendor_location_id','!=','54')
					 ->whereIn('id',$reservationIdArr)
					 //->whereRaw("reservation_date = '".date('Y-m-d')."'")
					 ->where('created_at','>=','2015-10-12 15:20:00')
					 ->orderBy('created_at','desc')->get() as $today)
		{


			$booking = new \stdClass();
			$booking->id = $today->id;
			/*$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$today->attributesDatetime->attribute_value);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');*/
			if($today->product_id == 0){
				$booking->name = "Classic Reservation";
			} else {
				$booking->name = $today->experience->name;
			}
			$booking->cust_name = $today->guest_name;
			$booking->restaurant_name = $today->vendor_location->vendor->name;
			if(empty($today->vendor_location->address->city_name)){
				$booking->city =  "";
			} else {
				$booking->city = $today->vendor_location->address->city_name->name;
			}
			//$reservStatus = $today->reservationStatus->first();
			//dd($reservStatus);
			$booking->email = $today->guest_email;
			$booking->phone_no = $today->guest_phone;
			$booking->no_of_persons = $today->no_of_persons;
			//$booking->status = $reservStatus->status;
			$userModel = User::find($today->user_id);
			$booking->lastmodified = $userModel->role->name;
			$booking->user_id = $today->user_id;

			$statusArr = $this->reservStatuses;
			$statusKey = array_search($reservStatusArr[$today->id],$statusArr);
			if($statusKey != -1){
				unset($statusArr[$statusKey]);
			}
			$booking->reserv_status = $reservStatusArr[$today->id][0];
			$booking->statusArr = $statusArr;

			$reservationDetailsAttr = $this->reservationDetails->getByReservationId($today->id);
			$booking->special_request = (isset($reservationDetailsAttr['attributes']['special_request']) ? $reservationDetailsAttr['attributes']['special_request'] : "");
			$booking->gift_card_id = (isset($reservationDetailsAttr['attributes']['gift_card_id_reserv']) ? $reservationDetailsAttr['attributes']['gift_card_id_reserv'] : "");
			$booking->outlet = (isset($reservationDetailsAttr['attributes']['outlet']) ? $reservationDetailsAttr['attributes']['outlet'] : "");
			$booking->reserv_type = $reservationDetailsAttr['attributes']['reserv_type'];
			$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$reservationDetailsAttr['attributes']['reserv_datetime']);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');
			$todayBookings[$count] = $booking;
			$count++;

			//print_r($today);
		}


		//die;

		return view('admin.bookings.index')->with('un_bookings',$un_bookings)
										   ->with('post_bookings',$postReservation)
										   ->with('bookings',$bookings)
			 					           ->with('todaysbookings',$todayBookings);
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
		//echo $id;die;
		//$status = DB::select(DB::raw('select reservation_statuses.status1 from reservation_status_log left join reservation_statuses on reservation_status_log.new_reservation_status_id = reservation_statuses.id where reservation_statuses.status in (\'Cancelled\', \'Closed\',\'New\',\'Changed\',\'Confirmed\') and reservation_status_log.new_reservation_status_id in (select max(new_reservation_status_id) from reservation_status_log where reservation_id='.$id.' group by reservation_id) and reservation_id='.$id));
		//$reservationStatus = $status[0]->status;
		$reservationDetailsAttr = $this->reservationDetails->getByReservationId($id);
		$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$reservationDetailsAttr['attributes']['reserv_datetime']);
		$reservationDetailsAttr['attributes']['date'] = $reservCarbonDate->format('d/m/Y');
		$reservationDetailsAttr['attributes']['time'] = $reservCarbonDate->format('h:i A');

		$reservationDetails = ReservationDetails::find($id);
		//print_r($reservationDetailsAttr);die;
		$pricingDetails = new \stdClass();
		if($reservationDetailsAttr['attributes']['reserv_type'] == "Alacarte"){

			$priceDetails = $this->alacarterepository->getByRestaurantLocationId($reservationDetails->vendor_location_id);
			$pricingDetails->commission = $priceDetails['attributes']['commission_per_cover'];
			if(isset($reservationDetailsAttr['attributes']['total_seated'])){
				$pricingDetails->total_commission = $reservationDetailsAttr['attributes']['total_seated'] * $priceDetails['attributes']['commission_per_cover'];
			} else {
				$pricingDetails->total_commission = 0 * $priceDetails['attributes']['commission_per_cover'];
			}

		} else {

			$priceDetails = $this->repository->populateProductPricing($reservationDetails->product_id);
			//print_r($priceDetails);die;
			$pricingDetails->pre_tax_price = $priceDetails[0]->price;
			$pricingDetails->post_tax_price = $priceDetails[0]->post_tax_price;
			$pricingDetails->commission = $priceDetails[0]->commission;
			$pricingDetails->commission_on = $priceDetails[0]->commission_on;
			$pricingDetails->total_commission = 0 * $priceDetails[0]->commission;
			$pricingDetails->total_billing = 0 * $priceDetails[0]->post_tax_price;
			/*if(isset($reservationDetailsAttr['attributes']['total_seated'])){
				$pricingDetails->total_commission = $reservationDetailsAttr['attributes']['total_seated'] * $priceDetails[0]->commission;
			} else {
				$pricingDetails->total_commission = 0 * $priceDetails[0]->commission;
			}
			if(isset($reservationDetailsAttr['attributes']['total_seated'])){
				$pricingDetails->total_billing = $reservationDetailsAttr['attributes']['total_seated'] * $priceDetails[0]->post_tax_price;
			} else {
				$pricingDetails->total_billing = 0 * $priceDetails[0]->post_tax_price;
			}*/

			$expAddOns = $this->repository->populateProductAddOns($reservationDetails->product_id);
			//print_r($expAddOns);die;
			$expAddOnsMultiple = $this->experiencemodel->getReservationAddonDetails($id);
			$finalAddonArr = array();
			$addonNames = array();
			foreach($expAddOnsMultiple as $ea){
				$addOnDetails = new \stdClass();
				$addOnDetails->pre_tax_price = $expAddOns[$ea->options_id]['price'];
				$addOnDetails->post_tax_price = $expAddOns[$ea->options_id]['post_tax_price'];
				$addOnDetails->commission = $expAddOns[$ea->options_id]['commission'];
				$addOnDetails->commission_on = $expAddOns[$ea->options_id]['commission_on'];
				$addOnDetails->no_of_people_addon = $ea->no_of_persons;
				$addOnDetails->short_description = $expAddOns[$ea->options_id]['short_description'];
				$addonsDetails = DB::select("SELECT attribute_value from product_attributes_text where product_id = $ea->options_id and product_attribute_id = 17");
				$addOnDetails->title = $addonsDetails[0]->attribute_value;
				//print_r($addOnDetails->title);die;
				$addonNames[$ea->options_id][] = $addonsDetails[0]->attribute_value;
				$addonNames[$ea->options_id][] = $ea->no_of_persons;
				$finalAddonArr[] = $addOnDetails;
			}
			$pricingDetails->addon = $finalAddonArr;
			$pricingDetails->addon_names = $addonNames;
		}

		//print_r($expAddOns);die;

		//$vendorAttributes = $this->alacarterepository->getByRestaurantLocationId($reservationDetails->vendor_location_id);
		/*$statusArr = $this->reservStatuses;
		$statusKey = array_search($reservationStatus,$statusArr);
		if($statusKey != -1){
			unset($statusArr[$statusKey]);
		}*/
		$bookingType = ["Alacarte"=>"Alacarte","Experience"=>"Experience"];

		return view('admin.bookings.edit')->with('reservation_id',$id)
										  ->with('reservationDetailsAttr',$reservationDetailsAttr)
										  ->with('pricingDetails',$pricingDetails)
										  ->with('bookingTypeArr',$bookingType);
										  /*->with('finalAddonArr',$finalAddonArr)*/;
										  //->with('status',$reservationStatus)
										  //->with('statusArr',$statusArr);
										  //->with('vendorAttributes',$vendorAttributes)
						                  //->with('experienceAttributes',$experienceAttributes);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = $this->request->all();
		//var_dump($input);die;
		$bookingUpdate = $this->reservationDetails->updateAttributes($id, $input);

		if($bookingUpdate['status'] === 'success'){
			if($this->request->ajax()) {
				return response()->json(['status' => 'success'], 200);
			}
			flash()->success('The booking has been successfully updated.');
			return redirect()->route('BookingList');
		}else{
			return response()->json([
				'action' => $bookingUpdate['action'],
				'message' => $bookingUpdate['message']
			], 400);
		}
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


	public function cancelReservationRestaurant($id){

		$reservation_id = $id;
		//var_dump($reservation_id);die;
		$reservationDetails = ReservationDetails::with('vendor_location_contacts')->where('id','=',$reservation_id)->get();
		$reservationAttrs = $this->reservationDetails->getByReservationId($reservation_id);
		$vendor_email = array();
		if(isset($reservationDetails[0]->vendor_location_contacts[0]->email)) {
			foreach($reservationDetails[0]->vendor_location_contacts as $vendorEmail){
				$vendor_email[] = $vendorEmail->email;
			}
			$data['cust_name'] = $reservationAttrs['attributes']['cust_name'];
			$data['no_of_people'] = $reservationAttrs['attributes']['no_of_people_booked'];
			$dateObject = Carbon::createFromFormat('Y-m-d H:i:s',$reservationAttrs['attributes']['reserv_datetime']);
			$data['date'] = $dateObject->format('d/m/y');
			$data['time'] = $dateObject->format('h:i A');
			//$vendor_email = 'durgesh@wowtables.com';
			Mail::send('admin.bookings.emails.cancel', [
				'data' => $data,
			], function ($message) use ($vendor_email) {
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');
				$message->to($vendor_email)->subject('Booking Cancelled');
				$message->cc('concierge@wowtables.com');
			});
			flash()->success('Cancellation Email has been send to restaurant');
			return redirect()->route('BookingList');
		} else {
			flash()->error('Contact of restaurant location not present');
			return redirect()->route('BookingList');
		}
	}


	public function changeReservationRestaurant($id){

		$reservation_id = $id;
		//var_dump($reservation_id);die;
		$reservationDetails = ReservationDetails::with('vendor_location_contacts')->where('id','=',$reservation_id)->get();
		$reservationAttrs = $this->reservationDetails->getByReservationId($reservation_id);
		$vendor_email = array();
		if(isset($reservationDetails[0]->vendor_location_contacts[0]->email)) {
			foreach($reservationDetails[0]->vendor_location_contacts as $vendorEmail){
				$vendor_email[] = $vendorEmail->email;
			}
			$data['cust_name'] = $reservationAttrs['attributes']['cust_name'];
			$data['contact'] = $reservationAttrs['attributes']['contact_no'];
			$data['email'] = $reservationAttrs['attributes']['email'];
			$data['no_of_people'] = $reservationAttrs['attributes']['no_of_people_booked'];
			$dateObject = Carbon::createFromFormat('Y-m-d H:i:s',$reservationAttrs['attributes']['reserv_datetime']);
			$data['date'] = $dateObject->format('d/m/y');
			$data['time'] = $dateObject->format('h:i A');
			$data['outlet'] = $reservationAttrs['attributes']['outlet'];
			$data['experience'] = $reservationAttrs['attributes']['experience'];
			$data['special_request'] = isset($reservationAttrs['attributes']['special_request']) ? $reservationAttrs['attributes']['special_request'] : "";
			if(isset($reservationAttrs['attributes']['gift_card_id']) && $reservationAttrs['attributes']['reserv_type'] == "Experience" ){
				//$data['end_text'] = "";
			} else {
				$data['end_text'] = "This experience will be post-paid by the customer. Please bill the customer for the WowTables experience bill in the restaurant.";
			}

			//print_r($data);die;
			//$vendor_email = 'durgesh@wowtables.com';
			Mail::send('admin.bookings.emails.change', [
				'data' => $data,
			], function ($message) use ($vendor_email) {
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');
				$message->to($vendor_email)->subject('Change in existing reservation');
				$message->cc('concierge@wowtables.com');
			});
			//die;
			flash()->success('Change Email has been send to restaurant');
			return redirect()->route('BookingList');
		} else {
			flash()->error('Contact of restaurant location not present');
			return redirect()->route('BookingList');
		}

	}

	public function sendReservationRestaurant($id){

		$reservation_id = $id;
		//var_dump($reservation_id);die;
		$reservationDetails = ReservationDetails::with('vendor_location_contacts')->where('id','=',$reservation_id)->get();
		//print_r($reservationDetails);die;
		$reservationAttrs = $this->reservationDetails->getByReservationId($reservation_id);
		$vendor_email = array();
		//print_r($reservationDetails[0]->vendor_location_contacts[0]);die;

		if(isset($reservationDetails[0]->vendor_location_contacts[0]->email)){

			foreach($reservationDetails[0]->vendor_location_contacts as $vendorEmail){
				$vendor_email[] = $vendorEmail->email;
			}


			//print_r($vendor_email);die;
			$data['cust_name'] = $reservationAttrs['attributes']['cust_name'];
			$data['contact'] = $reservationAttrs['attributes']['contact_no'];
			$data['email'] = $reservationAttrs['attributes']['email'];
			$data['no_of_people'] = $reservationAttrs['attributes']['no_of_people_booked'];
			$dateObject = Carbon::createFromFormat('Y-m-d H:i:s',$reservationAttrs['attributes']['reserv_datetime']);
			$data['date'] = $dateObject->format('d/m/y');
			$data['time'] = $dateObject->format('h:i A');
			$data['outlet'] = $reservationAttrs['attributes']['outlet'];
			$data['experience'] = $reservationAttrs['attributes']['experience'];
			$data['special_request'] = isset($reservationAttrs['attributes']['special_request']) ? $reservationAttrs['attributes']['special_request'] : "";

			if(isset($reservationAttrs['attributes']['gift_card_id']) && $reservationAttrs['attributes']['reserv_type'] == "Experience"){
				//$data['end_text'] = "";
			} else {
				$data['end_text'] = "This experience will be post-paid by the customer. Please bill the customer for the WowTables experience bill in the restaurant.";
			}

			//print_r($data);die;
			$vendor_name = explode('-',$data['experience']);
			//$vendor_email = 'durgesh@wowtables.com';

			Mail::send('admin.bookings.emails.restaurant',[
				'data' =>$data,
			], function($message) use ($vendor_email,$vendor_name){
				$message->from('concierge@wowtables.com','WowTables by GourmetItUp');
				$message->to($vendor_email)->subject($vendor_name[0].' New Reservations');
				$message->cc('concierge@wowtables.com');
			});
			//die;
			flash()->success('Reservation Information Email has been send to restaurant');
			return redirect()->route('BookingList');
		} else {
			flash()->error('Contact of restaurant location not present');
			return redirect()->route('BookingList');
		}


	}

	public function sendCustomerConfirmation($id){

		$reservation_id = $id;
		//var_dump($reservation_id);die;
		/*->with(['reservationStatus' => function($query)
							{
								$query->whereIn('reservation_statuses.id',[1,2,7])
									  ->orderBy('reservation_statuses.id','desc')
									  ->select(DB::raw('reservation_statuses.*, user_id'));

					 }])*/
		$reservationDetails = ReservationDetails::with('vendor_location')->with(['attributesText' => function($query)
		{
			$query->where('reservation_attribute_id',5);

		}])->where('id','=',$reservation_id)->get();


		$reservationAttrs = $this->reservationDetails->getByReservationId($reservation_id);
		//print_r($reservationDetails);die;
		//print_r($reservationDetails[0]->vendor_location->vendor->name);die;
		$cust_email = $reservationDetails[0]->attributesText[0]->attribute_value;
		//print_r($cust_email);die;
		$data['cust_name'] = $reservationAttrs['attributes']['cust_name'];
		$data['contact'] = $reservationAttrs['attributes']['contact_no'];
		$data['email'] = $reservationAttrs['attributes']['email'];
		$data['no_of_people'] = $reservationAttrs['attributes']['no_of_people_booked'];
		$dateObject = Carbon::createFromFormat('Y-m-d H:i:s',$reservationAttrs['attributes']['reserv_datetime']);
		$data['date'] = $dateObject->format('d/m/y');
		$data['time'] = $dateObject->format('h:i A');
		//$data['time'] = $reservationAttrs['attributes']['time'];
		$data['outlet'] = $reservationAttrs['attributes']['outlet'];
		$data['experience'] = $reservationAttrs['attributes']['experience'];
		$data['special_request'] = isset($reservationAttrs['attributes']['special_request']) ? $reservationAttrs['attributes']['special_request'] : "";
		$data['booking_type'] = $reservationAttrs['attributes']['reserv_type'];
		$temp = explode("-",$reservationAttrs['attributes']['experience']);
		$data['restaurant_name'] = $temp[0];

		//print_r($data);die;
		//$vendor_name = explode('-',$data['experience']);

		Mail::send('admin.bookings.emails.customer',[
			'data' =>$data,
		], function($message) use ($cust_email) {
			$message->from('concierge@wowtables.com','WowTables by GourmetItUp');
			$message->to($cust_email)->subject('Your WowTables reservation is confirmed');
			$message->cc('concierge@wowtables.com');
		});
		//die;
		$smsStatus = $this->smsconfirmation($data);
		flash()->success('Reservation Confirmation has been send to customer');
		return redirect()->route('BookingList');

	}

	protected function smsconfirmation($data) {


		$smsBody = "Your reservation for the WowTables experience at ".$data['restaurant_name'].",". $data['outlet'] . " is confirmed for ".$data['no_of_people']." guests at ".$data['time']." on ".$data['date'].". Enjoy your meal. Regards, WowTables";
		if ($data['booking_type']  ==  "Alacarte")
		{
			$smsBody = "Your A la Carte reservation at ".$data['outlet']." is confirmed for ".$data['no_of_people']." guests at ".$data['time']." on ".$data['date'].". Enjoy your meal. Regards, WowTables";
		}
		$post_data = array(
			// 'From' doesn't matter; For transactional, this will be replaced with your SenderId;
			// For promotional, this will be ignored by the SMS gateway
			'From'   => '02230987935',
			'To'    => $data['contact'],
			'Body'  => $smsBody,
		);

		$exotel_sid = "enthrallmedia"; // Your Exotel SID - Get it from here: http://my.exotel.in/Exotel/settings/site#api-settings
		$exotel_token = "8f06e27a1e92198db28dd3b01b747efaf10354c6"; // Your exotel token - Get it from here: http://my.exotel.in/Exotel/settings/site#api-settings

		$url = "https://".$exotel_sid.":".$exotel_token."@twilix.exotel.in/v1/Accounts/".$exotel_sid."/Sms/send";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));

		$http_result = curl_exec($ch);
		$error = curl_error($ch);
		$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);

		curl_close($ch);
		return "success";
	}

	protected function changeStatus(){

		$reservation_id = $this->request->get('reserv_id');
		$data['user_id'] = Auth::user()->id;
		$data['status'] = $this->request->get('reserv_status');
		$data['reserv_type'] =  $this->request->get('reserv_type');
		$data['attributes'] = $this->request->get('attributes');
		$data['attributes']['reservation_status_id'] = (int)$data['status'];

		//echo $data['attributes']['reservation_status_id'];die;

		//echo $data['attributes']['admin_comments'];die;
		//if(!empty($data['attributes']['admin_comments'])){
			$bookingUpdate = $this->reservationDetails->updateAttributes($reservation_id, $data);
		//}

		$reservationStatus = $this->reservationDetails->changeReservationStatus($reservation_id,$data);
		//print_r($data);die;
		//$bookingUpdate = $this->reservationDetails->updateAttributes($reservation_id, $data);
		//$reservationStatus = $this->reservationDetails->changeReservationStatus($reservation_id,$data);

		/*if($bookingUpdate['status'] === 'success'){
			if($this->request->ajax()) {
				return response()->json(['status' => 'success'], 200);
			}
			flash()->success('Reservation Status Changed.');
			return redirect()->route('BookingList');
		}else{
			return response()->json([
				'action' => $bookingUpdate['action'],
				'message' => $bookingUpdate['message']
			], 400);
		}*/

		flash()->success('Reservation Status Changed.');
		return redirect()->route('BookingList');
		//flash()->success('Reservation Status Changed');
		//return redirect()->route('BookingList');
		/*$reservationDetails = ReservationDetails::with('vendor_location_contacts')->where('id','=',$reservation_id)->get();
		$reservationAttrs = $this->reservationDetails->getByReservationId($reservation_id);*/
	}

	protected function orderCompleted($id,$status){
		$reservation_id = $id;
		if($status == 1){
			$newDb['attributes']['order_completed'] = 1;
		} else {
			$newDb['attributes']['order_completed'] = "";
		}
		$newDbStatus = $this->reservationDetails->updateAttributes($reservation_id,$newDb);
		echo $newDbStatus['status'];
		die;
	}

	public function updateBilling(){

		$totalBooked = $this->request->get('bookedno');
		$totalExpTakers = $this->request->get('expnos');
		$totalAlacarteTakers = $this->request->get('alano');
		//print_r($this->request->get('addoninfo'));die;
		$totalAddonTakers = $this->request->get('addoninfo');
		$reservId = $this->request->get('reservid');
		$reservationDetails = ReservationDetails::find($reservId);

		//total of everything
		$totalCommission = 0;
		$totalBilling = 0;

		//alacarte info
		if($totalAlacarteTakers != 0){
			$priceDetails = $this->alacarterepository->getByRestaurantLocationId($reservationDetails->vendor_location_id);
			if(isset($priceDetails['attributes']['commission_per_cover'])){
				$totalCommission += $totalAlacarteTakers * $priceDetails['attributes']['commission_per_cover'];
			} else {
				$totalCommission += $totalAlacarteTakers * 0;
			}
		}

		//experiences info
		if($totalExpTakers != 0) {
			$priceDetails = $this->repository->populateProductPricing($reservationDetails->product_id);
			$totalCommission += $totalExpTakers * $priceDetails[0]->commission;
			$totalBilling += $totalExpTakers * $priceDetails[0]->post_tax_price;
		}

		//addon information
		if(!empty($totalAddonTakers)) {
			$expAddOns = $this->repository->populateProductAddOns($reservationDetails->product_id);
			$expAddOnsMultiple = $this->experiencemodel->getReservationAddonDetails($reservId);
			foreach($expAddOnsMultiple as $ea){
				$totalBilling += $totalAddonTakers[$ea->options_id] * $expAddOns[$ea->options_id]['post_tax_price'];
				$totalCommission += $totalAddonTakers[$ea->options_id] * $expAddOns[$ea->options_id]['commission'];
			}
			$data['attributes']['actual_addon_takers'] = $totalAddonTakers;
			$updatActualAddonTakers = $this->reservationDetails->updateAttributes($reservId,$data);
		}

		$pricing = new \stdClass();
		$pricing->total_commission = $totalCommission;
		$pricing->total_billing = $totalBilling;
		$pricing->status = "success";
		echo json_encode($pricing);
		die;

	}

	public function changeStatusBookingCancelled($id,$reservtype,$statusid){
		$reservation_id = (int)$id;
		$reservType = $reservtype;
		$statusId = (int)$statusid;
		/*$zoho_data = array(
			'Order_completed'=>'booking cancelled',
			'Total_Seated'=>'0',
			'Actual_attendees'=>'0'
		);
		if($reservType == "Experience"){
			$this->reservationDetails->changeStatusInZoho('E'.sprintf("%06d",$reservation_id),$zoho_data);
		} else if($reservType == "Alacarte") {
			$this->reservationDetails->changeStatusInZoho('A'.sprintf("%06d",$reservation_id),$zoho_data);
		}
		//print_r($this->reservationDetails);die;
		//$this->reservationDetails->changeStatusInZoho($reservation_id,$zoho_data);


		$data['attributes']['zoho_booking_cancelled'] = "yes";
		$bookingUpdate = $this->reservationDetails->updateAttributes($reservation_id, $data);*/
		$reservationDetailsAttr = $this->reservationDetails->getByReservationId($reservation_id);

		switch ($statusId) {
			case 2:
				//for edited status
				//prepare the array for sending status to zoho
				$zoho_data = array(
					'Order_completed' => 'User Changed',
				);
				if ($reservType == "Experience") {
					$this->reservationDetails->changeStatusInZoho('E' . sprintf("%06d", $reservation_id), $zoho_data);
				} else if ($reservType == "Alacarte") {
					$this->reservationDetails->changeStatusInZoho('A' . sprintf("%06d", $reservation_id), $zoho_data);
				}


				break;
			case 3:
				//for cancelled status
				$zoho_data = array(
					'Order_completed'=>'booking cancelled',
					'Total_Seated'=>'0',
					'Actual_attendees'=>'0'
				);
				if ($reservType == "Experience") {
					$this->reservationDetails->changeStatusInZoho('E' . sprintf("%06d", $reservation_id), $zoho_data);
				} else if ($reservType == "Alacarte") {
					$this->reservationDetails->changeStatusInZoho('A' . sprintf("%06d", $reservation_id), $zoho_data);
				}
				$data['attributes']['total_seated'] = 0;
				$data['attributes']['actual_experience_takers'] = 0;
				break;
			case 6:
				//for accepted status
				$adminComments = DB::table('reservation_attributes_text')->where('reservation_id',$reservation_id)->where('reservation_attribute_id',17)->select('attribute_value')->first();;
				$zoho_data = array(
					'Order_completed' => 'Confirmed with rest & customer',
					'Satisfaction' => $adminComments->attribute_value
				);
				if ($reservType == "Experience") {
					$this->reservationDetails->changeStatusInZoho('E' . sprintf("%06d", $reservation_id), $zoho_data);
				} else if ($reservType == "Alacarte") {
					$this->reservationDetails->changeStatusInZoho('A' . sprintf("%06d", $reservation_id), $zoho_data);
				}
				break;
			case 7:
				//for rejected status
				$zoho_data = array(
					'Order_completed' => 'Rejected by restaurant',
				);
				if ($reservType == "Experience") {
					$this->reservationDetails->changeStatusInZoho('E' . sprintf("%06d", $reservation_id), $zoho_data);
				} else if ($reservType == "Alacarte") {
					$this->reservationDetails->changeStatusInZoho('A' . sprintf("%06d", $reservation_id), $zoho_data);
				}
				break;
			case 8:
				//for closed status
				$zoho_data = array(
					'Order_completed' => 'yes',
					'Total_Seated'=>$reservationDetailsAttr['attributes']['total_seated'],
					'Actual_attendees'=>$reservationDetailsAttr['attributes']['actual_experience_takers']
				);
				if ($reservType == "Experience") {
					$this->reservationDetails->changeStatusInZoho('E' . sprintf("%06d", $reservation_id), $zoho_data);
				} else if ($reservType == "Alacarte") {
					$this->reservationDetails->changeStatusInZoho('A' . sprintf("%06d", $reservation_id), $zoho_data);
				}
				break;
			case 9:
				//for no show status
				$zoho_data = array(
					'Order_completed' => 'no show',
					'Total_Seated'=>'0',
					'Actual_attendees'=>'0'
				);
				if ($reservType == "Experience") {
					$this->reservationDetails->changeStatusInZoho('E' . sprintf("%06d", $reservation_id), $zoho_data);
				} else if ($reservType == "Alacarte") {
					$this->reservationDetails->changeStatusInZoho('A' . sprintf("%06d", $reservation_id), $zoho_data);
				}
				$data['attributes']['total_seated'] = 0;
				$data['attributes']['actual_experience_takers'] = 0;
				break;
		}
		$data['attributes']['zoho_booking_update'] = "yes";
		$bookingUpdate = $this->reservationDetails->updateAttributes($reservation_id, $data);


		echo "success";
		die;
	}

	public function unconfirmed(){

		$un_bookings = array();
		$count = 0;

		//unconfirmed bookings
		$statusCancelledNew = DB::select(DB::raw('select * from reservation_status_log having new_reservation_status_id in (1,2,7,3,6,7,8,9) and created_at in (SELECT MAX(created_at) FROM reservation_status_log group by reservation_id)'));
		$reservationIdArr = array();
		foreach($statusCancelledNew as $reservId){
			$reservationIdArr[$reservId->reservation_id] = $reservId->user_id;
		}


		$reservStatusArr = $this->reservationDetails->getReservationStatus(array_keys($reservationIdArr),[1,2,7,3,6,7,8,9]);
		$unPaginate = ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
			/*->with(['reservationStatus' => function($query)
                   {
                       $query->whereIn('reservation_statuses.id',[1,2,7])
                             ->orderBy('reservation_statuses.id','desc')
                             ->select(DB::raw('reservation_statuses.*, user_id'));

            }])*/
			->where('vendor_location_id','!=','0')
			->where('vendor_location_id','!=','54')
			->whereIn('id',array_keys($reservationIdArr))
			->where('created_at','>=','2015-10-12 15:20:00')
			->where('id','!=','27355')
			->orderBy('reservation_details.created_at','desc')->paginate(15);

		foreach (ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
					 /*->with(['reservationStatus' => function($query)
							{
								$query->whereIn('reservation_statuses.id',[1,2,7])
									  ->orderBy('reservation_statuses.id','desc')
									  ->select(DB::raw('reservation_statuses.*, user_id'));

					 }])*/
					 ->where('vendor_location_id','!=','0')
					 ->where('vendor_location_id','!=','54')
					 ->whereIn('id',array_keys($reservationIdArr))
					 ->where('created_at','>=','2015-10-12 15:20:00')
					 ->where('id','!=','27355')
					 ->orderBy('reservation_details.created_at','desc')->paginate(15) as $unconfirmedBookings)
		{
			//print_r($unconfirmedBookings->attributesDatetime->attribute_value);die;
			$booking = new \stdClass();
			$booking->id = $unconfirmedBookings->id;
			//echo "<pre>".print_r($unconfirmedBookings);

			if($unconfirmedBookings->product_id == 0){
				$booking->name = "Classic Reservation";
			} else {
				$booking->name = $unconfirmedBookings->experience->name;
			}
			$booking->cust_name = $unconfirmedBookings->guest_name;
			if(isset($unconfirmedBookings->vendor_location->vendor->name)){
				$booking->restaurant_name = $unconfirmedBookings->vendor_location->vendor->name;
			} else {
				$booking->restaurant_name = "";
			}

			if(empty($unconfirmedBookings->vendor_location->address->city_name)){
				$booking->city =  "";
			} else {
				$booking->city = $unconfirmedBookings->vendor_location->address->city_name->name;
			}
			//$reservStatus = $unconfirmedBookings->reservationStatus->first();
			//dd($reservStatus->status);
			$booking->email = $unconfirmedBookings->guest_email;
			$booking->phone_no = $unconfirmedBookings->guest_phone;
			$booking->no_of_persons = $unconfirmedBookings->no_of_persons;
			//$booking->status = $reservStatus->status;
			$userModel = User::find($reservationIdArr[$unconfirmedBookings->id]);
			//echo $unconfirmedBookings->user_id."--";
			//echo $userModel->role->name."<br/>";
			$booking->lastmodified = $userModel->role->name;
			$booking->user_id = $unconfirmedBookings->user_id;

			$statusArr = $this->reservStatuses;
			$statusKey = array_search($reservStatusArr[$unconfirmedBookings->id][0],$statusArr);
			//echo $statusKey."<br/>";
			if($statusKey != -1){
				unset($statusArr[$statusKey]);
			}
			//echo $reservStatusArr[$unconfirmedBookings->id];
			$booking->reserv_status = $reservStatusArr[$unconfirmedBookings->id][0];
			$booking->reservation_status_id = $reservStatusArr[$unconfirmedBookings->id][1];
			$booking->statusArr = $statusArr;
			//echo $reservStatusArr[$unconfirmedBookings->id][0];
			//if($reservStatusArr[$unconfirmedBookings->id][1] == 3){
			if(array_search($reservStatusArr[$unconfirmedBookings->id][1],array(3,6,7,8,9)) != -1){
				$booking->zoho_update = 1;
			} else {
				$booking->zoho_update = 0;
			}

			$reservationDetailsAttr = $this->reservationDetails->getByReservationId($unconfirmedBookings->id);
			$booking->special_request = (isset($reservationDetailsAttr['attributes']['special_request']) ? $reservationDetailsAttr['attributes']['special_request'] : "");
			$booking->gift_card_id = (isset($reservationDetailsAttr['attributes']['gift_card_id_reserv']) ? $reservationDetailsAttr['attributes']['gift_card_id_reserv'] : "");
			$booking->outlet = (isset($reservationDetailsAttr['attributes']['outlet']) ? $reservationDetailsAttr['attributes']['outlet'] : "");
			$booking->reserv_type = $reservationDetailsAttr['attributes']['reserv_type'];

			$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$reservationDetailsAttr['attributes']['reserv_datetime']);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');
			//echo $unconfirmedBookings->id."<pre>".print_r($reservationDetailsAttr['attributes']['zoho_booking_cancelled']);
			//echo $unconfirmedBookings->id;
			if(!isset($reservationDetailsAttr['attributes']['zoho_booking_update']) || array_search($reservStatusArr[$unconfirmedBookings->id][1],array(2)) != -1){
				$un_bookings[$count] = $booking;
			}
			//$un_bookings[$count] = $booking;
			//print_r($booking);die;

			$count++;


		}
		//die;

		return view('admin.bookings.list.unconfirmed')->with('un_bookings',$un_bookings)
													  ->with('unpaginate',$unPaginate);

	}

	public function missing(){
		$postReservation = array();
		$count = 0;

		$statusCancelledNew = DB::select(DB::raw('select * from reservation_status_log having new_reservation_status_id in (6) and created_at in (SELECT MAX(created_at) FROM reservation_status_log group by reservation_id)'));
		$reservationIdArr = array();
		foreach($statusCancelledNew as $reservId){
			$reservationIdArr[$reservId->reservation_id] = $reservId->user_id;
		}

		$reservStatusArr = $this->reservationDetails->getReservationStatus(array_keys($reservationIdArr),[6]);
		//print_r($reservStatusArr);die;
		$missingPaginate = ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
			/*->with(['reservationStatus' => function($query)
                   {
                       $query->whereIn('reservation_statuses.id',[1,2,7])
                             ->orderBy('reservation_statuses.id','desc')
                             ->select(DB::raw('reservation_statuses.*, user_id'));

            }])*/
			->where('vendor_location_id','!=','0')
			->where('vendor_location_id','!=','54')
			->whereIn('id',array_keys($reservationIdArr))
			->where('reservation_date','=',Carbon::yesterday()->format('Y-m-d'))
			->where('created_at','>=','2015-10-12 15:20:00')
			->orderBy('reservation_details.created_at','desc')->paginate(15);

		foreach (ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
					 /*->with(['reservationStatus' => function($query)
							{
								$query->whereIn('reservation_statuses.id',[1,2,7])
									  ->orderBy('reservation_statuses.id','desc')
									  ->select(DB::raw('reservation_statuses.*, user_id'));

					 }])*/
					 ->where('vendor_location_id','!=','0')
					 ->where('vendor_location_id','!=','54')
					 ->whereIn('id',array_keys($reservationIdArr))
					 ->where('reservation_date','=',Carbon::yesterday()->format('Y-m-d'))
					 ->where('created_at','>=','2015-10-12 15:20:00')
					 ->orderBy('reservation_details.created_at','desc')->paginate(15) as $postBookings)
		{
			//print_r($unconfirmedBookings->attributesDatetime->attribute_value);die;
			$booking = new \stdClass();
			$booking->id = $postBookings->id;
			/*$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$postBookings->attributesDatetime->attribute_value);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');*/
			if($postBookings->product_id == 0){
				$booking->name = "Classic Reservation";
			} else {
				$booking->name = $postBookings->experience->name;
			}
			$booking->cust_name = $postBookings->guest_name;
			if(isset($postBookings->vendor_location->vendor->name)){
				$booking->restaurant_name =$postBookings->vendor_location->vendor->name;
			} else {
				$booking->restaurant_name = "";
			}
			if(empty($postBookings->vendor_location->address->city_name)){
				$booking->city =  "";
			} else {
				$booking->city = $postBookings->vendor_location->address->city_name->name;
			}
			//$reservStatus = $unconfirmedBookings->reservationStatus->first();
			//dd($reservStatus->status);
			$booking->email = $postBookings->guest_email;
			$booking->phone_no = $postBookings->guest_phone;
			$booking->no_of_persons = $postBookings->no_of_persons;
			//$booking->status = $reservStatus->status;
			$userModel = User::find($reservationIdArr[$postBookings->id]);
			$booking->lastmodified = $userModel->role->name;
			$booking->user_id = $postBookings->user_id;

			$statusArr = $this->reservStatuses;
			$statusKey = array_search($reservStatusArr[$postBookings->id],$statusArr);
			//echo $statusKey."<br/>";
			if($statusKey != -1){
				unset($statusArr[$statusKey]);
			}
			//echo $reservStatusArr[$unconfirmedBookings->id];
			$booking->reserv_status = $reservStatusArr[$postBookings->id][0];
			$booking->reservation_status_id = $reservStatusArr[$postBookings->id][1];
			$booking->statusArr = $statusArr;

			if($reservStatusArr[$postBookings->id][1] == 6){
				$booking->zoho_update = 1;
			} else {
				$booking->zoho_update = 0;
			}

			$reservationDetailsAttr = $this->reservationDetails->getByReservationId($postBookings->id);
			$booking->special_request = (isset($reservationDetailsAttr['attributes']['special_request']) ? $reservationDetailsAttr['attributes']['special_request'] : "");
			$booking->gift_card_id = (isset($reservationDetailsAttr['attributes']['gift_card_id_reserv']) ? $reservationDetailsAttr['attributes']['gift_card_id_reserv'] : "");
			$booking->outlet = (isset($reservationDetailsAttr['attributes']['outlet']) ? $reservationDetailsAttr['attributes']['outlet'] : "");
			$booking->reserv_type = $reservationDetailsAttr['attributes']['reserv_type'];

			$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$reservationDetailsAttr['attributes']['reserv_datetime']);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');
			//print_r($booking);die;
			$postReservation[$count] = $booking;
			$count++;


		}
		return view('admin.bookings.list.missing')->with('post_bookings',$postReservation)->with('missing_paginate',$missingPaginate);

	}

	public function all(){
		$bookings = array();
		$count = 0;

		$statusCancelledNew = DB::select(DB::raw('select * from reservation_status_log having new_reservation_status_id in (1,2,3,4,5,6,7,8) and created_at in (SELECT MAX(created_at) FROM reservation_status_log group by reservation_id)'));
		$reservationIdArr = array();
		foreach($statusCancelledNew as $reservId){
			$reservationIdArr[$reservId->reservation_id] = $reservId->user_id;
		}
		$reservStatusArr = $this->reservationDetails->getReservationStatus(array_keys($reservationIdArr),[1,2,3,4,5,6,7,8]);
		$allPaginate = ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
			/*->with(['reservationStatus' => function($query)
            {
                $query->whereIn('status',[3,8,6])
                    ->orderBy('reservation_statuses.id','desc')
                    ->select(DB::raw('reservation_statuses.*, user_id'));

            }])*/
			->with(['attributesInteger' => function($query){
				$query->where('reservation_attribute_id',function($q1){
					$q1->select('id')
						->from('reservation_attributes')
						->where('alias','=','order_completed');
				});

			}])
			->where('vendor_location_id','!=','0')
			->where('vendor_location_id','!=','54')
			->whereIn('id',array_keys($reservationIdArr))
			->where('created_at','>=','2015-10-12 15:20:00')
			->orderBy('created_at','desc')->paginate(15);
		foreach (ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
					 /*->with(['reservationStatus' => function($query)
					 {
						 $query->whereIn('status',[3,8,6])
							 ->orderBy('reservation_statuses.id','desc')
							 ->select(DB::raw('reservation_statuses.*, user_id'));

					 }])*/
					 ->with(['attributesInteger' => function($query){
						 $query->where('reservation_attribute_id',function($q1){
							 $q1->select('id')
								 ->from('reservation_attributes')
								 ->where('alias','=','order_completed');
						 });

					 }])
					 ->where('vendor_location_id','!=','0')
					 ->where('vendor_location_id','!=','54')
					 ->whereIn('id',array_keys($reservationIdArr))
					 ->where('created_at','>=','2015-10-12 15:20:00')
					 ->orderBy('created_at','desc')->paginate(15) as $allbookings)
		{


			$booking = new \stdClass();
			$booking->id = $allbookings->id;
			//print_r($allbookings);
			//echo "<br/><br/>";
			/*$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$allbookings->attributesDatetime->attribute_value);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');*/
			if($allbookings->product_id == 0){
				$booking->name = "Classic Reservation";
			} else {
				$booking->name = $allbookings->experience->name;
			}
			$booking->cust_name = $allbookings->guest_name;
			if(isset($allbookings->vendor_location->vendor->name)){
				$booking->restaurant_name =$allbookings->vendor_location->vendor->name;
			} else {
				$booking->restaurant_name = "";
			}
			if(empty($allbookings->vendor_location->address->city_name)){
				$booking->city =  "";
			} else {
				$booking->city = $allbookings->vendor_location->address->city_name->name;
			}
			//$reservStatus = $allbookings->reservationStatus->first();
			//dd($reservStatus);
			$booking->email = $allbookings->guest_email;
			$booking->phone_no = $allbookings->guest_phone;
			$booking->no_of_persons = $allbookings->no_of_persons;
			//$booking->status = $reservStatus->status;
			$userModel = User::find($reservationIdArr[$allbookings->id]);
			$booking->lastmodified = $userModel->role->name;
			$booking->user_id = $allbookings->user_id;

			if(!$allbookings->attributesInteger->isEmpty()){
				$booking->order_completed = 1;
			} else {
				$booking->order_completed = 0;
			}

			$statusArr = $this->reservStatuses;
			$statusKey = array_search($reservStatusArr[$allbookings->id],$statusArr);
			if($statusKey != -1){
				unset($statusArr[$statusKey]);
			}
			$booking->reserv_status = $reservStatusArr[$allbookings->id][0];
			$booking->statusArr = $statusArr;

			$reservationDetailsAttr = $this->reservationDetails->getByReservationId($allbookings->id);
			$booking->special_request = (isset($reservationDetailsAttr['attributes']['special_request']) ? $reservationDetailsAttr['attributes']['special_request'] : "");
			$booking->gift_card_id = (isset($reservationDetailsAttr['attributes']['gift_card_id_reserv']) ? $reservationDetailsAttr['attributes']['gift_card_id_reserv'] : "");
			$booking->outlet = (isset($reservationDetailsAttr['attributes']['outlet']) ? $reservationDetailsAttr['attributes']['outlet'] : "");
			$booking->reserv_type = $reservationDetailsAttr['attributes']['reserv_type'];
			$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$reservationDetailsAttr['attributes']['reserv_datetime']);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');
			$bookings[$count] = $booking;
			$count++;

			//var_dump();


		}
		return view('admin.bookings.list.all')->with('bookings',$bookings)->with('all_paginate',$allPaginate);

	}

	public function today(){
		$todayBookings = array();
		$count = 0;

		$statusCancelledNew = DB::select(DB::raw('select rd.id as reservation_id,rd.user_id as user_id from reservation_details as rd left join reservation_attributes_date as rad on rd.id = rad.reservation_id where DATE(rad.attribute_value) = \''.Carbon::now()->format('Y-m-d').'\''));
		$reservationIdArr = array();
		foreach($statusCancelledNew as $reservId){
			$reservationIdArr[$reservId->reservation_id] = $reservId->user_id;
		}
		$reservStatusArr = $this->reservationDetails->getReservationStatus(array_keys($reservationIdArr),[1,2,3,6,7,8]);
		$todayPaginate = ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
			/*->with(['reservationStatus' => function($query)
            {
                $query->whereIn('status',[1,2,3,6,7,8])
                    ->orderBy('reservation_statuses.id','desc')
                    ->select(DB::raw('reservation_statuses.*, user_id'));

            }])*/
			->where('vendor_location_id','!=','0')
			->where('vendor_location_id','!=','54')
			->whereIn('id',array_keys($reservationIdArr))
			//->whereRaw("reservation_date = '".date('Y-m-d')."'")
			->where('created_at','>=','2015-10-12 15:20:00')
			->orderBy('created_at','desc')->paginate(15);
		foreach (ReservationDetails::with('experience','vendor_location.vendor','vendor_location.address.city_name','attributesDatetime')
					 /*->with(['reservationStatus' => function($query)
					 {
						 $query->whereIn('status',[1,2,3,6,7,8])
							 ->orderBy('reservation_statuses.id','desc')
							 ->select(DB::raw('reservation_statuses.*, user_id'));

					 }])*/
					 ->where('vendor_location_id','!=','0')
					 ->where('vendor_location_id','!=','54')
					 ->whereIn('id',array_keys($reservationIdArr))
					 //->whereRaw("reservation_date = '".date('Y-m-d')."'")
					 ->where('created_at','>=','2015-10-12 15:20:00')
					 ->orderBy('created_at','desc')->paginate(15) as $today)
		{


			$booking = new \stdClass();
			$booking->id = $today->id;
			/*$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$today->attributesDatetime->attribute_value);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');*/
			if($today->product_id == 0){
				$booking->name = "Classic Reservation";
			} else {
				$booking->name = $today->experience->name;
			}
			$booking->cust_name = $today->guest_name;
			if(isset($today->vendor_location->vendor->name)){
				$booking->restaurant_name =$today->vendor_location->vendor->name;
			} else {
				$booking->restaurant_name = "";
			}
			if(empty($today->vendor_location->address->city_name)){
				$booking->city =  "";
			} else {
				$booking->city = $today->vendor_location->address->city_name->name;
			}
			//$reservStatus = $today->reservationStatus->first();
			//dd($reservStatus);
			$booking->email = $today->guest_email;
			$booking->phone_no = $today->guest_phone;
			$booking->no_of_persons = $today->no_of_persons;
			//$booking->status = $reservStatus->status;
			$userModel = User::find($reservationIdArr[$today->id]);
			$booking->lastmodified = $userModel->role->name;
			$booking->user_id = $today->user_id;

			$statusArr = $this->reservStatuses;
			$statusKey = array_search($reservStatusArr[$today->id],$statusArr);
			if($statusKey != -1){
				unset($statusArr[$statusKey]);
			}
			$booking->reserv_status = $reservStatusArr[$today->id][0];
			$booking->statusArr = $statusArr;

			$reservationDetailsAttr = $this->reservationDetails->getByReservationId($today->id);
			$booking->special_request = (isset($reservationDetailsAttr['attributes']['special_request']) ? $reservationDetailsAttr['attributes']['special_request'] : "");
			$booking->gift_card_id = (isset($reservationDetailsAttr['attributes']['gift_card_id_reserv']) ? $reservationDetailsAttr['attributes']['gift_card_id_reserv'] : "");
			$booking->outlet = (isset($reservationDetailsAttr['attributes']['outlet']) ? $reservationDetailsAttr['attributes']['outlet'] : "");
			$booking->reserv_type = $reservationDetailsAttr['attributes']['reserv_type'];
			$reservCarbonDate = Carbon::createFromFormat('Y-m-d H:i:s',$reservationDetailsAttr['attributes']['reserv_datetime']);
			$booking->bdate = $reservCarbonDate->format('d-m-Y');
			$booking->btime = $reservCarbonDate->format('h:i A');
			$todayBookings[$count] = $booking;
			$count++;

			//print_r($today);
		}
		return view('admin.bookings.list.today')->with('todaysbookings',$todayBookings)->with('today_paginate',$todayPaginate);

	}

}
