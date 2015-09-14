<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Giftcards;
use WowTables\GiftcardsAddonsPurchaseDetails;
use WowTables\Http\Controllers\Controller;

use App;
use Cookie;
use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Page;
use Session;
use Config;
use Response;
use WowTables\Http\Models\Eloquent\Location;
use WowTables\Http\Models\Eloquent\Products\Product;
use WowTables\Http\Models\Frontend\CommonModel;
use WowTables\Http\Models\Frontend\ExperienceModel;
use Input;
use WowTables\Http\Models\Eloquent\User;
use Validator;
use Hash;
use DB;
use Auth;
use Redirect;
use WowTables\Http\Models\Profile;
use Mail;
use WowTables\Http\Models\UserDevices;

class StaticPagesController extends Controller {

	function __construct(ExperienceModel $experiences_model,Request $request){
		$this->experiences_model = $experiences_model;
		$this->request = $request;
	}

	public function home()
	{
		/*Auth::user()->email;
		exit;*/
		$userRole = Auth::user()->role_id;

		if($userRole=='1' || $userRole=='2' || $userRole=='3' || $userRole=='4')
		{
			return Redirect::to('/mumbai');
		}
		else
		{
		return view('site.pages.home');
		}
	}

	public function loggedInHome()
	{
		$userRole = Auth::user()->role_id;
		$user = Auth::user();
		$city_name  = Location::where(['Type' => 'City', 'id' => $user->location_id])->pluck('name');
		if(empty($city_name))
		{
			$city_name = 'mumbai';
		}
		if($userRole=='1' || $userRole=='2' || $userRole=='3' || $userRole=='4')
		{
			//return Redirect::to('/mumbai');
			return redirect('/'.strtolower($city_name));
		}
		else
		{
		return view('site.users.home');
		}
	}

	public function giftCard()
	{

		//echo "<pre>"; print_r(Input::all()); die;
		$cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $arrResponse['cities'] = $cities;
        $arrResponse['user']   = Auth::user(); 

        $city_id    = Input::get('city');        
        $city_name      = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
        if(empty($city_name))
        {
            $city_name = 'mumbai';
        }

        $arrResponse['allow_guest']            ='Yes'; 
        $arrResponse['current_city']           = strtolower($city_name);
        $arrResponse['current_city_id']        = $city_id;

        return view('frontend.pages.giftcard',$arrResponse);
	}

	public function pages($pages="")
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
		//return view('site.users.home');
		//this code is start in header and footer page.
        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $arrResponse['cities'] = $cities;
        $arrResponse['user']   = Auth::user(); 

        $city_id    = Input::get('city');        
        $city_name      = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
        if(empty($city_name))
        {
            $city_name = 'mumbai';
        }

        $arrResponse['allow_guest']            ='Yes'; 
        $arrResponse['current_city']           = strtolower($city_name);
        $arrResponse['current_city_id']        = $city_id;

