<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Controllers\Controller;
use App;
use Cookie;
use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Page;
use WowTables\Http\Models\Eloquent\User;
use Session;

use Config;
use WowTables\Http\Models\Eloquent\Location;
use WowTables\Http\Models\Frontend\RegpageModel;
use WowTables\Http\Models\Frontend\CustomerModel;
use WowTables\Http\Requests\Site\CustomerLoginUserRequest;
use WowTables\Http\Models\Password;
use Input;
use Validator;
use Hash;
use DB;
use Auth;
use Redirect;
use Mail;
use Mailchimp;

class HomePageController extends Controller {

    protected $listId = '986c01a26a';

    function __construct(CustomerModel $customermodel,Request $request , Mailchimp $mailchimp){
         $this->customermodel = $customermodel;
         $this->request = $request;
         $this->mailchimp = $mailchimp;
    }

	public function home()
	{
		$cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
		$data['base_url'] = "/";
        $data['cities'] = $cities;
        $data['hasOrder'] = Session::get('hasorder');
        if(empty($data['hasOrder'])){
           $data['hasOrder'] = false; 
        }

        $repagemodel = new RegpageModel();
        $bg_images = $repagemodel->get_images();
        $data['journal'] = $repagemodel->get_journal();
        $data['testimonials'] = $repagemodel->get_testimonials();

        Session::forget('reservation_location');
		$checkLastLogin = Session::get('last_login');
		//echo " checkLastLogin = ".$checkLastLogin;
		if(isset($checkLastLogin) && $checkLastLogin != "" && $checkLastLogin != "0000-00-00 00:00:00" )
			$setUserStatus = array('new_user_status'=>'false');
		else
			$setUserStatus = array('new_user_status'=>'true');

		Session::put($setUserStatus);

		$image = array();
        $info = array();
        $i = 0;
        
        foreach($bg_images as $bgimage ){
            $image[] = Config::get('constants.API_LISTING_IMAGE_URL').$bgimage['file'];
            $inf = array();
            $inf['id'] = $bgimage['id'];
            $inf['title'] = $bgimage['title'];
            $inf['description'] = $bgimage['description'];
            $info[$i] = $inf; 
            $i++;
        }

        $data['bg_images'] = $image;
        $data['info'] = $info; 

        if(Cookie::get('inform_rebranding') == null)
		{
			return response()->view('frontend.pages.home',$data);
		}
		else{
			return response()->view('frontend.pages.home',$data);
    	}
	}

	public function show($slug)
	{
		$page = Page::where('slug',$slug)->where('status','Active')->first();

		if( $page != null )
		{
			return view('site.pages.static_page',[
				'page'=>$page,
				'seo_title' => $page->seo_title,
				'seo_meta_description' => $page->seo_meta_description,
				'seo_meta_keywords' => $page->seo_meta_keywords
			]);
		}
		else {
			App::abort('404');
		}
	}

	public function set_reservation_location()
	{
		$pageLoc = Input::get('resv_loc');
        if($pageLoc != ''){
           Session::forget('reservation_location');

           Session::put('reservation_location',$pageLoc);
           echo json_encode(Session::get('reservation_location'));
        }
	}

