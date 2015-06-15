<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Controllers\Controller;
use Config;
use Illuminate\Http\Request;
use WowTables\Http\Models\Frontend\ReservationModel;
use WowTables\Http\Models\Eloquent\Location;
use WowTables\Http\Models\Eloquent\Products\ProductVendorLocationLimit;
use WowTables\Http\Models\Eloquent\Vendors\VendorLocationLimit;
use WowTables\Http\Models\Schedules;
use WowTables\Http\Models\Eloquent\ProductVendorLocationBlockSchedule;
use WowTables\Http\Models\Eloquent\ReservationDetails;
use WowTables\Http\Models\Frontend\AlacarteModel;
use WowTables\Http\Models\Frontend\ExperienceModel;
use WowTables\Http\Models\UserDevices;
use Validator;
use Session;
use Input;
use Hash;
use DB;
use Auth;
use Redirect;
use Mail;																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																
use Response;
use Mailchimp;
use WowTables\Http\Models\Profile;
use WowTables\Core\Repositories\Experiences\ExperiencesRepository;
use WowTables\Core\Repositories\Restaurants\RestaurantLocationsRepository;

class RegistrationsController extends Controller {

	protected $listId = '986c01a26a';

	function __construct(Request $request, AlacarteModel $alacarte_model, ExperienceModel $experiences_model,Mailchimp $mailchimp,RestaurantLocationsRepository $restaurantLocationsRepository,ExperiencesRepository $experiencesRepository,ExperienceModel $experiences_model)
	{
		$this->request = $request;
		$this->alacarte_model = $alacarte_model;
		$this->experiences_model = $experiences_model;
		$this->mailchimp = $mailchimp;
		$this->restaurantLocationsRepository = $restaurantLocationsRepository;
		$this->experiencesRepository = $experiencesRepository;
	}

	public function registerView()
	{
		return view('site.users.register');
	}

	public function register()
	{
		dd($this->request->all());
	}

	/**
	 * Handles requst for displaying the historical my reservation
	 * record of the logged in user.
	 * 
	 * @access	public
	 * @param	string	$access_token
	 * @return	response
	 * @since	1.0.0
	 */
	public function reservationRecord()
	{
		$user_array = Session::all();
		//$userID =Session::get('id');
		//this code is start in header and footer page.
        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $arrResponse['cities'] = $cities;

        $city_id    = Input::get('city');        
        $city_name  = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
        if(empty($city_name))
        {
            $city_name = 'mumbai';
        }

        $arrResponse['allow_guest']            ='Yes'; 
        $arrResponse['current_city']           = strtolower($city_name);
        $arrResponse['current_city_id']        = $city_id;
        //this code is start in header and footer page.
       $userID = Session::get('id');
		if($userID) {
			$arrReservation = ReservationModel::getReservationRecord($userID);
		}
		else {
			$arrReservation['status'] = Config::get('constants.API_ERROR');
			$arrReservation['msg'] = 'Not a valid request'; 
		}
		/*print_r($arrReservation);
		foreach ($arrReservation['data']['pastReservation'] as $data) {
			echo $data['guest_email'];
		}
		exit;*/
		//return response()->json($arrResponse,200);   
		//$aLaCarteID = DB::table('vendor_locations')->where('slug',$alaslug)->first()->id; //@kailash
        /*$aLaCarteID 		 = '97';
        $arrALaCarte 		 = $this->alacarte_model->getALaCarteDetails($aLaCarteID);
		$data['reserveData'] = $this->alacarte_model->getAlacarteLimit($aLaCarteID);
        $data['block_dates'] = $this->alacarte_model->getAlacarteBlockDates($aLaCarteID);
        $data['schedule']    = $this->alacarte_model->getAlacarteLocationSchedule($aLaCarteID);*/ 
        /*	print_r($arrReservation);
        	exit;*/
        return view('frontend.pages.myreservation',$arrResponse)
        			->with('arrReservation',$arrReservation);
        			/*->with('data',$data);*/
	}

