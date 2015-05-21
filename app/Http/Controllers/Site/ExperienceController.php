<?php namespace WowTables\Http\Controllers\Site;

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

class ExperienceController extends Controller {
	
	function __construct(Request $request, ExperienceModel $experiences_model){
        $this->request = $request;
        $this->experiences_model = $experiences_model;
    }

    function index(){
    	return "hello experience";
    }

     function details($city='',$expslug = ''){
        DB::connection()->enableQueryLog();

        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $data['cities'] = $cities;

        //$cities = DB::table('locations')->where('Type','City')->select('name','id','visible')->get();


        $check_userid = '`order`,id desc';
        $data['user']   = Auth::user();
        $city_name      = 'mumbai';
       
        if(!empty($data['user']))
        {
            $users_city     = $data['user']->location_id;
            $user_id        =  $data['user']->id;
            $city_name      = Location::where(['Type' => 'City', 'id' => $users_city])->pluck('name');
            if(empty($city_name))
            {
                $city_name = 'mumbai';
            }

            $check_userid = "if(bookmark_userid = ".$user_id.", 0, if(bookmark_userid != ".$user_id.", 1, 1)),`order` asc";
        }
        $city_id    = Location::where(['Type' => 'City', 'name' => $city])->first()->id;
        
        $id = DB::table('products')->where('slug',$expslug)->first()->id;

        $data['allow_guest']            ='Yes'; 
        $data['current_city']           = strtolower($city);
        $data['current_city_id']        = $city_id;

        //$arrSubmittedData['city_id']    = $city_id;
       
        $arrExperience                  = $this->experiences_model->find($id);
        $data['arrExperience']          = $arrExperience;
        $data['reserveData']            = $this->experiences_model->getExperienceLimit($id);
        $data['block_dates']            = $this->experiences_model->getExperienceBlockDates($id);
        $data['schedule']               = $this->experiences_model->getExperienceLocationSchedule($id);
             
       /*echo '<pre>';
       //print_r( $time_range);
       print_r( $data['arrExperience']);
       //print_r(DB::getQueryLog());
       print_r( $data['reserveData']);
       print_r( $data['block_dates']);
       print_r( $data['schedule']);
       echo '</pre>';
      //  exit;*/

        $commonmodel = new CommonModel();
        $data['allCuisines']  = $commonmodel->getAllCuisines();
        $data['allAreas']  =   $commonmodel->getAllAreas();
        $data['allPrices']  = $commonmodel->getAllPrices();

        $data['dropdowns_opt']  = 1; //1 for disp


        return response()->view('frontend.pages.experiencedetails',$data);
    }


    function lists($city='',$start_from=0,$areas='',$cousines='',$prices=''){
    	
    	//DB::connection()->enableQueryLog();
        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $data['cities'] = $cities;

        //$cities = DB::table('locations')->where('Type','City')->select('name','id','visible')->get();


        $check_userid = '`order`,id desc';
        $data['user']   = Auth::user();
        $city_name      = 'mumbai';
       
        if(!empty($data['user']))
        {
            $users_city     = $data['user']->location_id;
            $user_id        =  $data['user']->id;
            $city_name      = Location::where(['Type' => 'City', 'id' => $users_city])->pluck('name');
            if(empty($city_name))
            {
                $city_name = 'mumbai';
            }

            $check_userid = "if(bookmark_userid = ".$user_id.", 0, if(bookmark_userid != ".$user_id.", 1, 1)),`order` asc";
        }
       
        $areas_footer = $areas; 

        if($city == '')
        {
            if (Input::has('signup'))
            {
               if(!empty($data['user'])){
                     $redirect_url = '/'.strtolower($city_name).'/?signup=true';
                } else {
                    $redirect_url = '/mumbai/?signup=true';
                }
            } 
            else if(!empty($data['user']))
            {
                $redirect_url = '/'.strtolower($city_name);                
            } 
            else 
            {
               $redirect_url = "/mumbai";
            }  
            return redirect()->route('experience.lists',[$redirect_url]);
        }

        
        $city_id    = Location::where(['Type' => 'City', 'name' => $city])->first()->id;
        
        $arrSubmittedData['city_id'] = $city_id;

        $searchResult = $this->experiences_model->findMatchingExperience($arrSubmittedData);       
                
        if(!empty($searchResult)) {
            //setting up the array to be formatted as json
            $data['resultCount'] = $searchResult['resultCount'];
            $data['data'] = (array_key_exists('data', $searchResult)) ? $searchResult['data']:array();
            $data['filters'] = $this->experiences_model->getExperienceSearchFilters();
        }
        else {
            $data['resultCount'] = 0;
        }

        $data['allow_guest']='Yes'; 
        $data['current_city']  = strtolower($city);
        $data['current_city_id']  = $city_id;
       

        

        $commonmodel = new CommonModel();
        $data['allCuisines']  = $commonmodel->getAllCuisines();
        $data['allAreas']  =   $commonmodel->getAllAreas();
        $data['allPrices']  = $commonmodel->getAllPrices();

        $data['dropdowns_opt']  = 1; //1 for disp

        if(Input::get('ref')){
            $refid = Input::get('ref'); 
        } else {
            $refid = Cookie::get('referral');
        }    
        if (!empty($refid)) {
            //$data['referral'] = $this->partners_model->get_row_by_refid($refid);
        }


        return response()->view('frontend.pages.experiencelist',$data);
    }