       /* $commonmodel = new CommonModel();
        $arrResponse['allCuisines']  = $commonmodel->getAllCuisines();
        $arrResponse['allAreas']  =   $commonmodel->getAllAreas();
        $arrResponse['allPrices']  = $commonmodel->getAllPrices();

        $arrResponse['dropdowns_opt']  = 1; //1 for disp*/
        //this code is start in header and footer page.
		$staticPage = DB::select("SELECT `page_title` , `page_contents` , `slug` , `path` , `seo_title` ,
								 `meta_desc` , `meta_keywords` FROM `cmspages` WHERE slug = '$pages'");

		//print_r($staticPage);
		$count = count($staticPage);
		//echo $count;
		//print_r(count($count));
		/*if($count==0)
		{
			echo 'city';
			//return Redirect::route('experience.lists');
			//return Redirect::action('Site\ExperienceController@lists', array('{city}' => 'mumbai'));
			//return Redirect::route('experience.lists');
			return route('experience.lists', 'mumbai');
		}
		else
		{
			return view('frontend.pages.aboutus',$arrResponse)->with('staticPage',$staticPage);
		}*/
		
		return view('frontend.pages.aboutus',$arrResponse)->with('staticPage',$staticPage);
		
	}
	/*
	 * function to get all experiences which are allows giftcard redemption
	 * */
	public function getGiftcardExperiences($city){
		//echo "city = ".$city;
		$city_id    = Location::where(['Type' => 'City', 'name' => $city])->first()->id;
		$data['city_name'] = $city;
		$data['gcExperiences'] = $this->experiences_model->getGiftcardExperiences($city_id);
		$cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
		$data['cities'] = $cities;
		$data['user']   = Auth::user();

		$city_id    = Input::get('city');
		$city_name      = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
		if(empty($city_name))
		{
			$city_name = 'mumbai';
		}

		$data['allow_guest']            ='Yes';
		$data['current_city']           = strtolower($city_name);
		$data['current_city_id']        = $city_id;
		//echo "<pre>"; print_r($data['gcExperiences']); die;
		return view('frontend.pages.giftcardExperiences',$data)->with('relatedExperiences',$data);
	}

	/*
	 * function to get the gift card experiences according to the city selected
	 * */
	public function getGiftcardExperiencesFromCity(Request $request){
		$city_id = $request->get('city');

		if($city_id != 0 && $city_id != ""){
			$gcExperiences = $this->experiences_model->getGiftcardExperiences($city_id);
			//echo "<pre>"; print_r($gcExperiences); die;
			$content = '';
			foreach($gcExperiences as $row){
				$content.='<li><a href="javascript:" class="list-group-item" rel="'.$row['slug']."|".$row['product_id']."|".$row['post_tax_price']."|".$row['vendor_location_id'].'">
                    <h5 class="list-group-item-heading">'.$row['vendor_name'].' - '.$row['productname'].'</h5>
                    <p class="list-group-item-text" rel="'.$row["descriptive_title"].'">'.$row['vendor_name'].' - '.$row['descriptive_title'].'</p>
                    <input type="hidden" name="per_person_price" id="per_person_price" value="'.$row["price"].'">
                    <input type="hidden" name="exp_loc" id="exp_loc" value="'.\URL::to('/').'/'.$row['cityname'].'/experiences/'.$row['slug'].'">
                    <input type="hidden" name="product_id" id="product_id" value="'.$row['product_id'].'">
                    </a></li>';
			}
			echo json_encode($content);die;
		}

	}

	/*
	 * function to get the gift card experiences according to the city selected
	 * */
	public function getExperiencesRelatedAddons(Request $request){
		$product_id = $request->get('pid');
		//echo "product_id =="+$product_id;
		if($product_id != 0 && $product_id != ""){
			$gcExperiences = $this->experiences_model->getExperiencesAddons($product_id);

			$content = '';
			$content1 = '';
			$number_people = array(0,1,2,3,4,5,6,7,8,9,10);
			foreach($gcExperiences as $row){
				$content.='<span class="col-md-5 col-xs-5">'.$row['addonName'].':
						   	<select class="giftcard_addons_list" data-addonid = "'.$row['addonID'].'" id="addon_'.$row['addonID'].'" name="addons['.$row['addonID'].']">';
						   	foreach($number_people as $n){
								$content.= '<option value="'.$n.'"> '.$n.' </option>';
							}
				$content.= '</select>
						   	<input type="hidden" name="addon_price" id="'.$row['addonID'].'" value="'.$row['post_tax_price'].'">
						   </span>';

				//$content1.='';
				$content1.='<p class="col-md-12">'.$row['addonName'].': <span>Rs. '.$row['post_tax_price'].' per person (Inclusive taxes)</span></p>';
			}
			echo json_encode(array('content'=>$content,'addons_content'=>$content1));die;
		}

	}

