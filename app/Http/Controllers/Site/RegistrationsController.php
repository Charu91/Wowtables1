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
use WowTables\Http\Models\Eloquent\Reservations\ReservationDetails as ReservDetailsModel;
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
use Carbon\Carbon;
use WowTables\Http\Controllers\ConciergeApi\ReservationController;

class RegistrationsController extends Controller {

	protected $listId = '986c01a26a';

	function __construct(Request $request, AlacarteModel $alacarte_model, ExperienceModel $experiences_model,Mailchimp $mailchimp,RestaurantLocationsRepository $restaurantLocationsRepository,ExperiencesRepository $experiencesRepository,ExperienceModel $experiences_model,ReservationController $restaurantapp)
	{
		$this->request = $request;
		$this->alacarte_model = $alacarte_model;
		$this->experiences_model = $experiences_model;
		$this->mailchimp = $mailchimp;
		$this->restaurantLocationsRepository = $restaurantLocationsRepository;
		$this->experiencesRepository = $experiencesRepository;
		$this->restaurantapp = $restaurantapp;
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
        	/*print_r($arrReservation);
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
	 * Handles requests for partysizeajax a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function productVendorLoad()
	{
		$product_id = $this->request->input('product_id');
		$locality_change_val = $this->request->input('locality_change_val');
		$selectVendorLocationIdQuery = DB::select("SELECT vendor_location_id
													FROM product_vendor_locations
													WHERE product_id = '$product_id'
													AND id = '$locality_change_val'");

		$selectVendorLocationId = $selectVendorLocationIdQuery[0]->vendor_location_id;
		echo '<input type="hidden" id="locality_val" value="'.$selectVendorLocationId.'">';
	}

	/**
	 * Handles requests for partysizeajax a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function myReservGiftCard()
	{
		$res_id = $this->request->input('res_id');
		$giftcard_id = DB::select("select giftcard_id, special_request from reservation_details where id = $res_id");

		if(empty($giftcard_id))
		{?>

		<div class="panel panel-default">
                <div class="panel-heading active">
                  <h4 class="panel-title">
                     <a href="javascript:" style="text-decoration: none;">
                      Gift card Id </a>
                      <input type="text" name="giftcard_id" id="giftcard_id" class="form-control" placeholder="Gift card Id (If available)">
                        
                  </h4>
                </div>
              </div>

		<?php }
		else
		{?>
		<div class="panel panel-default">
                <div class="panel-heading active">
                  <h4 class="panel-title">
                     <a href="javascript:" style="text-decoration: none;">
                      Gift card Id </a>
                      <input type="text" name="giftcard_id" id="giftcard_id" class="form-control" placeholder="Gift card Id (If available)" value="<?php echo $giftcard_id[0]->giftcard_id;?>">
                        
                  </h4>
                </div>
              </div>

		<?php }

		if(empty($giftcard_id[0]->special_request))
		{?>

		<div class="panel panel-default">
                <div class="panel-heading active">
                  <h4 class="panel-title">
                     <a href="javascript:" style="text-decoration: none;">
                      Special Request </a>
                      <input type="text" name="special_request" id="special_request" class="form-control" placeholder="Special Request">
                        
                  </h4>
                </div>
              </div>

		<?php }
		else
		{?>
		<div class="panel panel-default">
                <div class="panel-heading active">
                  <h4 class="panel-title">
                     <a href="javascript:" style="text-decoration: none;">
                      Special Request </a>
                      <input type="text" name="special_request" id="special_request" class="form-control" placeholder="Special Request" value="<?php echo $giftcard_id[0]->special_request;?>">
                        
                  </h4>
                </div>
              </div>

		<?php }


	}

	/**
	 * Handles requests for myReservLocality a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function myReservLocality()
	{
		$product_id = $this->request->input('product_id');
		$city_id = $this->request->input('city_id');
		$res_id = $this->request->input('res_id');
		$vendor_location_id = $this->request->input('vendor_location_id');
		$selectArea = DB::select("SELECT `pvl`.`id` AS `vendor_location_id` , `l1`.`name` AS area
					FROM `product_vendor_locations` AS `pvl`
					LEFT JOIN vendor_location_address AS vla ON `vla`.`vendor_location_id` = `pvl`.`vendor_location_id`
					LEFT JOIN `locations` AS `l1` ON `l1`.`id` = `vla`.`area_id`
					LEFT JOIN `locations` AS `l2` ON `l2`.`id` = `vla`.`city_id`
					LEFT JOIN `locations` AS `l3` ON `l3`.`id` = `vla`.`state_id`
					WHERE `pvl`.`product_id` = '$product_id'
					AND `vla`.`city_id` = '$city_id'
					AND `pvl`.`id` = '$vendor_location_id'");
		//print_r($selectArea);
		$areaId = $selectArea[0]->vendor_location_id;
		$areaName = $selectArea[0]->area;
		//exit;
		$selectVendorLocationIdQuery = DB::select("SELECT vendor_location_id FROM reservation_details WHERE id = '$res_id'");
		$selectVendorLocationId = $selectVendorLocationIdQuery[0]->vendor_location_id;

		$arrLocation = Self::getProductLocations($product_id, $city_id);
		//print_r($arrLocation);
		if(count($arrLocation)>1)
		{?>
			<div class="panel panel-default">
                <div class="panel-heading active">
                  <h4 class="panel-title">
                     <a href="javascript:" style="text-decoration: none;">
                      Select Location<input type="hidden" value="<?php echo $product_id;?>" id="my_product_id"> <span id="get_locality"><input type="hidden" id="locality_val" value="<?php echo $selectVendorLocationId;?>"></span></a>
					  <input type="hidden" name="old_area" value="<?php echo $areaName?>" id="old_area_name"/>
					  <input type="hidden" name="old_locality_value" value="<?php echo $selectVendorLocationId?>" id="old_locality_value"/>
					  <input type="hidden" name="new_locality_value" value="" id="new_locality_value"/>
                      <a  href="javascript:" data-original-title="Select the total number of guests at the table. If a larger table size is needed, please contact the WowTables Concierge." data-placement="top" data-toggle="tooltip" class="btn tooltip1"></a>
		              <select name="locality" id="locality"  class="pull-right space" style="display:none;">
		                    <option value="0">SELECT</option>
		                 
		            	  <?php
		                        foreach($arrLocation as $key =>$listData): ?>
		             			<option value="<?php echo $listData['vendor_location_id'];?>" <?php if($areaId==$listData['vendor_location_id']) {echo 'selected';}?> ><?php echo $listData['area'];?></option>
		                        <?php endforeach; ?>

		             </select>
                        <strong><a id="locality_select" href="javascript:"  style="text-decoration: none;float: right;font-size: 13px;color: #EAB703;" >
                        	<span style="color:#756554 !important;" id="myselect_locality"><?php echo $areaName;?></span> EDIT</a></strong>
                  </h4>
                </div>
              </div>
		
		<?php }
	}

	//-----------------------------------------------------------------

	/**
	 * Handles requests for myReservLocality a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function myReservAddons()
	{
		$product_id = $this->request->input('product_id');
		$res_id = $this->request->input('res_id');
		$no_of_persons = $this->request->input('no_of_persons');
		$addOnArr = $this->experiences_model->readExperienceAddOns($product_id);
		$addOnSelect = DB::select("select no_of_persons as person_select,options_id as optionsId from reservation_addons_variants_details where reservation_id = $res_id");
		$arrDataAddonSelect = array();
		foreach ($addOnSelect as $value) {
			$arrDataAddonSelect[] = array('noOfPerson'=>$value->person_select,'optionsId'=>$value->optionsId);
		}
		//$addOnArr['addOnSelect'] = $arrDataAddonSelect;
		
		//exit;*/
		if(count($addOnArr)>0)
		{?>
			<div class="panel panel-default">
                <div class="panel-heading active meals2">
                  <h4 class="panel-title">
                     <a href="javascript:" style="text-decoration: none;">
                      Meal options</a>
                      <a  href="javascript:" data-original-title="Select the total number of guests at the table. If a larger table size is needed, please contact the WowTables Concierge." data-placement="top" data-toggle="tooltip" class="btn tooltip1"></a><br>
                  <?php
		                        foreach($addOnArr as $key =>$addOnDetails): 
		                        	$addOnArr['addOnSelect'] = $arrDataAddonSelect;?> 
		                       <?php $addonproduct_id = $addOnDetails['prod_id'];
		                       $addOnSelect = array();
		                       $addOnSelect = DB::select("select no_of_persons as person_select,options_id as optionsId from reservation_addons_variants_details where reservation_id = $res_id and options_id=$addonproduct_id");?>
		                        <span><?php echo $addOnDetails['reservation_title'];  
		                        if(count($addOnSelect)=='0') {$addon_value='0';}else{$addon_value=$addOnSelect[0]->person_select;} //echo $addOnSelect[0]->person_select;?></span>   
		              <select id="add_ons" data-value="<?php echo $addOnDetails['prod_id'];?>" name="add_ons[<?php echo $addOnDetails['prod_id'];?>]"  class="pull-right space myaddonselect">
		                    <option value="0">0</option>
		                    <?php for($i=1;$i<=$no_of_persons;$i++){?>
		                    <option value="<?php echo $i;?>" <?php if($addon_value==$i) {echo 'selected';}?> ><?php echo $i;?></option>
		                    <?php } ?>
		             	</select>
		             	<br>
		             	<br>
		             	<input type ="hidden" name="add_prod_id[]" value="<?php echo $addOnDetails['prod_id'];?>">
                        <?php endforeach; //print_r($addOnArr);?>
                  </h4>
                </div>
              </div>
		
		<?php }

	}

