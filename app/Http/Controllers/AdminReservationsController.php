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

/**
 * Class AdminExperiencesController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="/admin/adminreservations")
 */

class AdminReservationsController extends Controller{

    protected $listId = '986c01a26a';

    public function __construct(Mailchimp $mailchimp) {
        $this->mailchimp = $mailchimp;

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

            $merge_vars = array(
                'NAME'         =>     isset($createPasswordRequest['userName'] )? $createPasswordRequest['userName']: '',
                'SIGNUPTP'     =>     'Email',
                'BDATE'     =>    '',
                'GENDER'    =>  '',
                'MERGE11'  => 0,
                'MERGE17'=>'Admin added account',
                'PHONE'=>   isset($phone) ? $phone: '',
            );

            $this->mailchimp->lists->subscribe($this->listId, ["email"=>$createPasswordRequest['email']],$merge_vars,"html",false,true );

            $my_email = $createPasswordRequest['email'];

            $city_name      = Location::where(['Type' => 'City', 'id' => $cityid])->pluck('name');
            if(empty($city_name))
            {
                $city_name = 'mumbai';
            }

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
                                                                  $arrUpcomingReservations .= $data['vendor_name'] .': '.'A la carte Reservation';
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
                                                          $arrPastReservations .= $data['name'] .' : '.'A la carte Reservation';
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


}