	public function login()
	{
		$customerLoginUserRequest = new CustomerLoginUserRequest();
		$login = $this->customermodel->login($this->request->input('email'), $this->request->input('password'), $this->request->input('remember_me') ? 1 :0);
       /* echo print_r($login);
        exit;*/
        if($login['state'] === 'success'){
			$user_array  =  Auth::user();
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

            $order = unserialize(Cookie::get('order'));
			
			/*
            //Start MailChimp
            require_once 'MCAPI.class.php'; //specify the proper path
            $apikey = 'f60ffa078492fad96cb1575e38c86160-us5';
            $listId = '986c01a26a';
            $api = new MCAPI($apikey);
            if(!isset($_GET["utm_source"])) $_GET["utm_source"] = "";
            if(!isset($_GET["utm_medium"])) $_GET["utm_medium"] = "";
            if(!isset($_GET["utm_campaign"])) $_GET["utm_campaign"] = "";
            $merge_vars = array(
                'NAME'         =>     isset($userdata['full_name'] )? $userdata['full_name']: '',
                'SIGNUPTP'     =>     isset($facebook)? 'Facebook': 'Email',
                'BDATE'     =>    isset($userdata['dob'])? $userdata['dob']: '',
                'GENDER'    =>  isset($userdata['gender'])? $userdata['gender']: '',
                'MERGE11'  => 0,
                'MERGE17'=>'Has GIU account',
                'MERGE18'=>$_GET["utm_source"],
                'MERGE19'=>$_GET["utm_medium"],
                'MERGE20'=>$_GET["utm_campaign"]
            );
            $api->listSubscribe($listId, $userdata['email'], $merge_vars,"html",false,true );
            $mergeVars = array(
            'GROUPINGS' => array(
            array(
                'id' => 9613, 
                'groups' => ucfirst($userdata['city']),
            )
            )); 
            $api->listUpdateMember($listId, $userdata['email'], $mergeVars);
        	//End MailChimp 
			*/
            flash()->success('You are now logged In!');
            if(!empty($order['slug'])){
                return Redirect::route('experience.lists',['city'=>$order['slug']]);
                //redirect('/experiences/'.$order['slug']);    
            }else if (Cookie::get('gift_order')){
                return redirect()->route('gift-cards');
            }else if (Input::get('url')){
                redirect(base_url(Input::get('url')));
            }
            else{
                

                $city_name  = Location::where(['Type' => 'City', 'id' => $user_array->location_id])->pluck('name');
                if(empty($city_name))
                {
                    $city_name = 'mumbai';
                }
                 return redirect('/'.strtolower($city_name));
            }           
            //return redirect()->route('SiteHomePageLoggedIn');
        }else{
             Session::flush();
            return redirect()->back()->withErrors($login['message'])->withInput();
        }
	}

	public function checkemail()
    {
        $gothrough = Input::get('gothrough');
        $res = User::where('email',Input::get('email'))->get()->toArray();
        
        if(count($res)>=1)
        {
            $user_array=$res[0];
            if(isset($user_array['password']) && $user_array['password']=='' && $user_array['fb_token']!=0){
                echo "Log in with facebook please";                
            }
            else
            {
	            if($gothrough == "gothrough"){
	                echo "User already exists. Please <a href='#signin'  data-toggle='tab' class='sign_in' style='color:#EAB803;text-decoration: underline;'>Sign In</a>.";   
	            } else {
	                echo "User already exists. Please <a href='javascript:void(0)' class='sign_in' style='color:#EAB803;text-decoration: underline;'>Sign In</a>.";   
	            } 
             }          
        }
    }