	/*
	 * function giftcard page checkout
	 * */
	public function giftcardCheckout(Request $request)
	{
		//echo "<pre>";print_r(Input::all()); //die;

		$totalAddons = $request->get('addons');
		//echo "<pre>"; print_r($totalAddons); die;
		$receiverName = $request->get('receiver_name');
		$receiverEmail = $request->get('receiver_email');
		$giftcardType = $request->get('gift_opt');
		if ($giftcardType == 2) {
			$explodePerpersonAmount = explode('|', $request->get('gift_choose_exp'));
			$perpersonAmount = $explodePerpersonAmount[2];
			$productId = $explodePerpersonAmount[1];
			$noOfGuests = $request->get('gift_no_people');
			$short_description = $explodePerpersonAmount[0];
			$totalAmount1 = $request->get('grandTotal');
		}else{
			$totalAmount1 = $request->get('amount');
			$short_description = "Cash value GiftCard of Rs. ".$totalAmount1;
			$perpersonAmount = 0;
			$productId = 0;
			$noOfGuests = 0;
		}

		$sendingType = $request->get('gift_send');
		$specialInstructions = $request->get('special_instructions');
		if($sendingType == "mail"){
			$totalAmount = $totalAmount1 + 50;
		} else {
			$totalAmount = $totalAmount1;
		}
		//echo "sad = ".$totalAmount; die;
		$mailingAddress = $request->get('mailing_address');
		$userID = $request->get('userid');

		$giftcard = new Giftcards();
		$giftcard->receiver_name = $receiverName;
		$giftcard->receiver_email = $receiverEmail;
		$giftcard->order_type = $giftcardType;
		if ($giftcardType == 2) {
			$giftcard->amount = $perpersonAmount;
			$giftcard->experience_id = $productId;
			$giftcard->no_of_guests = $noOfGuests;
		}
		$giftcard->total_amount = $totalAmount;
		$giftcard->sending_type = $sendingType;
		$giftcard->mailing_address = $mailingAddress;
		$giftcard->user_id = $userID;

		$giftcard->save();
		$insertedId = $giftcard->id;


		if (!empty($totalAddons)) {
			foreach ($totalAddons as $addon => $value) {
				if ($value > 0) {
					$giftcardAddon = new GiftcardsAddonsPurchaseDetails();
					$giftcardAddon->giftcards_purchase_details_id = $insertedId;
					$giftcardAddon->no_of_guests = $value;
					$giftcardAddon->addon_id = $addon;
					$giftcardAddon->save();
				}

			}
		}
		//echo $insertedId; die;
		$userData = Profile::getUserProfileWeb($userID);

		//echo "<pre>"; print_r($userData); die;

		if ($insertedId > 0) {

			//echo "prepaid is true";
			Session::forget('purchase_giftcard_session');
			$cookiearray = array(
				'guestName' => $userData['data']['full_name'],
				'guestEmail' => $userData['data']['email'],
				'phone' => $userData['data']['phone_number'],
				'order_id' => 'G'.$insertedId,
				'actual_order_id' => $insertedId,
				'total_amount' => $totalAmount,
				'short_description' => $short_description,
				'receiverName' => $receiverName,
				'receiverEmail' => $receiverEmail,
				'giftcardType' => $giftcardType,
				'productId' => $productId,
				'noOfGuests' => $noOfGuests,
				'sendingType' => $sendingType,
				'mailingAddress' => ($mailingAddress == "" ? 0 : $mailingAddress ),
				'specialInstructions' => ($specialInstructions == "" ? 0 : $specialInstructions ),
				'userID' => $userID,
			);

			//echo "<pre>"; print_r($cookiearray); die;
			$city_id = Input::get('city');
			$city_name = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
			if (empty($city_name)) {
				$city_name = 'mumbai';
			}

			$cookiearray['allow_guest'] = 'Yes';
			$cookiearray['current_city'] = strtolower($city_name);

			$cookiearray['current_city_id'] = $city_id;

			Session::put('purchase_giftcard_session', $cookiearray);

			//die;
			//echo "<pre>sad = "; print_r($_COOKIE); die;
			return view('site.pages.giftcard_payment', ['cookie_array' => $cookiearray]);


		}
	}