	//-----------------------------------------------------------------
  
  /**
   * Returns the locations where product can be found.
   * 
   * @static  true
   * @access  public
   * @param integer $productID
   * @return  array
   * @since 1.0.0
   */
  public static function getProductLocations($productID,$cityID) {
      //echo "product_id == ".$productID." , city = ".$cityID; die;
    $queryResult =    DB::table('product_vendor_locations as pvl')
              ->leftJoin(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','pvl.vendor_location_id')
              ->leftJoin('locations as l1', 'l1.id','=','vla.area_id')
              ->leftJoin('locations as l2', 'l2.id','=','vla.city_id')
              ->leftJoin('locations as l3', 'l3.id','=','vla.state_id')
              ->select('pvl.id as vendor_location_id','l1.name as area','l2.name as city','l3.name as state_name','vla.address','vla.pin_code','vla.latitude','vla.longitude')
              ->where('pvl.product_id',$productID)
              ->where('vla.city_id',$cityID)
              ->get();
    
    //array to hold location details
    $arrLocation = array();
    if($queryResult) {
      foreach($queryResult as $row) {

        $arrLocation[] = array(
                  "vendor_location_id" => $row->vendor_location_id,
                  "address_line" => $row->address,
                  "area" => $row->area,
                  "city" => $row->city,
                  "pincode" => $row->pin_code,
                  "state" => $row->state_name,                                                                
                  //"country" => $row->country,
                  "latitude" => $row->latitude,
                  "longitude" => $row->longitude 

                );
      }
    }   
    return $arrLocation;
  }
  
  //-----------------------------------------------------------------

	/**
	 * Handles requests for cancelling a reservation.
	 * 
	 * @access	public
	 * @return	response
	 * @since	1.0.0
	 */
	public function reservationCancel()
	{
		//echo "<pre>"; print_r(Input::all()); die;
		$reservationID = $this->request->input('reserv_id');
		$reservationType = $this->request->input('reserv_type');
		$user_id = $this->request->input('user_id');
		$added_by = $this->request->input('added_by');
		//echo "reservationID == ".$reservationID." , reservationType == ".$reservationType; die;
		$arrResponse = ReservationModel::cancelReservation($reservationID, $reservationType);
		$userID = $user_id;
		$userData = Profile::getUserProfileWeb($userID);
		//print_r($userData);die;

		//for the new db structure support
		$newDb['attributes']['reservation_status_id'] = 3;
		$newDb['userdetails']['user_id'] = $userID;
		$newDb['userdetails']['status'] = 3;
		$newDb['attributes']['seating_status'] = 3;
		$newDb['attributes']['closed_on'] = Carbon::now()->toDateTimeString();

		//print_r($newDb);die;
		$reservDetails = new ReservDetailsModel();
		$newDbStatus = $reservDetails->updateAttributes($reservationID,$newDb);
		$tokens = $reservDetails->pushToRestaurant($reservationID);
		$this->restaurantapp->push($reservationID,$tokens,true);
		//print_r($newDbStatus);die;
		/*TODO: Add the status of success check and include added_by and transaction_id attributes */
		//die;


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

				$zoho_data  = array(
					'Loyalty_Points_Awarded'=>0,
					'Order_completed'=>(isset($added_by) && $added_by == 'user' ? 'User Cancelled' : 'Admin Cancelled'),
				);
				$res_data = $this->zoho_edit_booking('E'.sprintf("%06d",$reservationID),$zoho_data);

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

				$zoho_data  = array(
					'Loyalty_Points_Awarded'=>0,
					'Order_completed'=>(isset($added_by) && $added_by == 'user' ? 'User Cancelled' : 'Admin Cancelled'),
				);
				$res_data = $this->zoho_edit_booking('A'.sprintf("%06d",$reservationID),$zoho_data);


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
						'GROUPINGS' => array(array('id' => 9713, 'groups' => [$userData['data']['location']]))
					);

					//$email = ["email"["email":]];
					$this->mailchimp->lists->subscribe($this->listId, ["email"=>$userData['data']['email']],$merge_vars,"html",true,true );
					//$this->mc_api->listSubscribe($list_id, $_POST['email'], $merge_vars,"html",true,true );

				}

