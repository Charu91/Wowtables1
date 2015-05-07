<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Controllers\Controller;
use App;
use Cookie;
use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Page;
use Session;
use Config;
use WowTables\Http\Models\Eloquent\Location;
use WowTables\Http\Models\Frontend\CommonModel;
use WowTables\Http\Models\Frontend\AlacarteModel;
use Input;
use WowTables\Http\Models\Eloquent\User;
use Validator;
use Hash;
use DB;
use Auth;
use Redirect;

class AlacarteController extends Controller {
	
	function __construct(Request $request, AlacarteModel $alacarte_model){
        $this->request = $request;
        $this->alacarte_model = $alacarte_model;
    }

    function index(){
    	return "hello alacarte";
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

        }
       
        if($city == '')
        {
            if (Input::has('signup'))
            {
               if(!empty($data['user'])){
                     $redirect_url = '/'.strtolower($city_name).'/alacarte/';
                } else {
                    $redirect_url = '/mumbai/alacarte';
                }
            } 
            else if(!empty($data['user']))
            {
                $redirect_url = '/'.strtolower($city_name).'/alacarte/';                
            } 
            else 
            {
               $redirect_url = "/mumbai".'/alacarte/';
            }  
            return redirect()->route('alacarte.lists',[$redirect_url]);
        }

        
        $city_id    = Location::where(['Type' => 'City', 'name' => $city])->first()->id;
        
        $arrSubmittedData['city_id'] = $city_id;

        $searchResult = $this->alacarte_model->findMatchingAlacarte($arrSubmittedData);       
                
        if(!empty($searchResult)) {
            //setting up the array to be formatted as json
            $data['resultCount'] = $searchResult['resultCount'];
            $data['data']       = (array_key_exists('data', $searchResult)) ? $searchResult['data']:array();
            $data['filters']    = $this->alacarte_model->getAlaCarteSearchFilters();
        }
        else {
            $data['resultCount'] = 0;
        }

        $data['allow_guest']='Yes'; 
        $data['current_city']  = strtolower($city);
       

        $commonmodel = new CommonModel();
        $data['allCuisines']  = $commonmodel->getAllCuisines();
        $data['allAreas']  =   $commonmodel->getAllAreas();
        $data['allPrices']  = $commonmodel->getAllPrices();
        

        if(Input::get('ref')){
            $refid = Input::get('ref'); 
        } else {
            $refid = Cookie::get('referral');
        }    
        if (!empty($refid)) {
            //$data['referral'] = $this->partners_model->get_row_by_refid($refid);
        }

        
        return response()->view('frontend.pages.alacartelist',$data);
    }

     function details($city='',$alaslug = ''){
        //DB::connection()->enableQueryLog();

        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $data['cities'] = $cities;
        //$data['allow_guest'] = true;
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
        //echo "==".$alacarte_id    = Vendor::where(['slug' => $alaslug])->first()->id;
        $aLaCarteID = DB::table('vendor_locations')->where('slug',$alaslug)->first()->id;
        $arrALaCarte = $this->alacarte_model->getALaCarteDetails($aLaCarteID);

        $data['arrALaCarte']= $arrALaCarte;
        $data['hasOrder']   =''; 
        $data['allow_guest']='Yes';
        $data['current_city']  = strtolower($city);
        


        $commonmodel = new CommonModel();
        $data['allCuisines']  = $commonmodel->getAllCuisines();
        $data['allAreas']  =   $commonmodel->getAllAreas();
        $data['allPrices']  = $commonmodel->getAllPrices();
        $data['dropdowns_opt']  = 0; //1 for disp

        return response()->view('frontend.pages.alacartedetails',$data);
    }
}