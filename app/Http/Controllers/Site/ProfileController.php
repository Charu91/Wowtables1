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
use Request;
class ProfileController extends Controller {


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
		$data = Request::all();
		
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




}
