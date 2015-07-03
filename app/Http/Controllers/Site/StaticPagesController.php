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

class StaticPagesController extends Controller {

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
		if($userRole=='1' || $userRole=='2' || $userRole=='3' || $userRole=='4')
		{
			return Redirect::to('/mumbai');
		}
		else
		{
		return view('site.users.home');
		}
	}

	public function giftCard()
	{
		
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
}