	public function process_response(Request $request){
		//echo "<pre>"; print_r(Input::all());
		//echo $request->get('status'); //die;
		$requestarray = Input::all();
		$fetch_cookie = Session::get('purchase_giftcard_session');
		//echo "<pre>"; print_r($fetch_cookie); //die;
		$id = $fetch_cookie['actual_order_id'];
		//echo "order id = ".$id;
		if($request->get('status') == "success"){
			$details = '<table width="600" cellpadding="2" cellspacing="2" border="0">
        <tr>
            <th colspan="2">Transaction Details</th>
        </tr>';
			foreach( $requestarray as $key => $value) {
				$details .= '<tr>
                <td class="fieldName" width="50%">'. $key.'</td>
                <td class="fieldName" align="left" width="50%">'. $value.'</td>
            </tr>';
			}
			$details .= '</table>';

			$transaction['user_id']=$fetch_cookie['userID'];
			$transaction['response_code']=$requestarray['unmappedstatus'];
			$transaction['response_message']=$requestarray['status'];
			$transaction['transaction_date']=date('Y-m-d H:i:s');
			$transaction['reservation_id']=$id;
			$transaction['amount_paid']=$requestarray['amount'];
			$transaction['transaction_number']=$requestarray['mihpayid'];
			$transaction['transaction_details']=$details."~~".$requestarray['status'];
			$transaction['source_type']="gift_cards";

			$lastTransactionID = DB::table('transactions_details')->insertGetId($transaction);

			Giftcards::updateDetails($id,$lastTransactionID);

			if($fetch_cookie['giftcardType'] == 2){
				$experienceDetails = Giftcards::getExperienceDetails($id);
				$addonsDetails = Giftcards::getAddonsDetails($id);

				$fetch_cookie['experienceName'] = $experienceDetails[0]->name;
				$addonsString = '';
				foreach($addonsDetails as $addons){
					$addonsString .= $addons->name.": (".$addons->no_of_guests."),";
				}
				$addonsDetail = rtrim($addonsString,",");
				$fetch_cookie['addonsDetail'] = $addonsDetail;
			}
			$fetch_cookie['paymentStatus'] = $request->get('status');
			//echo "<pre>sd = "; print_r($fetch_cookie);
			//die;

			Mail::send('site.pages.giftcard_purchase_confirmation',[
				'giftcard'=> $fetch_cookie,
			], function($message) use ($fetch_cookie){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to($fetch_cookie['guestEmail'])->subject('New gift card purchase order no. '.$fetch_cookie['order_id']);
				$message->bcc('concierge@wowtables.com');
			});
			//$dataPost['admin']  = "yes";
			Mail::send('site.pages.giftcard_purchase_confirmation',[
				'giftcard'=> $fetch_cookie,
			], function($message) use ($fetch_cookie){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

				$message->to('concierge@wowtables.com')->subject('WowTables Gift Card Purchase Confirmation '.$fetch_cookie['order_id']);
				$message->cc(['kunal@wowtables.com', 'deepa@wowtables.com']);
			});


			$cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
			$arrResponse['cities'] = $cities;
			$arrResponse['user']   = Auth::user();

			$city_id    = Input::get('city');
			$city_name      = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
			if(empty($city_name))
			{
				$city_name = 'mumbai';
			}

			$arrResponse['allow_guest']            ='Yes';
			$arrResponse['current_city']           = strtolower($city_name);
			$arrResponse['current_city_id']        = $city_id;

			return view('frontend.pages.giftcard_thankyou',$arrResponse)->with('orderDetails',$fetch_cookie);


		}
	}
}
