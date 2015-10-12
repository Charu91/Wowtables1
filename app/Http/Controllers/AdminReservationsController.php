<?php namespace WowTables\Http\Controllers;


use Illuminate\Support\Facades\URL;
use WowTables\Http\Models\AdminReservations;
use WowTables\Http\Models\Eloquent\Location;
use Input;
use Validator;
use DB;
use WowTables\Http\Models\Password;
use Mail;
use Mailchimp;
use WowTables\Http\Models\Frontend\ReservationModel;
use WowTables\Http\Models\Frontend\ExperienceModel;
use WowTables\Http\Models\Frontend\AlacarteModel;
use WowTables\Core\Repositories\Experiences\ExperiencesRepository;
use WowTables\Core\Repositories\Restaurants\RestaurantLocationsRepository;
use WowTables\Http\Models\Profile;
use WowTables\Http\Models\Eloquent\Reservations\ReservationDetails;
use Carbon\Carbon;

/**
 * Class AdminExperiencesController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="/admin/adminreservations")
 */

class AdminReservationsController extends Controller{

    protected $listId = '986c01a26a';

    public function __construct(Mailchimp $mailchimp,RestaurantLocationsRepository $alacarterepository,ExperiencesRepository $repository,ExperienceModel $experiences_model,AlacarteModel $alacarte_model) {
        $this->middleware('admin.auth');
        $this->mailchimp = $mailchimp;
        $this->experiences_model = $experiences_model;
        $this->repository = $repository;
        $this->alacarterepository = $alacarterepository;
        $this->alacarte_model = $alacarte_model;
    }

    public function index(){

        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');

        return view('admin.reservations.admin_reservations',['cities'=>$cities]);
    }