    public function check_user(Request $request)
    {
        /*if($request->ajax()){
                return "AJAX";
            }
            return "HTTP";
            exit;*/
        $customerLoginUserRequest = new CustomerLoginUserRequest();
        $all_res = $this->customermodel->login($this->request->input('email'), $this->request->input('password'), $this->request->input('remember_me') ? 1 :0);
        //echo "<pre>"; print_r($all_res); exit;
          if($all_res['state'] === 'success'){
            $user_array  =  Auth::user();
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
        $encval = json_encode($all_res);
        return $encval;
    }

    public function register()
    {
    	$rules = [
            'email' =>'required|email',
            'password' => 'required',
            'city' =>'required'
        ];

        $validator = Validator::make(Input::all(),$rules);
        
        if ($validator->fails()) {
            $string_msg = "";

            $messages = head($validator->messages());
            // echo "<pre>";
            // print_r(Input::all());exit;
            foreach (head($messages) as $key => $value) {
                $string_msg .=$value."\n"; 
            }
            echo $string_msg; 
        }

         $res = User::where('email',Input::get('email'))->get()->toArray();

        if(count($res)>=1){
            echo "This email address is already registered with WowTables.com ";               
        }else{
            
            $google_add 			= Cookie::get('google_add_words');

            $users['email_address'] = Input::get('email');
            $users['password'] 		= Input::get('password');
            $users['city'] 			= Input::get('city');
            
            $users['full_name'] 	= Input::get('full_name'); 
            $login_type				= Input::get('login_type');
            $reg_page 				= Input::get('reg_page');
 			

            $gourmetRoleId = DB::table('roles')->where('name', 'Gourmet')->pluck('id');
            $users['role_id'] = $gourmetRoleId;

            $users['google_addwords'] = (isset($google_add) && $google_add == 1) ? "1" : "0" ;
            $users['last_login'] = '0000-00-00 00:00:00';
            $users['facebook_id'] = 0;
            if($users['facebook_id']!=0){
                $facebook_id = $users['facebook_id'];
            }
            
            $registerinfo = $this->customermodel->register($users);
            $last_id = $registerinfo['user_id'];



            // update membership_number
            // update email_whitelist
            if($registerinfo['state'] === 'success'){

                    if(isset($login_type) && $login_type != ""){
                        $set_login_type= $login_type;
                    } else {
                        $set_login_type= "Email";
                    }
                    if(isset($reg_page) && $reg_page != ""){
                        $set_reg_page= $reg_page;
                    } else {
                        $set_reg_page= "http://www.wowtables.com/registration";
                    }



                    $newdata = array(
                        'id'  => $last_id,
                        'username'  => substr($users['email_address'],0,strpos($users['email_address'],"@")),
                        'email'     => $users['email_address'],
                        'logged_in' => TRUE,
                        'city' => $users['city']
                   );
                    $newdata['add_mixpanel_event'] = 'yes';
                    $newdata['login_type'] = $set_login_type;
                    $newdata['Registration_Page'] = $set_reg_page;
                    Session::put($newdata);

$mailbody = "
You are now a WowTables VIP!

Thank you for joining WowTables.com!

Your account details are:
Email:".$users['email_address']."
Password:".$users['password']."

We add new experiences from the best restaurants in Mumbai,Pune,Delhi,Bengaluru every week. So the next time you feel like treating yourself or a loved one to something special just check out WowTables.com or call our concierge at 9619551387 to make a booking. We hope you will enjoy what we have to offer.

Happy Dining,
The WowTables Team";

                $city_name      = Location::where(['Type' => 'City', 'id' => $users['city']])->pluck('name');
                if(empty($city_name))
                {
                    $city_name = 'mumbai';
                }


					/*Mail::raw($mailbody, function($message) use ($users)
					{
					    $message->from('info@wowtables.com', 'WowTables by GourmetItUp');

					    $message->to($users['email_address'])->subject('Registration with WowTables');
					});*/

            	  /*$newdata = array(
						'id'  => $last_id,
						'username'  => substr($users['email_address'],0,strpos($users['email_address'],"@")),
						'email'     => $users['email_address'],
						'logged_in' => TRUE,
						'city' => $users['city']
				   );
					$newdata['add_mixpanel_event'] = 'yes';
					$newdata['login_type'] = $set_login_type;
					$newdata['Registration_Page'] = $set_reg_page;*/
                    

                    //$this->email->send();
                    //Start MailChimp
                    //require_once 'MCAPI.class.php'; //specify the proper path

                    $merge_vars = array(
                        'NAME'         =>     isset($users['full_name'] )? $users['full_name']: '',
                        'SIGNUPTP'     =>     isset($facebook_id)? 'Facebook': 'Email',
                        'BDATE'     =>    isset($users['dob'])? $users['dob']: '',
                        'GENDER'    =>  isset($users['gender'])? $users['gender']: '',
                        'MERGE11'  => 0,
                        'MERGE17'=>'Has WowTables account',
                        'PHONE'=>   isset($users['phone'])? $users['phone']: '',
                        'MERGE18'=>$_GET["utm_source"],
                        'MERGE19'=>$_GET["utm_medium"],
                        'MERGE20'=>$_GET["utm_campaign"]
                    );

                    $this->mailchimp->lists->subscribe($this->listId, ['email' => $_POST['email']],$merge_vars,"html",false,true );
                    //echo "<pre>"; print_r($api); die;
                    //$api->listSubscribe($listId, $_POST['email'], $merge_vars,"html",false,true );
                    $my_email = $users['email_address'];
                    //$city = $users['city'];
                    $city = $city_name;
                        $mergeVars = array(
                            'GROUPINGS' => array(
                                array(
                                    'id' => 9613,
                                    'groups' => ucfirst($city),
                                )
                            )
                        );
                //echo "asd , ";
                $this->mailchimp->lists->updateMember($this->listId, $my_email, $mergeVars);

                    //End MailChimp
                     echo 1;
            }//
            else
            {
            	echo $registerinfo['message'];
            }
           
        }
    }

    public function forgot_password(){

        $data['email'] = $_POST['forgotemail'];
        $validator = Validator::make($data,Password::$arrPasswordRule);

        if($validator->fails()){
            $message=$validator->messages();
            $errorMessage="";
            foreach($data as $key => $value) {
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n';
                }
            }
            echo '<p style="color: #eab803;">The email address you have entered is not registered on WowTables. Please use an alternative email address to login or call our concierge for assistance.</p>';
        }
        else{
            Password::requestWebsitePassword($data);

            echo '<p style="color: #eab803;">Please check your email for a forgot password link</p>';
        }
    }