			Profile::updateReservationInUsers($rewardsPoints,$type,$bookingsMade,$reservationType,$userID,$lastOrderId);

			Mail::send('site.pages.cancel_reservation',[
				'post_data'=>$dataPost,
			], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to($dataPost['guestEmail'])->subject('Your WowTables Reservation at '.$dataPost['venue'].' has been cancelled');
				//$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
			});


			Mail::send('site.pages.cancel_reservation',[
				'post_data'=>$dataPost,
			], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to('concierge@wowtables.com')->subject('CR - '.$dataPost['order_id'].' | '.date('d-F-Y',strtotime($dataPost['reservationDate'])).' , '.date('g:i a',strtotime($dataPost['reservationTime'])).' | '.$dataPost['venue'].' | '.$dataPost['guestName']);
				$message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com','abhishek.n@wowtables.com');
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
                  ->select('reservation_date','reservation_time','no_of_persons','giftcard_id')
                  ->get();
    $reservation_date = $arrdata[0]->reservation_date;
    $reservation_time = $arrdata[0]->reservation_time;
    $no_of_persons = $arrdata[0]->no_of_persons;
	$giftcard_id = $arrdata[0]->giftcard_id;



    $arrData = array('last_reservation_date'=>$reservation_date,
    				 'last_reservation_time'=>$reservation_time,
    				 'convert_time'=>date('g:i A',strtotime($reservation_time)),
    				 'convert_date'=>date('jS M, Y',strtotime($reservation_date)),
     				 'no_of_persons'=>$no_of_persons,
					 'giftcard_id'=>($giftcard_id != "" ? $giftcard_id : "NULL")
			);
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
		//echo "<pre>"; print_r(Input::all()); //die;


		$reserv_id = $this->request->input('reserv_id');

		$vendor_details = $this->request->input('vendor_details');
		$array = explode(',', $vendor_details);
		$reserveType = $array['0'];
		//echo "sd = ".$reserveType; die;
		

		$party_size = $this->request->input('party_size');
		$locality_val = $this->request->input('locality_val');
		$edit_date = $this->request->input('edit_date');
		$edit_date1 = $this->request->input('last_reserv_date');
		$new_date = date('Y-m-d',strtotime($this->request->input('last_reserv_date')));
		/*$datearray=explode(" ",$edit_date);
		$date = trim(str_replace(range('a','z'),'',$datearray["0"]));
		$remove_comma = trim(str_replace(',','',$datearray["1"]));
		$month = str_pad($remove_comma, 2, "0", STR_PAD_LEFT); 
		$year = $datearray["2"];
		$final_date_format = $year.'-'.$month.'-'.$date;*/
		$final_date_format = $edit_date1;
		$edit_time = $this->request->input('edit_time');
		//echo "new_date = ".$new_date;
		$last_reservation_time = $this->request->input('last_reservation_time');
		$last_reservation_date = $this->request->input('last_reservation_date');
		$last_reservation_party_size = $this->request->input('last_reservation_party_size');
		$new_reservation_outlet = $this->request->input('new_locality_value');
		$last_reservation_outlet_val = $this->request->input('old_locality_value');
		$last_reservation_outlet_name = $this->request->input('old_area_name');
		$last_reservation_giftcard_id = rtrim($this->request->input('last_reservation_giftcard_id'));
		$user_id = $this->request->input('user_id');
		$added_by = $this->request->input('added_by');



		//check for outlet change
		if($locality_val != $last_reservation_outlet_val){
			//echo " , outlet changed, send to email";
			$old_reservation_outlet = $last_reservation_outlet_name;
			$new_reservation_outlet = $new_reservation_outlet;

			$reservation_oulet = " Old Outlet: ".$old_reservation_outlet." -> New Outlet: ".$new_reservation_outlet;
		} else {
			$reservation_oulet = "";
		}

		//check for party size change
		if($party_size != $last_reservation_party_size){
			//echo " , party size changed, send to email";
			$old_reservation_party_size = $last_reservation_party_size;
			$new_reservation_party_size = $party_size;

			$reservation_party_size = " Old Party Size: ".$old_reservation_party_size." -> New Party Size: ".$new_reservation_party_size;
		} else {
			$reservation_party_size = "";

		}


		//check for date change
		if($new_date != $last_reservation_date){

			$old_reservation_date = $last_reservation_date;
			$new_reservation_date = $new_date;

			$reservation_date = " Old Date: ".$old_reservation_date." -> New Date: ".$new_reservation_date;

		} else {
			$reservation_date = "";
		}

		//check for time change
		if($edit_time != $last_reservation_time){

			$old_reservation_time = $last_reservation_time;
			$new_reservation_time = $edit_time;

			$reservation_time = " Old Time: ".$old_reservation_time." -> New Time: ".$new_reservation_time;

		} else {
			$reservation_time = "";
		}

		$addonsArray= $this->request->input('addonsArray');
		$giftcard_id= $this->request->input('giftcard_id');
		$giftcard_id_text= ($this->request->input('giftcard_id') != "" ? $this->request->input('giftcard_id') : "NULL");
		$special_request= $this->request->input('special_request');
		//	`print_r($addonsArray);
		//echo "sad = ".$giftcard_id;
		$count = $this->request->input('addonsArray');
		$giftcard_change = '';
		if($giftcard_id != $last_reservation_giftcard_id){
			if($giftcard_id == "") {
				//echo " d null = ".$giftcard_id_text;
				$giftcard_change = " old Giftcard ID: ".$last_reservation_giftcard_id." -> New Giftcard ID: ".$giftcard_id_text;
			} else {
				$giftcard_change = " old Giftcard ID: ".$last_reservation_giftcard_id." -> New Giftcard ID: ".$giftcard_id_text;
			}

		} else {
			$giftcard_change = "";
		}
		//die;
		if($count==""){  $addonsArray =array();}

		$addonsText = '';
		foreach($addonsArray as $prod_id => $qty) {
			if($qty > 0){
				//echo "prod id = ".$prod_id." , qty = ".$qty;
				$addonsDetails = DB::select("SELECT attribute_value from product_attributes_text where product_id = $prod_id and product_attribute_id = 17");

				//echo "<pre>"; print_r($addonsDetails);
				$addonsText .= $addonsDetails[0]->attribute_value." (".$qty.") , ";
			}

		}
		//$addons_special_request = isset($addonsText) && $addonsText != "" ? "Addons: ".$addonsText : " ";
		$finalAddontext = isset($addonsText) && $addonsText != "" ? "Addons: ".$addonsText : " ";
		$special_request_data = isset($special_request) && $special_request != "" ? "Spl Req: ".$special_request : "";
		$addons_special_request = $finalAddontext." ".$special_request_data;

		//echo " addon special request = ".$addons_special_request;
		//echo "<pre>"; print_r($addonsArray); die;

		if(count($addonsArray)>=1)
		{
			DB::delete("delete from reservation_addons_variants_details where reservation_id = '$reserv_id'");
			$this->experiences_model->addReservationAddonDetails($reserv_id, $addonsArray);
		}
		//exit;
		if($locality_val=="" || $locality_val=='0')
		{
			//echo 'null';
			DB::update("update reservation_details set reservation_date='$final_date_format',reservation_time='$edit_time',no_of_persons='$party_size',reservation_status='edited' where id = '$reserv_id'");
		}
		else
		{
			//echo 'value is present';
			DB::update("update reservation_details set reservation_date='$final_date_format',reservation_time='$edit_time',no_of_persons='$party_size',vendor_location_id='$locality_val',reservation_status='edited' where id = '$reserv_id'");
		}
		//exit;
		DB::update("update reservation_details set giftcard_id='$giftcard_id', special_request = '$special_request' where id = '$reserv_id'");


		//code for new db structure changes
		$combined_date_and_time = $final_date_format . ' ' . $edit_time;
		$newDb['attributes']['reserv_datetime'] = Carbon::createFromFormat('Y-m-d H:i A',$combined_date_and_time)->toDateTimeString();
		$newDb['attributes']['no_of_people_booked'] = $party_size;
		$newDb['attributes']['gift_card_id_reserv'] = (isset($giftcard_id)) ? $giftcard_id : "";
		$newDb['attributes']['special_request'] = ($special_request) ? $special_request : "";
		$newDb['attributes']['reservation_status_id'] = 2;
		$newDb['userdetails']['user_id'] = $user_id;
		$newDb['userdetails']['status'] = 2;
		$newDb['userdetails']['addons'] = $addonsArray;
		//print_r($newDb);die;
		$reservDetails = new ReservDetailsModel();
		$newDbStatus = $reservDetails->updateAttributes($reserv_id,$newDb);
		$tokens = $reservDetails->pushToRestaurant($reserv_id);
		$this->restaurantapp->push($reserv_id,$tokens,true);
		//print_r($newDbStatus);die;
		/*TODO: Add the status of success check and include added_by and transaction_id attributes */
		//die;
		$userID = $user_id;
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
				'Date_of_Visit' => date('d-M-Y', strtotime($edit_date1)),
				'Time' => date("g:ia", strtotime($this->request->input('edit_time'))),
				//'Refferal' => (isset($ref['partner_name'])) ? $ref['partner_name'] : $google_add,
				'Type' => 'Experience',
				'API_added' => (($added_by == 'user') ? 'Web Updated' : 'Admin Updated'),
				'GIU_Membership_ID' =>$userData['data']['membership_number'],
				'Outlet' => $outlet->name,
				//'Points_Notes'=>$this->data['bonus_reason'],
				'AR_Confirmation_ID'=>'0',
				'Auto_Reservation'=>'Not available',
				'Order_completed'=>(($added_by == 'user') ? 'User Changed' : 'Admin Changed'),
				'Special_Request' => $addons_special_request,
				'gift_card_id_from_reservation' => $giftcard_id
			);

			//echo "<pre>E".sprintf("%06d",$reserv_id); print_r($zoho_data); die;

			$this->zoho_edit_booking('E'.sprintf("%06d",$reserv_id),$zoho_data);

			//echo "<pre> a ="; print_r($a); die;

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
							  'addons_special_request'=> $addons_special_request,
							  'giftcard_id'=> $giftcard_id,

			);
			//echo "<br/>---datapost---<pre>"; print_r($dataPost);die;
			Mail::send('site.pages.edit_experience_reservation',[
				'location_details'=> $locationDetails,
				'outlet'=> $outlet,
				'post_data'=>$dataPost,
				'productDetails'=>$productDetails,
			], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to($dataPost['guestEmail'])->subject('Your WowTables Reservation at '.$dataPost['venue'].' has been changed');
				//$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
			});

			$dataPost['admin_email'] = 1;
			$dataPost['final_reservation_oulet'] = $reservation_oulet;
			$dataPost['final_reservation_party_size'] = $reservation_party_size;
			$dataPost['final_reservation_date'] = $reservation_date;
			$dataPost['final_reservation_time'] = $reservation_time;
			$dataPost['final_giftcard_id'] = $giftcard_change;


			Mail::send('site.pages.edit_experience_reservation',[
				'location_details'=> $locationDetails,
				'outlet'=> $outlet,
				'post_data'=>$dataPost,
				'productDetails'=>$productDetails,
				], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to('concierge@wowtables.com')->subject('ER - #E'.$dataPost['order_id'].' | '.$dataPost['reservation_date'].' , '.$dataPost['reservation_time'].' | '.$dataPost['venue'].' | '.$dataPost['guestName']);
				$message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com','abhishek.n@wowtables.com');
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
				'Date_of_Visit' => date('d-M-Y', strtotime($edit_date1)),
				'Time' => date("g:i a", strtotime($this->request->input('edit_time'))),
				//'Refferal' => (isset($ref['partner_name'])) ? $ref['partner_name'] : $google_add,
				'Type' => 'Experience',
				'API_added' => (($added_by == 'user') ? 'Web Updated' : 'Admin Updated'),
				'GIU_Membership_ID' =>$userData['data']['membership_number'],
				'Outlet' => $outlet->name,
				//'Points_Notes'=>$this->data['bonus_reason'],
				'AR_Confirmation_ID'=>'0',
				'Auto_Reservation'=>'Not available',
				'Order_completed'=>(($added_by == 'user') ? 'User Changed' : 'Admin Changed'),
				'Special_Request' => $addons_special_request,
			);

			$this->zoho_edit_booking('A'.sprintf("%06d",$reserv_id),$zoho_data);

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
			Mail::send('site.pages.edit_restaurant_reservation',[
				'location_details'=> $locationDetails,
				'outlet'=> $outlet,
				'post_data'=>$dataPost,
				'productDetails'=>$vendorDetails,
			], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to($dataPost['guestEmail'])->subject('Your WowTables Reservation at '.$dataPost['venue'].' has been changed');
				//$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
			});

			$dataPost['admin_email'] = 1;
			$dataPost['final_reservation_party_size'] = $reservation_party_size;
			$dataPost['final_reservation_date'] = $reservation_date;
			$dataPost['final_reservation_time'] = $reservation_time;

			Mail::send('site.pages.edit_restaurant_reservation',[
				'location_details'=> $locationDetails,
				'outlet'=> $outlet,
				'post_data'=>$dataPost,
				'productDetails'=>$vendorDetails,
			], function($message) use ($dataPost){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to('concierge@wowtables.com')->subject('ER - #A'.$dataPost['order_id'].' | '.$dataPost['reservation_date'].' , '.$dataPost['reservation_time'].' | '.$dataPost['venue'].' | '.$dataPost['guestName']);
				$message->cc(['kunal@wowtables.com', 'deepa@wowtables.com']);
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

	/**
	 * Handles requst for displaying the complete_signup.
	 * record of the logged in user.
	 * 
	 * @access	public
	 * @param	string	$access_token
	 * @return	response
	 * @since	1.0.0
	 */
	public function completeSignup()
	{
		$data = array(
                'email'=>$_GET['email'],
                'phone'=>$_GET['phone_number'],
                'cityName'=>$_GET['city']
            );
		//print_r($data);
		$cities = Location::where(['Type' => 'City', 'visible' =>1,'name' =>$data['cityName']])->pluck('id');
		
		$data['city'] = $cities;
		$count = count($cities);
		if($count=='0')
		{
			die("City name doesn't exist.");
			exit;
		}
		else
		{
			return view('frontend.pages.completesignup',$data);
		}
	}

	public function zoho_edit_booking($order_id,$data){
		$ch = curl_init();
		$config = array(
			//'authtoken' => 'e56a38dab1e09933f2a1183818310629',
			// 'authtoken' => '7e8e56113b2c2eb898bca9916c52154c',
			'authtoken' => 'a905350ac6562ec91e9a5ae0025bb9b2',
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
		  //echo "<pre> results == "; print_r($result);die;
		curl_close($ch);
	}


}