    public function sorting(){
        $city_id    = Input::get('city');
        $sortby     = Input::get('sortby');
        
        $city       = Location::where(['Type' => 'City', 'id' => $city_id])->first()->name;

        if($sortby == "popular"){
            $set_order = 'products.created_at';//order by if(flag_name like "popular", 0, if(flag_name not like "popular", 1, 2))';
            $set_order_type = 'ASC';
        } else if($sortby == "new"){
            $set_order = 'products.created_at';
            $set_order_type = 'DESC';
        }

        $data['current_city'] = $city;
        $data['sort_selected'] = $sortby;
        
        $arrSubmittedData['city_id'] = $city_id;
        $arrSubmittedData['orderby'] = $set_order;
        $arrSubmittedData['ordertype'] = $set_order_type;

        $searchResult = $this->experiences_model->findMatchingExperience($arrSubmittedData);     
        if(!empty($searchResult)) {
            //setting up the array to be formatted as json
            $data['resultCount'] = $searchResult['resultCount'];
            $data['data'] = (array_key_exists('data', $searchResult)) ? $searchResult['data']:array();
        }
        else {
            $data['resultCount'] = 0;
        }


        $restaurant_data_values = view('frontend.pages.experiencelistajax',$data)->render();
        $restaurant_data = str_replace(array('\r', '\n', '\t'),"",$restaurant_data_values);

        return Response::json(array('restaurant_data'=> $restaurant_data), 200);

    }

    public function search_filter()
    {
        //DB::connection()->enableQueryLog();
        $restaurant_value = Input::get('restaurant_val');
        $format_date_value = (Input::get('date_value') ? Input::get('date_value') : "");
        $time_value = Input::get('time_value');
        $price_start_range = Input::get('start_price');
        $price_end_with = Input::get('end_price');
        $arrAreasList = Input::get('area_values');
        $arrCuisineList = Input::get('cuisine_values');   
        $arrTagsList = Input::get('tags_values');   
        $arrVendorList = Input::get('vendor_value');   
             
        $search_city = Input::get('city');
       
        $city       = Location::where(['Type' => 'City', 'id' => $search_city])->first()->name;

        if(isset($format_date_value) && $format_date_value != "") {
            $day = strtolower(date('N',strtotime($format_date_value)));
            $explode_before_time = explode("/",$format_date_value);
            $date_value = $explode_before_time[2]."-".$explode_before_time[0]."-".$explode_before_time[1];

            $set_start_time = "11:00:00_".$day;
            $set_end_time = "23:59:00_".$day;
        }else{
            $format_date_value = '';
            $date_value = '';
            $day = '';
        }

        if($time_value != "") {
            
            if($time_value == "lunch") {
                $set_start_time = "11:00:00";
                $set_end_time = "14:00:00";
            } else if($time_value == "dinner"){
                $set_start_time = "18:00:00";
                $set_end_time = "23:59:00";
            } else {
                $change_start_time = strtotime("-30 minutes", strtotime($time_value));
                $set_start_time = date('H:i', $change_start_time).":00";
                $change_end_time = strtotime("+30 minutes", strtotime($time_value));
                $set_end_time = date('H:i', $change_end_time).":00";
            }

        } else {
            
            $set_start_time = "";
            $set_end_time = "";

        }

        $data['current_city'] = $city;
        $arrSubmittedData['city_id'] = $search_city;
        if(!empty($arrAreasList))
        {
            $arrSubmittedData['location'] = explode(',',$arrAreasList);
        }

        if(!empty($arrCuisineList))
        {
            $arrSubmittedData['cuisine']  = explode(',',$arrCuisineList);
        }

        if(!empty($arrTagsList))
        {
            $arrSubmittedData['tag']  = explode(',',$arrTagsList);
        }

        if(!empty($arrVendorList))
        {
            $arrSubmittedData['vendor']  = explode(',',$arrVendorList);
        }

        $arrSubmittedData['minPrice']       = $price_start_range; 

        if(!empty($price_end_with))
        {
            $arrSubmittedData['maxPrice']  = $price_end_with;
        }
        
        $searchResult = $this->experiences_model->findMatchingExperience($arrSubmittedData);       
                
        if(!empty($searchResult)) {
            //setting up the array to be formatted as json
            $data['resultCount'] = $searchResult['resultCount'];
            $data['data'] = (array_key_exists('data', $searchResult)) ? $searchResult['data']:array();
            $data['filters'] = $this->experiences_model->getExperienceSearchFilters();
        }
        else {
            $data['resultCount'] = 0;
            $data['filters']['locations']  = array();
            $data['filters']['cuisines']  = array();
            $data['filters']['tags']  = array();
        }

        $restaurant_data_values = view('frontend.pages.experiencelistajax',$data)->render();
        $restaurant_data = str_replace(array('\r', '\n', '\t'),"",$restaurant_data_values);

        return Response::json(array('restaurant_data'=> $restaurant_data,'area_count' => $data['filters']['locations'], 'cuisine_count' => $data['filters']['cuisines'], 'tags_count' => $data['filters']['tags']), 200);
        
    }