    public function newPassword() {
        //echo "sd"; //die;
        $data = $this->request->all();
        //echo "<pre>"; print_r($data); //die;
        //Validation user's profile data
        $validator = Validator::make($data,Password::$arrWebsitePasswordConfirm);

        if($validator->fails()){
            $message=$validator->messages();
            foreach($data as $key => $value) {
                $errorMessage = '';
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n ';
                }
            }
            $arrResponse['status'] = Config::get('constants.API_ERROR');
            $arrResponse['msg'] = $errorMessage;
            //return response()->json($arrResponse,200);
            if($arrResponse['status'] == "FAIL"){
                return view('frontend.pages.invalid_request',$arrResponse);
            }
        }
        else{

            //print_r($data['password']) ; die('..Ready Here');
            $arrData['password']=$data['password'];
            $arrData['token']=$data['token'];
            //echo "<pre>"; print_r($arrData); die;
            $response = Password::updatePasswordDatabase($arrData);

            $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
            $arrResponse['cities'] = $cities;

            $city_id    = Input::get('city');
            $city_name      = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
            if(empty($city_name))
            {
                $city_name = 'mumbai';
            }

            $arrResponse['allow_guest']            ='Yes';
            $arrResponse['current_city']           = strtolower($city_name);

            $arrResponse['current_city_id']        = $city_id;

            if($response['status'] == "OK"){
                return view('frontend.pages.success_forgot_password',$arrResponse);
            } else {
                return view('frontend.pages.invalid_request',$arrResponse);
            }




        }

    }


    public function verifyResetToken($token,$id) {
        //echo "sadsad"; die;
        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $arrResponse['cities'] = $cities;

        $city_id    = Input::get('city');
        $city_name      = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
        if(empty($city_name))
        {
            $city_name = 'mumbai';
        }

        $arrResponse['allow_guest']            ='Yes';
        $arrResponse['current_city']           = strtolower($city_name);

        $arrResponse['current_city_id']        = $city_id;
        $data=array('token' => $token );

        $validator = Validator::make($data,Password::$arrPasswordToken);
        //echo "<pre>"; print_r($validator); die;
        if($validator->fails()){

            $message=$validator->messages();
            foreach($data as $key => $value) {
                $errorMessage = '';
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n ';
                }
            }
            $arrResponse['status'] = Config::get('constants.API_ERROR');
            $arrResponse['msg'] = $errorMessage;
            //return response()->json($arrResponse,200);
            if($arrResponse['status'] == "FAIL"){
                return view('frontend.pages.invalid_request',$arrResponse);
            }

        }
        else{

            $getUserDetails = $this->customermodel->getUserDetailsFromToken($token);
            //echo "<pre>"; print_r($getUserDetails); die;
            return view('frontend.pages.forgot_password',$arrResponse,[
                'data'=>$getUserDetails
            ]);
        }

        //return response()->json($arrResponse,200);
    }
}