	/**
	 * Handles requests for time load a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function timedataload()
	{
		//$aLaCarteID = DB::table('vendor_locations')->where('slug',$alaslug)->first()->id; //@kailash
		$dateText = $this->request->input('dateText');
		$vendor_details = $this->request->input('vendor_id');
		$last_reserv_time = $this->request->input('last_reserv_time');
		$array = explode(',', $vendor_details);
		$type = $array[0];
		$vendor_id =$array[1];
		$product_id =$array[2];
		if($type=='alacarte')
		{
        $aLaCarteID 		 = $vendor_id;
		$data['schedule']    = $this->alacarte_model->getAlacarteLocationSchedule($aLaCarteID); 
		$vendorId = $vendor_id;
		}
		else
		{
		$data['schedule'] = $this->experiences_model->getExperienceLocationSchedule($product_id);
		$vendorId = $vendor_id;
		}
		return view('frontend.pages.myreservationajax',$data)
        			->with('data',$data)->with('dateText',$dateText)
        			->with('vendorId',$vendorId)->with('last_reserv_time',$last_reserv_time);
	}

	/**
	 * Handles requests for partysizeajax a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function partysizeajax()
	{
		$vendor_details = $this->request->input('vendor_id');
		$array = explode(',', $vendor_details);
		$type = $array[0];
		$vendor_id =$array[1];
		$product_id =$array[2];
		if($type=='alacarte')
		{
        $aLaCarteID 		 = $vendor_id;
		$data['reserveData'] = $this->alacarte_model->getAlacarteLimit($aLaCarteID); 
		$min_people = $data['reserveData'][$aLaCarteID]['min_people'];
 		$max_people = $data['reserveData'][$aLaCarteID ]['max_people'];
 		 echo '<select name="qty" id="party_size1"  class="pull-right space hidden">
                            <option value="0">SELECT</option>';
                             for($i=$min_people;$i<=$max_people;$i++)
                            {
                                echo '<option value="'.$i.'">'.$i.'People</option>';
                        	}
                           
                     echo '</select>';
		}
		else
		{
		$data['reserveData']  = $this->experiences_model->getExperienceLimit($product_id);
		$min_people = $data['reserveData'][$vendor_id]['min_people'];
 		$max_people = $data['reserveData'][$vendor_id ]['max_people'];
 		 echo '<select name="qty" id="party_size1"  class="pull-right space hidden">
                            <option value="0">SELECT</option>';
                             for($i=$min_people;$i<=$max_people;$i++)
                            {
                                echo '<option value="'.$i.'">'.$i.'People</option>';
                        	}
                           
                     echo '</select>';
		}
		
	}

	/**
	 * Handles requests for cancelling a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function reservationCancel()
	{
		$reservationID = $this->request->input('reserv_id');
		$reservationType = $this->request->input('reserv_type');
		//echo "reservationID == ".$reservationID." , reservationType == ".$reservationType; die;
		$arrResponse = ReservationModel::cancelReservation($reservationID, $reservationType);
		$userID = Session::get('id');
		$userData = Profile::getUserProfileWeb($userID);

		$zoho_data  = array(
			'Loyalty_Points_Awarded'=>0,
			'Order_completed'=>'User Cancelled',
		);
		//$res_data = $this->zoho_edit_booking('E'.sprintf("%06d",$reservationID),$zoho_data);

		$rewardsPoints = '';
		$type = '';
		$bookingsMade = '';
		$lastOrderId = '';

		$arrReservationDetails = DB::table('reservation_details')->where('id', $reservationID)
			->select('reservation_date','reservation_time','no_of_persons','product_vendor_location_id','vendor_location_id')
			->get();

		//echo "<pre>"; print_r($arrReservationDetails); //die;

		if($arrResponse['status']=='ok')
		{

			if($reservationType == "experience"){

				$setBookingKey = 'MERGE11';
				$setBookingsValue = $userData['data']['bookings_made'];


				$arrProductID = DB::table('product_vendor_locations')->where('id', $arrReservationDetails[0]->product_vendor_location_id)
					->select('product_id','vendor_location_id')
					->get();

				$productDetails = $this->experiencesRepository->getByExperienceId($arrProductID[0]->product_id);

				$outlet = $this->experiences_model->getOutlet($arrReservationDetails[0]->product_vendor_location_id);

				//$locationDetails = $this->experiences_model->getLocationDetails($arrReservationDetails[0]->product_vendor_location_id);
				//echo "<br/>---- productdetails---<pre>"; print_r($productDetails);
				//echo "<br/>---- outlet---<pre>"; print_r($outlet);
				$rewardsPoints = $productDetails['attributes']['reward_points_per_reservation'];
				$bookingsMade = $userData['data']['bookings_made'] - 1;
				$type = "cancel";
				$reservationType = "experience";
				$lastOrderId = $reservationID;

				$dataPost = array('reservation_type'=> $reservationType,
					'reservationID' => $reservationID,
					'partySize' => $arrReservationDetails[0]->no_of_persons,
					'reservationDate'=> $arrReservationDetails[0]->reservation_date,
					'reservationTime'=> $arrReservationDetails[0]->reservation_time,
					'guestName'=>$userData['data']['full_name'],
					'guestEmail'=>$userData['data']['email'],
					'guestPhoneNo'=>$userData['data']['phone_number'],
					'order_id'=> "#E".sprintf("%06d",$reservationID),
					'venue' => $outlet->vendor_name,
				);


			} else if($reservationType == "alacarte"){

				$setBookingKey = 'MERGE26';
				$setBookingsValue = $userData['data']['a_la_carte_reservation'];


				$outlet = $this->alacarte_model->getOutlet($arrReservationDetails[0]->vendor_location_id);

				$locationDetails = $this->alacarte_model->getLocationDetails($arrReservationDetails[0]->vendor_location_id);

				$vendorDetails = $this->restaurantLocationsRepository->getByRestaurantLocationId($arrReservationDetails[0]->vendor_location_id);
				//echo "<br/>---- vendorDetails---<pre>"; print_r($vendorDetails);
				//echo "<br/>---- outlet---<pre>"; print_r($outlet);

				$rewardsPoints = $vendorDetails['attributes']['reward_points_per_reservation'];
				$bookingsMade = $userData['data']['a_la_carte_reservation'] - 1;
				$type = "cancel";
				$reservationType = "alacarte";
				$lastOrderId = $reservationID;

				$dataPost = array('reservation_type'=> $reservationType,
					'reservationID' => $reservationID,
					'partySize' => $arrReservationDetails[0]->no_of_persons,
					'reservationDate'=> $arrReservationDetails[0]->reservation_date,
					'reservationTime'=> $arrReservationDetails[0]->reservation_time,
					'guestName'=>$userData['data']['full_name'],
					'guestEmail'=>$userData['data']['email'],
					'guestPhoneNo'=>$userData['data']['phone_number'],
					'order_id'=> "#A".sprintf("%06d",$reservationID),
					'venue' => $outlet->vendor_name,
				);



			}

			if(!empty($userData)){
					$merge_vars = array(
						$setBookingKey=>$setBookingsValue - 1,
					);

					//$email = ["email"["email":]];
					//$this->mailchimp->lists->subscribe($this->listId, ['email' => $_POST['email']],$merge_vars,"html",true,true );
					//$this->mc_api->listSubscribe($list_id, $_POST['email'], $merge_vars,"html",true,true );

				}

			Profile::updateReservationInUsers($rewardsPoints,$type,$bookingsMade,$reservationType,$userID,$lastOrderId);

			Mail::send('site.pages.cancel_reservation',[
				'post_data'=>$dataPost,
			], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to($dataPost['guestEmail'])->subject('Your WowTables Reservation');
				//$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
			});


			Mail::send('site.pages.cancel_reservation',[
				'post_data'=>$dataPost,
			], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to('concierge@wowtables.com')->subject('CR - '.$dataPost['order_id'].' | '.date('d-F-Y',strtotime($dataPost['reservationDate'])).' , '.date('g:i a',strtotime($dataPost['reservationTime'])).' | '.$dataPost['venue'].' | '.$dataPost['guestName']);
				$message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com');
			});



			echo '1';
		}
	}

	/**
	 * Handles requests for cancelling a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function changeReserve($id="")
	{
		
    $arrdata = DB::table('reservation_details')->where('id', $id)
                  ->select('reservation_date','reservation_time','no_of_persons')
                  ->get();
    $reservation_date = $arrdata[0]->reservation_date;
    $reservation_time = $arrdata[0]->reservation_time;
    $no_of_persons = $arrdata[0]->no_of_persons;



    $arrData = array('last_reservation_date'=>$reservation_date,
    				 'last_reservation_time'=>$reservation_time,
    				 'convert_time'=>date('h:i A',strtotime($reservation_time)),
    				 'convert_date'=>date('jS m, Y',strtotime($reservation_date)),
     				 'no_of_persons'=>$no_of_persons);
   		 echo json_encode($arrData);
    	exit;
    	$aLaCarteID 		 = '97';
        $arrALaCarte 		 = $this->alacarte_model->getALaCarteDetails($aLaCarteID);
		$data['reserveData'] = $this->alacarte_model->getAlacarteLimit($aLaCarteID);
        $data['block_dates'] = $this->alacarte_model->getAlacarteBlockDates($aLaCarteID);
        $data['schedule']    = $this->alacarte_model->getAlacarteLocationSchedule($aLaCarteID); 
        echo json_encode($data);
        exit;
    	//return view('frontend.pages.myreservationajax',$data)->with('data',$data);
	}

	/**
	 * Handles requests for cancelling a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function updateReservetion()
	{

		$vendor_details = $this->request->input('vendor_details');
		$array = explode(',', $vendor_details);
		$reserveType = $array['0'];
		//echo "sd = ".$reserveType; die;
		$reserv_id = $this->request->input('reserv_id');
		$party_size = $this->request->input('party_size');
		$edit_date = $this->request->input('edit_date');
		$edit_date1 = $this->request->input('last_reserv_date');
		$datearray=explode(" ",$edit_date);
		$date = trim(str_replace(range('a','z'),'',$datearray["0"]));
		$remove_comma = trim(str_replace(',','',$datearray["1"]));
		$month = str_pad($remove_comma, 2, "0", STR_PAD_LEFT); 
		$year = $datearray["2"];
		$final_date_format = $year.'-'.$month.'-'.$date;
		$edit_time = date("H:i:s", strtotime($this->request->input('edit_time')));

		DB::update("update reservation_details set reservation_date='$final_date_format',reservation_time='$edit_time',no_of_persons='$party_size',reservation_status='edited' where id = '$reserv_id'");

		$userID = Session::get('id');
		$userData = Profile::getUserProfileWeb($userID);

		if($reserveType == "experience"){
			$arrProductVendorLocationId = DB::table('reservation_details')->where('id', $reserv_id)
				->select('product_vendor_location_id')
				->get();

			$arrProductID = DB::table('product_vendor_locations')->where('id', $arrProductVendorLocationId[0]->product_vendor_location_id)
				->select('product_id','vendor_location_id')
				->get();

			$productDetails = $this->experiencesRepository->getByExperienceId($arrProductID[0]->product_id);

			$outlet = $this->experiences_model->getOutlet($arrProductVendorLocationId[0]->product_vendor_location_id);

			$locationDetails = $this->experiences_model->getLocationDetails($arrProductVendorLocationId[0]->product_vendor_location_id);

			//echo "<prE>"; print_r($productDetails);
			//echo "<br/>----outlet-----<prE>"; print_r($outlet);
			//echo "<br/>----locationDetails-----<prE>"; print_r($locationDetails);
			$zoho_data = array(
				'Name' => $userData['data']['full_name'],
				'Email_ids' => $userData['data']['email'],
				'Contact' => $userData['data']['phone_number'],
				'Experience_Title' => $outlet->vendor_name.' - '.$outlet->descriptive_title,
				'No_of_People' => $party_size,
				'Date_of_Visit' => date('d-M-Y', strtotime($edit_date)),
				'Time' => date("G:ia", strtotime($this->request->input('edit_time'))),
				//'Refferal' => (isset($ref['partner_name'])) ? $ref['partner_name'] : $google_add,
				'Type' => 'Experience',
				'API_added' => 'Yes',
				'GIU_Membership_ID' =>$userData['data']['membership_number'],
				'Outlet' => $outlet->name,
				//'Points_Notes'=>$this->data['bonus_reason'],
				'AR_Confirmation_ID'=>'0',
				'Auto_Reservation'=>'Not available',
				'Order_completed'=>'User Changed',
			);

			$this->zoho_edit_booking('E'.sprintf("%06d",$reserv_id),$zoho_data);

			$dataPost = array('reservation_type'=> $reserveType,
				              'reservationID' => $reserv_id,
				              'partySize' => $party_size,
							  'reservationDate'=> $edit_date1,
							  'reservationTime'=> $this->request->input('edit_time'),
				              'guestName'=>$userData['data']['full_name'],
							  'guestEmail'=>$userData['data']['email'],
				              'guestPhoneNo'=>$userData['data']['phone_number'],
							  'order_id'=> sprintf("%06d",$reserv_id),
				              'venue' => $outlet->vendor_name,
							  'reservation_date'=> date('d-F-Y',strtotime($edit_date1)),
							  'reservation_time'=> date('g:i a',strtotime($this->request->input('edit_time'))),

			);
			//echo "<br/>---datapost---<pre>"; print_r($dataPost);die;
			Mail::send('site.pages.edit_experience_reservation',[
				'location_details'=> $locationDetails,
				'outlet'=> $outlet,
				'post_data'=>$dataPost,
				'productDetails'=>$productDetails,
			], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to($dataPost['guestEmail'])->subject('Your WowTables Reservation');
				//$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
			});


			Mail::send('site.pages.edit_experience_reservation',[
				'location_details'=> $locationDetails,
				'outlet'=> $outlet,
				'post_data'=>$dataPost,
				'productDetails'=>$productDetails,
				], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to('concierge@wowtables.com')->subject('ER - #E'.$dataPost['order_id'].' | '.$dataPost['reservation_date'].' , '.$dataPost['reservation_time'].' | '.$dataPost['venue'].' | '.$dataPost['guestName']);
				$message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com');
			});

		} else if($reserveType == "alacarte"){

			$arrVendorLocationID = DB::table('reservation_details')->where('id', $reserv_id)
				->select('vendor_location_id')
				->get();

			$outlet = $this->alacarte_model->getOutlet($arrVendorLocationID[0]->vendor_location_id);

			$locationDetails = $this->alacarte_model->getLocationDetails($arrVendorLocationID[0]->vendor_location_id);

			$vendorDetails = $this->restaurantLocationsRepository->getByRestaurantLocationId($arrVendorLocationID[0]->vendor_location_id);


			$zoho_data = array(
				'Name' => $userData['data']['full_name'],
				'Email_ids' => $userData['data']['email'],
				'Contact' => $userData['data']['phone_number'],
				'Experience_Title' => $outlet->vendor_name.' - Ala Carte',
				'No_of_People' => $party_size,
				'Date_of_Visit' => date('d-M-Y', strtotime($edit_date)),
				'Time' => date("G:ia", strtotime($this->request->input('edit_time'))),
				//'Refferal' => (isset($ref['partner_name'])) ? $ref['partner_name'] : $google_add,
				'Type' => 'Experience',
				'API_added' => 'Yes',
				'GIU_Membership_ID' =>$userData['data']['membership_number'],
				'Outlet' => $outlet->name,
				//'Points_Notes'=>$this->data['bonus_reason'],
				'AR_Confirmation_ID'=>'0',
				'Auto_Reservation'=>'Not available',
				'Order_completed'=>'User Changed',
			);

			$this->zoho_edit_booking('E'.sprintf("%06d",$reserv_id),$zoho_data);

			$dataPost = array('reservation_type'=> $reserveType,
				'reservationID' => $reserv_id,
				'partySize' => $party_size,
				'reservationDate'=> $edit_date1,
				'reservationTime'=> $this->request->input('edit_time'),
				'guestName'=>$userData['data']['full_name'],
				'guestEmail'=>$userData['data']['email'],
				'guestPhoneNo'=>$userData['data']['phone_number'],
				'order_id'=> sprintf("%06d",$reserv_id),
				'venue' => $outlet->vendor_name,
				'reservation_date'=> date('d-F-Y',strtotime($edit_date1)),
				'reservation_time'=> date('g:i a',strtotime($this->request->input('edit_time'))),

			);



			Mail::send('site.pages.edit_restaurant_reservation',[
				'location_details'=> $locationDetails,
				'outlet'=> $outlet,
				'post_data'=>$dataPost,
				'productDetails'=>$vendorDetails,
			], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to($dataPost['guestEmail'])->subject('Your WowTables Reservation');
				//$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
			});


			Mail::send('site.pages.edit_restaurant_reservation',[
				'location_details'=> $locationDetails,
				'outlet'=> $outlet,
				'post_data'=>$dataPost,
				'productDetails'=>$vendorDetails,
			], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to('concierge@wowtables.com')->subject('ER - #A'.$dataPost['order_id'].' | '.$dataPost['reservation_date'].' , '.$dataPost['reservation_time'].' | '.$dataPost['venue'].' | '.$dataPost['guestName']);
				$message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com');
			});
		}


   		
   		echo '1';
	}


	/**
	 * Handles requst for displaying the my account reservation.
	 * record of the logged in user.
	 * 
	 * @access	public
	 * @param	string	$access_token
	 * @return	response
	 * @since	1.0.0
	 */
	public function myAccount()
	{
		$user_array = Session::all();
		//$userID =Session::get('id');
		//this code is start in header and footer page.
        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $arrResponse['cities'] = $cities;

        $city_id    = Input::get('city');        
        $city_name  = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
        if(empty($city_name))
        {
            $city_name = 'mumbai';
        }

        $arrResponse['allow_guest']            ='Yes'; 
        $arrResponse['current_city']           = strtolower($city_name);
        $arrResponse['current_city_id']        = $city_id;
        //this code is start in header and footer page.
       $userID = Session::get('id');
		
		
        	
        return view('frontend.pages.myaccount',$arrResponse);
	}

	public function zoho_edit_booking($order_id,$data){
		$ch = curl_init();
		$config = array(
			//'authtoken' => 'e56a38dab1e09933f2a1183818310629',
			'authtoken' => '7e8e56113b2c2eb898bca9916c52154c',
			'scope' => 'creatorapi',
			'criteria'=>'Alternate_ID='.$order_id,
		);
		$curlConfig = array(
			CURLOPT_URL            => "https://creator.zoho.com/api/gourmetitup/xml/experience-bookings/form/bookings/record/update/",
			CURLOPT_POST           => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS     => $config + $data,
		);
		curl_setopt_array($ch, $curlConfig);
		$result = curl_exec($ch);
		//  out($result);die;
		curl_close($ch);
	}


}