    public function new_custom_search()
    {
        //DB::connection()->enableQueryLog();

        $term_str   = Input::get('term');

        $term = strip_tags($term_str);
        $city = Input::get('city');
        
        $arrSubmittedData['city_id'] = $city;
        $arrSubmittedData['term']    = $term_str;

        $arrExpData = $this->experiences_model->getExperienceAreaCuisineByName($arrSubmittedData);
        echo json_encode($arrExpData);
    }

    public function exporder()
    {
        $dataPost['reservationDate'] = Input::get('booking_date');
        $dataPost['reservationDay'] =  date("D", strtotime($dataPost['reservationDate']));//
        $dataPost['reservationTime'] = Input::get('booking_time');
        $dataPost['partySize'] = Input::get('qty');
        $dataPost['vendorLocationID'] = Input::get('address');
        $dataPost['guestName'] = Input::get('fullname');
        $dataPost['guestEmail'] = Input::get('email');
        $dataPost['phone'] = Input::get('phone');
        $dataPost['reservationType'] = 'experience';
        $dataPost['specialRequest'] = Input::get('special');
        $dataPost['addon']              = Input::get('add_ons');
        //$dataPost['access_token'] = Session::get('id');

        $arrRules = array(
                            'reservationDate' => 'required|date',
                            'reservationDay' => 'required',
                            'reservationTime' => 'required',
                            'partySize' => 'required|integer',
                            'vendorLocationID' => 'required|not_in:0',
                            'guestName' => 'required|max:255',
                            'guestEmail' => 'required|email|max:255',
                            'phone' => 'required',
                            'reservationType' => 'required|in:experience,alacarte,event',
                            'specialRequest' => 'max:512'//,
                            //'reservationID' => 'sometimes|required|exists:reservation_details,id'
                        ) ;

        $validator = Validator::make($dataPost,$arrRules);
        
        if($validator->fails()) {
            $message = $validator->messages();
            $errorMessage = "";
            foreach($data as $key => $value) {
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n ';
                }
            }
            
           return redirect()->back()->withErrors($validator);          
        }
        else {
            $userID = Session::get('id');
        
            if($userID > 0) {
                //validating the information submitted by users
                $arrResponse = $this->experiences_model->validateReservationData($dataPost);
            
            if($arrResponse['status'] == 'success') {
                    $arrResponse = $this->experiences_model->addReservationDetails($dataPost,$userID);
                }
            }
            else {
                return redirect()->back()->withErrors($validator);   
            }
        }

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

        return response()->view('frontend.pages.thankyou',$arrResponse);
    }

    public function exporderexists()
    {
        $dataPost['reservationDate']    = Input::get('booking_date');
        $dataPost['reservationDay']     =  date("D", strtotime($dataPost['reservationDate']));
        $dataPost['reservationTime']    = Input::get('booking_time');
        $dataPost['partySize']          = Input::get('qty');
        $dataPost['vendorLocationID']   = Input::get('address');
        $dataPost['guestName']          = Input::get('fullname');
        $dataPost['guestEmail']         = Input::get('email');
        $dataPost['phone']              = Input::get('phone');
        $dataPost['reservationType']    = 'experience';
        $dataPost['specialRequest']     = Input::get('special');
        $dataPost['access_token']       = Session::get('id');

        $arrData = $this->experiences_model->validateReservationData($dataPost);
        echo json_encode($arrData);
    }
}