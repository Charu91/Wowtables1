<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Controllers\Controller;
use Config;

use WowTables\Http\Models\Frontend\ReservationModel;
use WowTables\Http\Models\Profile;
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
use Illuminate\Http\Request;
use Mail;
class ProfileController extends Controller {

	function __construct(Request $request){
		$this->request = $request;
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
	public function myProfile()
	{
		$user_array = Session::all();
		$arrResponse['user']   = Auth::user();
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
       $id = Session::get('id');
		
		$data=Profile::getUserProfileWeb($id);

        return view('frontend.pages.myaccount',$arrResponse)
        			->with('data',$data);
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
	public function updateInfo()
	{
		$user_array = Session::all();

		//$userID =Session::get('id');
		//this code is start in header and footer page.
        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $arrResponse['cities'] = $cities;
		$arrResponse['user']   = Auth::user();
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
       $id = Session::get('id');
		
		$data=Profile::getUserProfileWeb($id);

        return view('frontend.pages.updateinfo',$arrResponse)
        			->with('data',$data)->with('cities',$cities);
	}


	/**
	 * Handles requst for update the my account user info.
	 * record of the logged in user.
	 * 
	 * @access	public
	 * @param	string	$access_token
	 * @return	response
	 * @since	1.0.0
	 */
	public function updateUserinfo()
	{
		$user_array = Session::all();

       	$userID = Session::get('id');
		$data = $this->request->all();
		$data['user']   = Auth::user();
			$rules = array(
        		'full_name' => 'required',
				'zip_code' => 'required',
				'aniversary_date' => 'required',
				'phone_number' => 'required',
				'dob' => 'required',
				'gender' => 'required',
				'location_id' => 'required'				
			);

			$message = array(
				'required' => 'The :attribute is required', 
			);

			$validation = Validator::make($data, $rules, $message);

			if($validation->fails())
			{
				return Redirect::to('/users/updateinfo')->withErrors($validation);
			}
			else
			{
        	$arrResponse=Profile::updateProfileWeb($data, $userID);
        	return Redirect::to('/users/myaccount')
		                ->with('flash_notice', '');
		     }
	}


		/**
	 * Handles requst for displaying the redeemRewards page.
	 * record of the logged in user.
	 * 
	 * @access	public
	 * @param	string	$access_token
	 * @return	response
	 * @since	1.0.0
	 */
	public function redeemRewards()
	{
		$accessToken = $this->request->get('access_token');

		if($accessToken != ""){
			Session::flush();
			$accessDetails = UserDevices::getUserDetailsByAccessToken($accessToken);

			$user_array = Auth::loginUsingId($accessDetails);
			//echo "<pre>"; print_r($user_array);
			$userdata = array(
				'id'  => $user_array->id,
				'username'  => substr($user_array->email,0,strpos($user_array->email,"@")),
				'email'     => $user_array->email,
				'full_name' =>$user_array->full_name,
				'user_role' =>$user_array->role_id,
				'phone'     =>$user_array->phone_number,
				'city_id'   =>$user_array->location_id,
				'facebook_id'=>@$user_array->fb_token,
				'exp'=>"10",
				'logged_in' => TRUE,
			);
			Session::put($userdata);

		}
		$user_array = Session::all();
		//$userID =Session::get('id');
		//this code is start in header and footer page.
        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $arrResponse['cities'] = $cities;
		$arrResponse['user']   = Auth::user();

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
       $id = Session::get('id');
		
		//$data=Profile::getUserProfileWeb($id);

        return view('frontend.pages.redeemrewards',$arrResponse);
	}

	/**
	 * Handles requst for displaying the redeemRewards page.
	 * record of the logged in user.
	 * 
	 * @access	public
	 * @param	string	$access_token
	 * @return	response
	 * @since	1.0.0
	 */
	public function makeGiftcard()
	{
		$user_id = Input::get('gc_user_id');
		$gc_points = Input::get('gc_points');
		$gc_price = Input::get('gc_price');
		Auth::user()->points_earned;
		$email_id = Auth::user()->email ;
		
		$set_giftcard_id = 0;
		if( $gc_points <= Auth::user()->points_earned-Auth::user()->points_spent ){
				//echo "points avaiable";

				DB::insert("insert into redeem_giftcard(user_id)values($user_id)");
				$last_id = DB::select("select id from redeem_giftcard order by id desc limit 1");
				$membership_query = DB::select("select attribute_value from user_attributes_varchar where user_id=$user_id limit 1");
				//echo "sad = ".$last_id;
				$getMembership_number = $membership_query[0]->attribute_value;

				if(empty($getMembership_number))
				{
					$membership_number = 'NULL';
				}
				else
				{
					$membership_number = $membership_query[0]->attribute_value;
				}

				if(empty($last_id)){
					$set_giftcard_id = 1;
				}else{
					$set_giftcard_id = $last_id[0]->id;
				}
				
			
				$quantity = 1;
				if($gc_price == 500){
					$reward_id = 1;
					$description = "Redeemed Rs.500 - GPRED".sprintf("%04d",$set_giftcard_id);	
				}else if($gc_price == 1000){
					$reward_id = 2;
					$description = "Redeemed Rs.1000 - GPRED".sprintf("%04d",$set_giftcard_id);
				}else if($gc_price == 1500){
					$reward_id = 3;
					$description = "Redeemed Rs.1500 - GPRED".sprintf("%04d",$set_giftcard_id);
				}
				$pointsRedeemed = $quantity * $gc_points;
				DB::insert("insert into reward_points_redeemed(user_id,order_id,points_redeemed,description)
							values('$user_id','$reward_id','$pointsRedeemed','$description')");
				
				
				$spent = $gc_points * $quantity;
				
				DB::update('UPDATE users SET points_spent = points_spent + '.$spent.' WHERE id = '.$user_id);
				
				$sent = Mail::send('site.pages.rewards_request_mail',
						['quantity'=> $quantity,
						 'email_id'=> $email_id,
						 'membership_number'=> $membership_number,
						 'description'=> $description,
						 'points_spent'=> $spent,
						 'set_giftcard_id'=> $set_giftcard_id,], function($message) {
						$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

						$message->to('concierge@wowtables.com')->subject('Rewards Request');
						$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
				});
				if($sent)
				{
					$msg = 1;
				}
				else
				{
					$msg = 2;
				}

				 Mail::send('site.pages.rewards_request_mail_user',
						['quantity'=> $quantity,
						 'email_id'=> $email_id,
						 'membership_number'=> $membership_number,
						 'description'=> $description,
						 'points_spent'=> $spent,
						 'set_giftcard_id'=> $set_giftcard_id,], function($message) use ($email_id) {
						$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

						$message->to($email_id)->subject('Your Gourmet Rewards redemption request was successful');
						//$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
				});
				
			}else{
				$msg = 3;
			}
			echo json_encode ( array('message' => $msg,'giftcard_id' => 'GPRED'.sprintf("%04d",$set_giftcard_id)));
	}





}
