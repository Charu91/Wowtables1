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

class RegistrationsController extends Controller {


	function __construct(Request $request, AlacarteModel $alacarte_model, ExperienceModel $experiences_model)
	{
		$this->request = $request;
		$this->alacarte_model = $alacarte_model;
		$this->experiences_model = $experiences_model;
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
		$vendorId = $product_id;
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
		$min_people = $data['reserveData'][$product_id]['min_people'];
 		$max_people = $data['reserveData'][$product_id ]['max_people'];
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
		$arrResponse = ReservationModel::cancelReservation($reservationID, $reservationType);

		if($arrResponse['status']=='ok')
		{
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
		$reserv_id = $this->request->input('reserv_id');
		$party_size = $this->request->input('party_size');
		$edit_date = $this->request->input('edit_date');
		$datearray=explode(" ",$edit_date);
		$date = trim(str_replace(range('a','z'),'',$datearray["0"]));
		$remove_comma = trim(str_replace(',','',$datearray["1"]));
		$month = str_pad($remove_comma, 2, "0", STR_PAD_LEFT); 
		$year = $datearray["2"];
		$final_date_format = $year.'-'.$month.'-'.$date;
		$edit_time = date("H:i:s", strtotime($this->request->input('edit_time')));

		DB::update("update reservation_details set reservation_date='$final_date_format',reservation_time='$edit_time',no_of_persons='$party_size',reservation_status='edited' where id = '$reserv_id'");
   		
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




}