    public function checkUser(){
        //echo "asd"; die;
        $data = array('error'=>0);
        $dataPost = array();
        $dataPost['CustomerEmail'] = Input::get('email');
        $arrRules = array(
            'CustomerEmail' => 'required|email|max:255',
        ) ;

        $validator = Validator::make($dataPost,$arrRules);

        if($validator->fails()) {
            //echo "validation fails";
            $message = $validator->messages();
            $errorMessage = "";
            foreach($data as $key => $value) {
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n ';
                }
            }

            //return redirect()->back()->withErrors($validator);

            $data['error'] = 1;
            $data['errors']['email'] = $message;
        } else {
            $result = DB::select('SELECT * FROM users WHERE email = "'.$dataPost['CustomerEmail'].'"');
            if(count($result)>=1){
                $data['exists'] = true;
                $data['user'] = $result[0];
            } else{
                $data['exists'] = false;
            }
        }
        echo json_encode($data);die;
    }



    public function addMember(){

        $password = str_random(6);
        $phone = Input::get('phone');
        $cityid = Input::get('city');

        $user_id = DB::table('users')->insertGetId([
            'full_name' => Input::get('full_name'),
            'email' => Input::get('email'),
            'password' => bcrypt($password),
            'role_id' => 3,
            'location_id' => Input::get('city'),
            'phone_number' => $phone,
        ]);
        $data = array('full_name' => Input::get('full_name'),
                    'email' => Input::get('email'),
                    'phone_number' => Input::get('phone'),
                );

        if($user_id) {

            DB::table('user_attributes_varchar')->insert([
                'user_id' => $user_id,
                'user_attribute_id' => 7,
                'attribute_value' => '1' . str_pad($user_id, 6, '0', STR_PAD_LEFT),
            ]);

            $createPasswordRequest = Password::creatRequestWebsitePassword($data);
            $createPasswordRequest['password'] = $password;



            Mail::send('site.pages.website_admin_registration',[
                'data'=> $createPasswordRequest
            ], function($message) use ($createPasswordRequest)
            {
                $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                $message->to($createPasswordRequest['email'])->subject('You have been registered as a WowTables member!');
            });

            $city_name      = Location::where(['Type' => 'City', 'id' => $cityid])->pluck('name');
            if(empty($city_name))
            {
                $city_name = 'mumbai';
            }
            $city = ucfirst($city_name);
            $merge_vars = array(
                'NAME'         =>     isset($createPasswordRequest['userName'] )? $createPasswordRequest['userName']: '',
                'SIGNUPTP'     =>     'Email',
                'BDATE'     =>    '',
                'GENDER'    =>  '',
                'MERGE11'  => 0,
                'MERGE17'=>'Admin added account',
                'PHONE'=>   isset($phone) ? $phone: '',
                'GROUPINGS' => array(array('id' => 9713, 'groups' => [$city]))
            );

            $this->mailchimp->lists->subscribe($this->listId, ["email"=>$createPasswordRequest['email']],$merge_vars,"html",false,true );

            $my_email = $createPasswordRequest['email'];
            $success_message = "Email ".$my_email." has been registered as a member.";
            $data = array(
                'user_id'=>$user_id,
                'success_message'=>$success_message,
            );
            echo json_encode($data);
        }
    }

    public function restaurantSearch($restaurantName){

        $arrRestaurantsData = AdminReservations::getMatchingRestaurants($restaurantName);

        echo json_encode($arrRestaurantsData);
    }

    public function getRelatedResults(){
        $restaurant_name = Input::get('restaurant_val');

        $getRestaurantId = DB::select('SELECT id from vendors where name = "'.$restaurant_name.'"');

        if(isset($getRestaurantId[0]) && $getRestaurantId[0] != ""){

            $arrdata['experiences'] = AdminReservations::readRestaurantsExperiences($getRestaurantId[0]->id);
            $arrdata['alacarte'] = AdminReservations::getResturantLocations($getRestaurantId[0]->id);
            $arrdata['url'] = URL::to('');

            echo json_encode($arrdata);
        }
    }

    public function myReservationDetails(){

        $userID = Input::get('user_id');

        if(isset($userID) && $userID != "" && $userID > 0){
            $arrReservation = ReservationModel::getReservationRecord($userID);
            $arrUpcomingReservations = '';
            foreach ($arrReservation['data']['upcomingReservation'] as $data) {

                  $arrUpcomingReservations .= '<div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <div class="row">
                                                            <span class="lead col-md-8">';
                                                               if($data['type']=='experience' || $data['type']=='event')
                                                              {
                                                                  $arrUpcomingReservations .= $data['vendor_name'].': '.$data['name'];

                                                              }
                                                              else
                                                              {
                                                                  $arrUpcomingReservations .= $data['vendor_name'] .': '.'Classic Reservation';
                                                              }

                                $arrUpcomingReservations .= '</span>
                                                                <ul class="col-md-4 list-inline text-right">';
                                                                    if($data['type']=='experience' || $data['type']=='event'){
                                                                        $arrUpcomingReservations .= '<li>
                                                                                                        <a href="'.$data['type'].','.$data['vl_id'].','.$data['product_id'].','.$data['city_id'].'" class="btn btn-defaulbt tn-sm" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#editModal" id="change_reservation">Change</a>
                                                                                                     </li>';

                                                                    }else if($data['type'] == "alacarte") {
                                                                            $arrUpcomingReservations .= '<li>
                                                                                                            <a href="'.$data['type'].','.$data['vendor_location_id'].','.$data['vendor_location_id'].'" class="btn btn-defaulbt tn-sm" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#editModal" id="change_reservation">Change</a>
                                                                                                        </li>';

                                                                     }
                                                                            $arrUpcomingReservations .= '<li>';
                                                                                                     if($data['type'] == "experience"){
                                                                                                        $change_id = "cancel_reservation";
                                                                                                     } else if($data['type'] == "alacarte"){
                                                                                                        $change_id = "ac_cancel_reservation";
                                                                                                     }else if($data['type'] == "event"){
                                                                                                        $change_id = "event_reservation";
                                                                                                     }

                                                                                                            $arrUpcomingReservations .= '<a href="javascript:" class="btn btn-default btn-sm" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#cancelModal" id="'.$change_id.'" data-reserve-type="'.$data['type'].'">Cancel</a>';
                                                                            $arrUpcomingReservations .= '</li>
                                                                                <input type="hidden" value="'.$data['id'].'">
                                                                                <input type="hidden" value="'.$data['type'].'" class="reserv_typee">
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">';
                                                             if($data['type']=='experience') {
                                                                 $arrUpcomingReservations .= '<p class="res-desc"><strong>Description: '.$data['short_description'].'</strong></p>';
                                                            }


                                $arrUpcomingReservations .= '<div class="row">
                                                                <div class="col-md-4 col-sm-4 res-details">
                                                                    <ul>
                                                                        <li>
                                                                            <p class="text-warning"><em>Date</em></p>
                                                                            <p><strong>'.$data['dayname'].', '.date('F j Y',strtotime($data['date'])).'</strong></p>
                                                                        </li>
                                                                        <li>
                                                                            <p class="text-warning"><em>Time</em></p>
                                                                            <p><strong>'.date('h:i A',strtotime($data['time'])).'</strong></p>
                                                                        </li>
                                                                        <li>
                                                                            <p class="text-warning"><em>Number of guests</em></p>
                                                                            <p><strong>'.$data['no_of_persons'].'</strong></p>
                                                                        </li>
                                                                        <li>
                                                                            <p class="text-warning"><em>Reservation ID</em></p>
                                                                            <p><strong>EU-'.$data['id'].'</strong></p>
                                                                        </li>';

                                                                         if($data['type']=='experience') {
                                             $arrUpcomingReservations .= '<li>
                                                                                <p class="text-warning"><em>Experience</em></p>
                                                                                <p><strong><a href="'.URL::to('/').'/'.$data['city'].'/experiences/'.$data['product_slug'].'" target="_blank">View Details</a></strong></p>
                                                                            </li>';
                                                                         }



                                        $arrUpcomingReservations .= '</ul>
                                                                </div>
                                                                <div class="col-md-8 col-sm-8 res-location">
                                                                    <ul>
                                                                        <li>
                                                                            <p class="text-warning"><em>Outlet</em></p>
                                                                            <p><strong>'.$data['locality'].'</strong></p>
                                                                        </li>
                                                                        <li>
                                                                            <p class="text-warning"><em>Address</em></p>
                                                                            <address>
                                                                                <strong>'.$data['name'].'</strong><br>
                                                                                '.$data['address'].'<br>

                                                                            </address>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>';
             }
            $arrPastReservations = '';
             foreach ($arrReservation['data']['pastReservation'] as $data) {

                 $arrPastReservations .= '<div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <span class="lead col-md-8">';
                                                       if($data['type']=='experience')
                                                      {
                                                          $arrPastReservations .= $data['vendor_name'].' : '.$data['name'];
                                                      }
                                                      else
                                                      {
                                                          $arrPastReservations .= $data['name'] .' : '.'Classic Reservation';
                                                      }


                            $arrPastReservations .= '</span>
                                                </div>
                                            </div>
                                            <div class="panel-body">';
                     if($data['type']=='experience') {
                        $arrPastReservations .= '<p class="res-desc"><strong>Description: '.$data['short_description'].'</strong></p>';
                     }


                        $arrPastReservations .= '<div class="row">
                                                    <div class="col-md-4 col-sm-4 res-details">
                                                        <ul>
                                                            <li>
                                                                <p class="text-warning"><em>Date</em></p>
                                                                <p><strong>'.$data['dayname'].', '.date('F j Y',strtotime($data['date'])).'</strong></p>
                                                            </li>
                                                            <li>
                                                                <p class="text-warning"><em>Time</em></p>
                                                                <p><strong>'.date('h:i A',strtotime($data['time'])).'</strong></p>
                                                            </li>
                                                            <li>
                                                                <p class="text-warning"><em>Number of guests</em></p>
                                                                <p><strong>'.$data['no_of_persons'].'</strong></p>
                                                            </li>
                                                            <li>
                                                                <p class="text-warning"><em>Reservation ID</em></p>
                                                                <p><strong>EU-'.$data['id'].'</strong></p>
                                                            </li>';

                                                             if($data['type']=='experience') {
                                         $arrPastReservations .= '<li>
                                                                    <p class="text-warning"><em>Experience</em></p>
                                                                    <p><strong><a href="'.URL::to('/').'/'.$data['city'].'/experiences/'.$data['product_slug'].'" target="_blank">View Details</a></strong></p>
                                                                  </li>';
                                                             }

                                $arrPastReservations .= '</ul>
                                                    </div>
                                                    <div class="col-md-8 col-sm-8 res-location">
                                                        <ul>
                                                            <li>
                                                                <p class="text-warning"><em>Outlet</em></p>
                                                                <p><strong>'.$data['locality'].'</strong></p>
                                                            </li>
                                                            <li>
                                                                <p class="text-warning"><em>Address</em></p>
                                                                <address>
                                                                    <strong>'.$data['name'].'</strong><br>
                                                                    '.$data['address'].'<br>

                                                                </address>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
             }

            $newData['upcomings'] = $arrUpcomingReservations;
            $newData['previous'] = $arrPastReservations;

            echo json_encode($newData);
        }

    }

    public function getExp_info(){
        $product_id = Input::get('id');
        $city_id = Input::get('city_id');
        $pvl_id = Input::get('pvl_id');

        //echo "product-id = ".$product_id." , city_id = ".$city_id;
        $data['locations']            = $this->experiences_model->getProductLocations($product_id, $pvl_id,$city_id);
        $data['reserveData']            = $this->experiences_model->getExperienceLimitWithCity($product_id,$city_id);
        $data['block_dates']            = AdminReservations::getExperienceBlockDates($product_id);
        $data['exp_schedule']               = AdminReservations::getExperienceLocationSchedule($product_id);
        $data['scheduleDays']               = $this->experiences_model->getExperienceLocationScheduleDay($product_id);
        $data['availableDates']         = $this->experiences_model->getAvailableDates($data['block_dates'],$data['scheduleDays']);
        //echo "<pre>"; print_r($data['exp_schedule']); die;
        $dataEnddate               = AdminReservations::getExperienceEndDate($product_id);
        $data['schedule'] = Array
        (

            "mon" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )
            ),

            "tue" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )

            ),

            "wed" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )

            ),

            "thu" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )
            ),

            "fri" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )

            ),

            "sat" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )
            ),

            "sun" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )

            )

        );
        $data['addons']               = $this->experiences_model->readExperienceAddOns($product_id);
        //echo "<pre>"; print_r($data['schedule']); die;

        $ed = ((isset($dataEnddate[0]->end_date) && $dataEnddate[0]->end_date != "") ? date('Y-m-d',strtotime($dataEnddate[0]->end_date)) : "0000-00-00");
        if ($ed != '0000-00-00') {
            $tmp = explode('-', $ed);
            $endDate = 'new Date('.$tmp[0].','.($tmp[1]-1).','.$tmp[2].')';
        } else {
            $endDate = '\'\'';
        }
        $data['enddate'] = $endDate;
        //echo "<pre>"; print_r($data); die;
        echo json_encode($data);
    }

    public function getAla_info(){
        $vl_id = Input::get('id');
        $city_id = Input::get('city_id');

        $data['reserveData']    = $this->alacarte_model->getAlacarteLimit($vl_id);
        $data['ala_schedule']   = AdminReservations::getAlacarteLocationSchedule($vl_id);
        $data['block_dates']    = $this->alacarte_model->getAlacarteBlockDates($vl_id);
        $data['scheduleDays']   = $this->alacarte_model->getAlacarteLocationScheduleDays($vl_id);
        $data['availableDates'] = $this->alacarte_model->getAvailableDates($data['block_dates'],$data['scheduleDays']);
        //echo "<pre>"; print_r($data['ala_schedule']); print_r($data['scheduleDays']); print_r($data['availableDates']); die;
        $data['schedule'] = Array
        (

            "mon" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )
            ),

            "tue" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )

            ),

            "wed" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )

            ),

            "thu" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )
            ),

            "fri" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )

            ),

            "sat" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )
            ),

            "sun" => Array
            (
                "breakfast" => Array
                (
                    "03:00" => "3:00 AM",
                    "03:30" => "3:30 AM",
                    "04:00" => "4:00 AM",
                    "04:30" => "4:30 AM",
                    "05:00" => "5:00 AM",
                    "05:30" => "5:30 AM",
                    "06:00" => "6:00 AM",
                    "06:30" => "6:30 AM",
                    "07:00" => "7:00 AM",
                    "07:30" => "7:30 AM",
                    "08:00" => "8:00 AM",
                    "08:30" => "8:30 AM",
                    "09:00" => "9:00 AM",
                    "09:30" => "9:30 AM",
                    "10:00" => "10:00 AM",
                    "10:30" => "10:30 AM",
                    "11:00" => "11:00 AM",
                    "11:30" => "11:30 AM"
                ),

                "lunch" => Array
                (
                    "12:00" => "12:00 PM",
                    "12:30" => "12:30 PM",
                    "13:00" => "1:00 PM",
                    "13:30" => "1:30 PM",
                    "14:00" => "2:00 PM",
                    "14:30" => "2:30 PM",
                    "15:00" => "3:00 PM",
                    "15:30" => "3:30 PM",
                    "16:00" => "4:00 PM",
                    "16:30" => "4:30 PM",
                    "17:00" => "5:00 PM",
                    "17:30" => "5:30 PM"
                ),

                "dinner" => Array
                (
                    "18:00" => "6:00 PM",
                    "18:30" => "6:30 PM",
                    "19:00" => "7:00 PM",
                    "19:30" => "7:30 PM",
                    "20:00" => "8:00 PM",
                    "20:30" => "8:30 PM",
                    "21:00" => "9:00 PM",
                    "21:30" => "9:30 PM",
                    "22:00" => "10:00 PM",
                    "22:30" => "10:30 PM",
                    "23:00" => "11:00 PM",
                    "23:30" => "11:30 PM",
                    "00:00" => "12:00 AM",
                    "00:30" => "12:30 AM",
                    "01:00" => "1:00 AM",
                    "01:30" => "1:30 AM",
                    "02:00" => "2:00 AM",
                    "02:30" => "2:30 AM",
                )

            )

        );

        echo json_encode($data);
    }

    public function experienceCheckout(){
        //echo "<pre>"; print_r(Input::all()); die;
        $dataPost['reservationDate'] = Input::get('booking_date');
        $dataPost['reservationDay'] =  date("D", strtotime($dataPost['reservationDate']));//
        $dataPost['reservationTime'] = Input::get('booking_time');
        $dataPost['partySize'] = Input::get('qty');
        $dataPost['vendorLocationID'] = Input::get('address');
        $dataPost['guestName'] = Input::get('fullname');
        $dataPost['guestEmail'] = Input::get('email');
        $dataPost['phone'] = Input::get('phone');
        $dataPost['total_amount'] = 0;
        //$dataPost['reservationType'] = (isset($dataPost['prepaid']) && $dataPost['prepaid'] == 1 ? 'event' : 'experience');
        $dataPost['specialRequest'] = Input::get('special');

        $dataPost['addon']          = Input::get('addonsArray');
        $dataPost['giftCardID']     = Input::get('gc_id');
        $award = Input::get('avard_point');
        $user_email = Input::get('mail');

        $count = $dataPost['addon'];
        if($count==""){  $dataPost['addon'] =array();}

        $addonsText = '';
        $addonsPostTaxTotal = '';
        foreach($dataPost['addon'] as $prod_id => $qty) {
            if($qty > 0){
                //echo "prod id = ".$prod_id." , qty = ".$qty;
                $addonsDetails = DB::select("SELECT attribute_value from product_attributes_text where product_id = $prod_id and product_attribute_id = 17");
                $addonsPricing = DB::select("SELECT post_tax_price from product_pricing where product_id = $prod_id");
                $addonsPostTaxTotal += $qty * $addonsPricing[0]->post_tax_price;
                //echo "<pre>"; print_r($addonsDetails);
                $addonsText .= $addonsDetails[0]->attribute_value." (".$qty.") , ";
                $dataPost['addon_'.$prod_id] = $qty;
            }

        }
        //echo "grand total addons = ".$addonsPostTaxTotal." , grand total = ".$dataPost['total_amount'];die;
        $finalAddontext = isset($addonsText) && $addonsText != "" ? "Addons: ".$addonsText : " ";
        $special_request = isset($dataPost['specialRequest']) && $dataPost['specialRequest'] != "" ? "Spl Req: ".$dataPost['specialRequest'] : "";
        $dataPost['addons_special_request'] = $finalAddontext." ".$special_request;

        //echo "<pre>"; print_r($dataPost); die;

        //echo $finalSpecialRequest;
        //die;
        $userID = Input::get('user_id');
        $userData = Profile::getUserProfileWeb($userID);
        //echo "<pre>"; print_r($userData); die;

        //$dataPost['access_token'] = Session::get('id');
        //echo "<pre>"; print_r($dataPost); die;
        $locationDetails = $this->experiences_model->getLocationDetails($dataPost['vendorLocationID']);
        //echo "<pre>"; print_r($locationDetails); //die;
        $outlet = $this->experiences_model->getOutlet($dataPost['vendorLocationID']);
        //echo "<pre>"; print_r($outlet);

        //die;
        $productDetails = $this->repository->getByExperienceId($outlet->product_id);
        $dataPost['product_id'] = (isset($outlet->product_id) && $outlet->product_id != 0 ? $outlet->product_id : 0);
        $dataPost['vendor_location_id'] = (isset($outlet->vendor_location_id) && $outlet->vendor_location_id != 0 ? $outlet->vendor_location_id : 0);
        $dataPost['reservationType'] = (isset($productDetails['attributes']['prepayment_allowed']) && $productDetails['attributes']['prepayment_allowed'] == 1 ? 'event' : 'experience');
        $dataPost['status']     = "new";
        $dataPost['addedBy']     = "admin";
        $vendor_name     = Input::get('vendor_name');
        $exp_title     = Input::get('exp_title');
        //echo "<pre>"; print_r($dataPost);

        $reservationResponse = $this->experiences_model->addReservationDetails($dataPost,$userID);

        //for the new db structure support
        /*$newDb['attributes']['date'] = date('d-M-Y', strtotime($dataPost['reservationDate']));
                $newDb['attributes']['time'] = date("g:i A", strtotime($dataPost['reservationTime']));*/
        $combined_date_and_time = $dataPost['reservationDate'] . ' ' . $dataPost['reservationTime'];
        $newDb['attributes']['reserv_datetime'] = Carbon::createFromFormat('Y-m-d H:i A',$combined_date_and_time)->toDateTimeString();
        $newDb['attributes']['no_of_people_booked'] = $dataPost['partySize'];
        $newDb['attributes']['cust_name'] = $dataPost['guestName'];
        $newDb['attributes']['email'] = $dataPost['guestEmail'];
        $newDb['attributes']['contact_no'] = $dataPost['phone'];
        $newDb['attributes']['reserv_type'] = "Experience";
        $newDb['attributes']['gift_card_id_reserv'] = $dataPost['giftCardID'];
        $newDb['attributes']['loyalty_points_awarded'] =  $productDetails['attributes']['reward_points_per_reservation'];
        $newDb['attributes']['special_request'] = $dataPost['addons_special_request'];
        $newDb['attributes']['experience'] = $outlet->vendor_name.' - '.$outlet->descriptive_title;
        $newDb['attributes']['api_added'] = "Admin Reservation";
        $newDb['attributes']['giu_membership_id'] = $userData['data']['membership_number'];
        $newDb['attributes']['outlet'] = $outlet->name;
        $newDb['attributes']['auto_reservation'] = "Not available";
        $newDb['attributes']['ar_confirmation_id'] = "0";
        $newDb['attributes']['alternate_id'] = 'E'.sprintf("%06d",$reservationResponse['data']['reservationID']);
        $newDb['attributes']['reservation_status_id'] = 1;
        $newDb['userdetails']['user_id'] = $userID;
        $newDb['userdetails']['status'] = 1;
        $newDb['userdetails']['addons'] = $dataPost['addon'];

        //print_r($newDb);die;
        $reservDetails = new ReservationDetails();
        $newDbStatus = $reservDetails->updateAttributes($reservationResponse['data']['reservationID'],$newDb);
        //print_r($newDbStatus);die;
        /*TODO: Add the status of success check and include added_by and transaction_id attributes */
        //die;
        $rewardsPoints = (isset($award) && $award != 0 ? $productDetails['attributes']['reward_points_per_reservation'] : 0);
        $bookingsMade = $userData['data']['bookings_made'] + 1;
        $type = "new";
        $reservationType = "experience";
        $lastOrderId = $reservationResponse['data']['reservationID'];
        //echo "rewardsPoints = ".$rewardsPoints." , bookingsMade = ".$bookingsMade." , type = ".$type." , reservationType = ".$reservationType; die;
        Profile::updateReservationInUsers($rewardsPoints,$type,$bookingsMade,$reservationType,$userID,$lastOrderId);
        DB::table('users')
            ->where('id', $userID)
            ->update(array('full_name' => $dataPost['guestName'],'phone_number'=>$dataPost['phone']));

        //echo "<pre>"; print_r($reservationResponse); die;
        $zoho_data = array(
            'Name' => $dataPost['guestName'],
            'Email_ids' => $dataPost['guestEmail'],
            'Contact' => $dataPost['phone'],
            'Experience_Title' => $outlet->vendor_name.' - '.$outlet->descriptive_title,
            'No_of_People' => $dataPost['partySize'],
            'Date_of_Visit' => date('d-M-Y', strtotime($dataPost['reservationDate'])),
            'Time' => date("g:i A", strtotime($dataPost['reservationTime'])),
            'Alternate_ID' =>  'E'.sprintf("%06d",$reservationResponse['data']['reservationID']),
            'Special_Request' => $dataPost['addons_special_request'],
            'Type' => "Experience",
            'API_added' => 'Admin Reservation',
            'GIU_Membership_ID' => $userData['data']['membership_number'],
            'Outlet' => $outlet->name,
            //'Points_Notes'=>'test',
            'AR_Confirmation_ID'=>'0',
            'Auto_Reservation'=>'Not available',
            //'telecampaign' => $campaign_id,
            //'total_no_of_reservations'=> '1',
            'Calling_option' => 'No',
            'gift_card_id_from_reservation' => $dataPost['giftCardID']
        );
        //echo "<pre>"; print_r($zoho_data);
        $zoho_res = AdminReservations::zoho_add_booking($zoho_data);
        $zoho_success = $zoho_res->result->form->add->status;
        //echo "<pre>"; print_r($zoho_success); die;
        if($zoho_success[0] != "Success"){
            //$this->email->from('concierge@wowtables.com', 'WowTables by GourmetItUp');
            //$list = array('concierge@wowtables.com', 'kunal@wowtables.com', 'deepa@wowtables.com');
            //$this->email->to($list);
            //$this->email->subject('Urgent: Zoho reservation posting error');
            $mailbody = 'E'.sprintf("%06d",$reservationResponse['data']['reservationID']).' reservation has not been posted to zoho. Please fix manually.<br><br>';
            $mailbody .= 'Reservation Details<br>';
            foreach($zoho_data as $key => $val){
                $name = str_replace('_',' ',$key);
                $mailbody .= $name.' '.$val.'<br>';
            }

            Mail::send('site.pages.zoho_posting_error',[
                'zoho_data'=> $mailbody,
            ], function($message) use ($zoho_data)
            {
                $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                $message->to('concierge@wowtables.com')->subject('Urgent: Zoho reservation posting error');
                $message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com');
            });
        }

        $city_id    = $locationDetails->city_id;

        $footerpromotions = DB::select('SELECT efp.link,mrn.file  FROM email_footer_promotions as efp LEFT JOIN media_resized_new as mrn ON mrn.media_id = efp.media_id WHERE efp.show_in_experience = 1 AND efp.city_id = '.$city_id);

        //echo "<pre>"; print_r($footerpromotions); die;

        $mergeReservationsArray = array('order_id'=> sprintf("%06d",$reservationResponse['data']['reservationID']),
            'reservation_date'=> date('d-F-Y',strtotime($dataPost['reservationDate'])),
            'reservation_time'=> date('g:i a',strtotime($dataPost['reservationTime'])),
            'venue' => $outlet->vendor_name,
            'username' => $dataPost['guestName']
        );

        if(isset($user_email) && $user_email != 0) {
            Mail::send('site.pages.experience_reservation',[
                'location_details'=> $locationDetails,
                'outlet'=> $outlet,
                'post_data'=>$dataPost,
                'productDetails'=>$productDetails,
                'reservationResponse'=>$reservationResponse,
                'footerPromotions'=>(!empty($footerpromotions) ? $footerpromotions : ""),
            ], function($message) use ($mergeReservationsArray){
                $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                $message->to(Input::get('email'))->subject('Your WowTables Reservation at '.$mergeReservationsArray['venue']);
                //$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
            });
        }


        $dataPost['admin']  = "yes";
        Mail::send('site.pages.experience_reservation',[
            'location_details'=> $locationDetails,
            'outlet'=> $outlet,
            'post_data'=>$dataPost,
            'productDetails'=>$productDetails,
            'reservationResponse'=>$reservationResponse,
            'footerPromotions'=>(!empty($footerpromotions) ? $footerpromotions : ""),
        ], function($message) use ($mergeReservationsArray){
            $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

            $message->to('concierge@wowtables.com')->subject('NR - #E'.$mergeReservationsArray['order_id'].' | '.$mergeReservationsArray['reservation_date'].' , '.$mergeReservationsArray['reservation_time'].' | '.$mergeReservationsArray['venue'].' | '.$mergeReservationsArray['username']);
            $message->cc(['kunal@wowtables.com', 'deepa@wowtables.com','abhishek.n@wowtables.com']);
        });

        $getUsersDetails = $this->experiences_model->fetchDetails($userID);

        //Start MailChimp
        $city_name      = Location::where(['Type' => 'City', 'id' => $getUsersDetails->location_id])->pluck('name');
        if(empty($city_name))
        {
            $city_name = 'mumbai';
        }
        $city = ucfirst($city_name);
        if(!empty($getUsersDetails)){

            $merge_vars = array(
                'MERGE1'=>$dataPost['guestName'],
                'MERGE10'=>date('m/d/Y'),
                'MERGE11'=>$userData['data']['bookings_made'] + 1,
                'MERGE13'=>$dataPost['phone'],
                'MERGE27'=>date("m/d/Y",strtotime($dataPost['reservationDate'])),
                'GROUPINGS' => array(array('id' => 9713, 'groups' => [$city]))
            );
            $this->mailchimp->lists->subscribe($this->listId, ["email"=>$dataPost['guestEmail']],$merge_vars,"html",false,true );
            //$this->mc_api->listSubscribe($list_id, $_POST['email'], $merge_vars,"html",true,true );
        }

        $result_data = array(
            'full_name'=>$dataPost['guestName'],
            'email'=>$dataPost['guestEmail'],
            'phone'=>$dataPost['phone'],
            'booking_date'=>$dataPost['reservationDate'],
            'booking_time'=>$dataPost['reservationTime'],
            'venue'=>$vendor_name,
            'exp_title'=>$exp_title,
            'order_id'=>'E'.sprintf("%06d",$reservationResponse['data']['reservationID']),
        );
        echo json_encode($result_data);
    }

    public function alacarteCheckout(){
        //echo "<pre>"; print_r(Input::all());
        $dataPost['reservationDate'] = Input::get('booking_date');
        $dataPost['reservationDay'] =  date("D", strtotime($dataPost['reservationDate']));//
        $dataPost['reservationTime'] = Input::get('booking_time');
        $dataPost['partySize'] = Input::get('qty');
        $dataPost['vendorLocationID'] = Input::get('address');
        $dataPost['guestName'] = Input::get('fullname');
        $dataPost['guestEmail'] = Input::get('email');
        $dataPost['phone'] = Input::get('phone');
        $dataPost['reservationType'] = 'alacarte';
        $dataPost['specialRequest'] = Input::get('special');
        $dataPost['addon']          = Input::get('add_ons');
        $award = Input::get('avard_point');
        $user_email = Input::get('mail');
        $vendor_name     = Input::get('vendor_name');
        $userID = Input::get('user_id');
        $userData = Profile::getUserProfileWeb($userID);

        $outlet = $this->alacarte_model->getOutlet($dataPost['vendorLocationID']);

        $locationDetails = $this->alacarte_model->getLocationDetails($dataPost['vendorLocationID']);

        $vendorDetails = $this->alacarterepository->getByRestaurantLocationId($dataPost['vendorLocationID']);

        $getUsersDetails = $this->experiences_model->fetchDetails($userID);


        $getReservationID = '';
        if($userID > 0) {
            //validating the information submitted by users
            $arrResponse = $this->alacarte_model->validateReservationData($dataPost);

            if($arrResponse['status'] == 'success') {
                $reservationResponse = $this->alacarte_model->addReservationDetails($dataPost,$userID);

                $rewardsPoints = (isset($award) && $award != 0 ? $vendorDetails['attributes']['reward_points_per_reservation'] : 0);;
                $bookingsMade = $userData['data']['a_la_carte_reservation'] + 1;
                $type = "new";
                $reservationType = "alacarte";
                $lastOrderId = $reservationResponse['data']['reservationID'];

                Profile::updateReservationInUsers($rewardsPoints,$type,$bookingsMade,$reservationType,$userID,$lastOrderId);
                DB::table('users')
                    ->where('id', $userID)
                    ->update(array('full_name' => $dataPost['guestName'],'phone_number'=>$dataPost['phone']));
                $getReservationID = $reservationResponse['data']['reservationID'];

                //for the new db structure support
                /*$newDb['attributes']['date'] = date('d-M-Y', strtotime($dataPost['reservationDate']));
                $newDb['attributes']['time'] = date("g:i A", strtotime($dataPost['reservationTime']));*/
                $combined_date_and_time = $dataPost['reservationDate'] . ' ' . $dataPost['reservationTime'];
                $newDb['attributes']['reserv_datetime'] = Carbon::createFromFormat('Y-m-d H:i A',$combined_date_and_time)->toDateTimeString();
                $newDb['attributes']['no_of_people_booked'] = $dataPost['partySize'];
                $newDb['attributes']['cust_name'] = $dataPost['guestName'];
                $newDb['attributes']['email'] = $dataPost['guestEmail'];
                $newDb['attributes']['contact_no'] = $dataPost['phone'];
                $newDb['attributes']['reserv_type'] = "Alacarte";
                $newDb['attributes']['loyalty_points_awarded'] =  $rewardsPoints;
                $newDb['attributes']['special_request'] = $dataPost['specialRequest'];
                $newDb['attributes']['experience'] = $outlet->vendor_name.' - Ala Carte';
                $newDb['attributes']['api_added'] = "Admin Reservation";
                $newDb['attributes']['giu_membership_id'] = $userData['data']['membership_number'];
                $newDb['attributes']['outlet'] = $outlet->name;
                $newDb['attributes']['auto_reservation'] = "Not available";
                $newDb['attributes']['ar_confirmation_id'] = "0";
                $newDb['attributes']['alternate_id'] = 'A'.sprintf("%06d",$reservationResponse['data']['reservationID']);
                $newDb['attributes']['reservation_status_id'] = 1;
                $newDb['userdetails']['user_id'] = $userID;
                $newDb['userdetails']['status'] = 1;

                //print_r($newDb);die;
                $reservDetails = new ReservationDetails();
                $newDbStatus = $reservDetails->updateAttributes($reservationResponse['data']['reservationID'],$newDb);
                //print_r($newDbStatus);die;
                /*TODO: Add the status of success check and include added_by and transaction_id attributes */
                //die;

                $zoho_data = array(
                    'Name' => $dataPost['guestName'],
                    'Email_ids' => $dataPost['guestEmail'],
                    'Contact' => $dataPost['phone'],
                    'Experience_Title' => $outlet->vendor_name.' - Ala Carte',
                    'No_of_People' => $dataPost['partySize'],
                    'Date_of_Visit' => date('d-M-Y', strtotime($dataPost['reservationDate'])),
                    'Time' => date("g:i A", strtotime($dataPost['reservationTime'])),
                    'Alternate_ID' =>  'A'.sprintf("%06d",$reservationResponse['data']['reservationID']),
                    'Special_Request' => $dataPost['specialRequest'],
                    'Type' => "Alacarte",
                    'API_added' => 'Admin Reservation',
                    'GIU_Membership_ID' => $userData['data']['membership_number'],
                    'Outlet' => $outlet->name,
                    //'Points_Notes'=>'test',
                    'AR_Confirmation_ID'=>'0',
                    'Auto_Reservation'=>'Not available',
                    //'telecampaign' => $campaign_id,
                    //'total_no_of_reservations'=> '1',
                    'Calling_option' => 'No'
                );
                //echo "<pre>"; print_r($zoho_data);
                $zoho_res = AdminReservations::zoho_add_booking($zoho_data);
                $zoho_success = $zoho_res->result->form->add->status;
                //echo "<pre>"; print_r($zoho_success); die;
                if($zoho_success[0] != "Success"){
                    //$this->email->from('concierge@wowtables.com', 'WowTables by GourmetItUp');
                    //$list = array('concierge@wowtables.com', 'kunal@wowtables.com', 'deepa@wowtables.com');
                    //$this->email->to($list);
                    //$this->email->subject('Urgent: Zoho reservation posting error');
                    $mailbody = 'A'.sprintf("%06d",$reservationResponse['data']['reservationID']).' reservation has not been posted to zoho. Please fix manually.<br><br>';
                    $mailbody .= 'Reservation Details<br>';
                    foreach($zoho_data as $key => $val){
                        $name = str_replace('_',' ',$key);
                        $mailbody .= $name.' '.$val.'<br>';
                    }

                    Mail::send('site.pages.zoho_posting_error',[
                        'zoho_data'=> $mailbody,
                    ], function($message) use ($zoho_data)
                    {
                        $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                        $message->to('concierge@wowtables.com')->subject('Urgent: Zoho reservation posting error');
                        $message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com');
                    });
                }

                $mergeReservationsArray = array('order_id'=> sprintf("%06d",$reservationResponse['data']['reservationID']),
                    'reservation_date'=> date('d-F-Y',strtotime($dataPost['reservationDate'])),
                    'reservation_time'=> date('g:i a',strtotime($dataPost['reservationTime'])),
                    'venue' => $outlet->vendor_name,
                    'username' => $dataPost['guestName']
                );

                //echo "<pre>"; print_r($mergeReservationsArray); die;
                $city_id    = $locationDetails->city_id;
                $city_name      = Location::where(['Type' => 'City', 'id' => $userData['data']['location_id']])->pluck('name');
                if(empty($city_name))
                {
                    $city_name = 'mumbai';
                }
                $city = ucfirst($city_name);
                //Start MailChimp
                if(!empty($getUsersDetails)){

                    $merge_vars = array(
                        'MERGE1'=>$dataPost['guestName'],
                        'MERGE10'=>date('m/d/Y'),
                        'MERGE11'=>$userData['data']['a_la_carte_reservation'] + 1,
                        'MERGE13'=>$dataPost['phone'],
                        'MERGE27'=>date("m/d/Y",strtotime($dataPost['reservationDate'])),
                        'GROUPINGS' => array(array('id' => 9713, 'groups' => [$city]))
                    );
                    $this->mailchimp->lists->subscribe($this->listId, ["email"=>$dataPost['guestEmail']],$merge_vars,"html",false,true );
                    //$this->mc_api->listSubscribe($list_id, $_POST['email'], $merge_vars,"html",true,true );
                }
                //End MailChimp

                $footerpromotions = DB::select('SELECT efp.link,mrn.file  FROM email_footer_promotions as efp LEFT JOIN media_resized_new as mrn ON mrn.media_id = efp.media_id WHERE efp.show_in_alacarte = 1 AND efp.city_id = '.$city_id);

                if(isset($user_email) && $user_email != 0) {
                    Mail::send('site.pages.restaurant_reservation',[
                        'location_details'=> $locationDetails,
                        'outlet'=> $outlet,
                        'post_data'=>$dataPost,
                        'productDetails'=>$vendorDetails,
                        'reservationResponse'=>$reservationResponse,
                        'footerPromotions'=>(!empty($footerpromotions) ? $footerpromotions : ""),
                    ], function($message) use ($mergeReservationsArray){
                        $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                        $message->to(Input::get('email'))->subject('Your WowTables Reservation at '.$mergeReservationsArray['venue']);
                        //$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
                    });
                }


                Mail::send('site.pages.restaurant_reservation',[
                    'location_details'=> $locationDetails,
                    'outlet'=> $outlet,
                    'post_data'=>$dataPost,
                    'productDetails'=>$vendorDetails,
                    'reservationResponse'=>$reservationResponse,
                    'footerPromotions'=>(!empty($footerpromotions) ? $footerpromotions : ""),
                ], function($message) use ($mergeReservationsArray){
                    $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                    $message->to('concierge@wowtables.com')->subject('NR - #A'.$mergeReservationsArray['order_id'].' | '.$mergeReservationsArray['reservation_date'].' , '.$mergeReservationsArray['reservation_time'].' | '.$mergeReservationsArray['venue'].' | '.$mergeReservationsArray['username']);
                    $message->cc(['kunal@wowtables.com', 'deepa@wowtables.com','abhishek.n@wowtables.com']);
                });

                $result_data = array(
                    'full_name'=>$dataPost['guestName'],
                    'email'=>$dataPost['guestEmail'],
                    'phone'=>$dataPost['phone'],
                    'booking_date'=>$dataPost['reservationDate'],
                    'booking_time'=>$dataPost['reservationTime'],
                    'exp_title'=>$vendor_name,
                    'order_id'=>'A'.sprintf("%06d",$reservationResponse['data']['reservationID']),
                );
                echo json_encode($result_data);


            }
        }

    }

}
