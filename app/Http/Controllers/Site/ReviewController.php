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
use Mail;		
use URL;				
use Response;
use Mailchimp;
use WowTables\Http\Models\Profile;
use WowTables\Core\Repositories\Experiences\ExperiencesRepository;
use WowTables\Core\Repositories\Restaurants\RestaurantLocationsRepository;
use WowTables\Http\Models\VendorLocationsReviews;
use WowTables\Http\Models\ProductReviews;

class ReviewController extends Controller {

	protected $listId = '986c01a26a';

	function __construct(Request $request, AlacarteModel $alacarte_model, ExperienceModel $experiences_model,Mailchimp $mailchimp,RestaurantLocationsRepository $restaurantLocationsRepository,ExperiencesRepository $experiencesRepository,ExperienceModel $experiences_model)
	{
		$this->request = $request;
		$this->alacarte_model = $alacarte_model;
		$this->experiences_model = $experiences_model;
		$this->mailchimp = $mailchimp;
		$this->restaurantLocationsRepository = $restaurantLocationsRepository;
		$this->experiencesRepository = $experiencesRepository;
	}


	 /**
     * addReview view details of the user
     *
     * @static	true
     * @access	public
     * @param	array $data
     * @since	1.0.0
     */

    public function addReview($myreservid)
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
		
		//$data=Profile::getUserProfileWeb($id);

        $var = $myreservid;
        $substr =substr($var, 1);

        $reservid = ltrim($substr, '0');


        $queryResult = DB::select("select `rd`.`id`, `rd`.`user_id`, `rd`.`reservation_status`, `rd`.`reservation_date`, `rd`.`reservation_time`, `rd`.`no_of_persons`,
                                   `products`.`name` as `product_name`, `vendors`.`id` as `vendor_id`, `vendors`.`name` as `vendor_name`,
                                    `rd`.`reservation_type`, `products`.`id` as `product_id`, `rd`.`vendor_location_id`,
                                     `rd`.`product_vendor_location_id`,
                                   `rd`.`special_request`, `rd`.`giftcard_id`, `rd`.`guest_name`, 
                                   `rd`.`guest_name`, `rd`.`guest_email`, `rd`.`guest_phone`, 
                                   `rd`.`points_awarded`, MAX(IF(pa.alias='short_description', pat.attribute_value,'')) AS product_short_description,
                                    MAX(IF(va.alias='short_description', vlat.attribute_value, ''))AS vendor_short_description, `ploc`.`name` as `product_locality`,
                                     `pvla`.`address` as `product_address`, `vloc`.`name` as `vendor_locality`,
                                     `vvla`.`address` as `vendor_address`, `products`.`slug` as `product_slug`, `ploc`.`name` as `city`,
                                       DAYNAME(rd.reservation_date) as dayname,pvl.id as product_vendor_location_id,`vloc1`.name as city_name,`vloc1`.id as city_id 
                                    from `reservation_details` as `rd` 
                                    left join `vendor_locations` as `vl` on `vl`.`id` = `rd`.`vendor_location_id`
                                    left join `product_vendor_locations` as `pvl` on `pvl`.`product_id` = `rd`.`product_id` and pvl.vendor_location_id = `rd`.`vendor_location_id` 
                                    left join `products` on `products`.`id` = `pvl`.`product_id` 
                                    left join `vendors` on `vendors`.`id` = `vl`.`vendor_id` 
                                    left join `product_attributes_text` as `pat` on `pat`.`product_id` = `products`.`id` 
                                    left join `product_attributes` as `pa` on `pa`.`id` = `pat`.`product_attribute_id` 
                                    left join `vendor_location_attributes_text` as `vlat` on `vlat`.`vendor_location_id` = `vl`.`id` 
                                    left join `vendor_attributes` as `va` on `va`.`id` = `vlat`.`vendor_attribute_id` 
                                    left join `vendor_locations` as `vl2` on `vl2`.`id` = `pvl`.`vendor_location_id` 
                                    left join `locations` as `ploc` on `ploc`.`id` = `vl2`.`location_id` 
                                    left join `vendor_location_address` as `pvla` on `pvla`.`vendor_location_id` = `pvl`.`vendor_location_id` 
                                    left join `vendor_location_address` as `vvla` on `vvla`.`vendor_location_id` = `rd`.`vendor_location_id` 
                                    left join `locations` as `vloc` on `vloc`.`id` = `vl`.`location_id`
                                    left join `locations` as `vloc1` on `vloc1`.`id` = vvla.city_id
                                     where `rd`.`id` = $reservid and `reservation_status` in ('new', 'edited') 
                                    group by `rd`.`id` order by `rd`.`reservation_date` asc, `rd`.`reservation_time` asc");
        //print_r($queryResult);
        if(empty($queryResult))
        {
            die('Data does not exists');
            exit;
        }
        else
        {
            $data = array('reservid'                  => $queryResult[0]->id,
                          'user_id'                   => $queryResult[0]->user_id,
                          'reservation_date'          => $queryResult[0]->reservation_date,
                          'product_name'              => $queryResult[0]->product_name,
                          //'vendor_id'               => $queryResult[0]->vendor_id,
                          'vendor_name'               => $queryResult[0]->vendor_name,
                          'reservation_type'          => $queryResult[0]->reservation_type,
                          'product_id'                => $queryResult[0]->product_id,
                          'vendor_location_id'        => $queryResult[0]->vendor_location_id,
                          'guest_name'                => $queryResult[0]->guest_name,
                          'guest_email'               => $queryResult[0]->guest_email,
                          'product_short_description' => $queryResult[0]->product_short_description,
                          'product_locality'          => $queryResult[0]->product_locality,
                          'vendor_locality'           => $queryResult[0]->vendor_locality,
                          'city_name'                 => $queryResult[0]->city_name,
                          'city_id'                   => $queryResult[0]->city_id,
                           );
            $user_id = $queryResult[0]->user_id;
            $membership_query = DB::select("select attribute_value from user_attributes_varchar where user_id=$user_id limit 1");
                //echo "sad = ".$last_id;
                //$getMembership_number = $membership_query[0]->attribute_value;

                if(empty($membership_query))
                {
                    $membership_number = 'NULL';
                }
                else
                {
                    $membership_number = $membership_query[0]->attribute_value;
                }

                $data['membership_number'] = $membership_number;
        
        }
        if($data['reservation_type'] == "experience")
        {
        $check_review = DB::select("select product_id, user_id, reserv_id
                                    from product_reviews where product_id ='".$queryResult[0]->product_id."' 
                                    and user_id = '".$queryResult[0]->user_id."' 
                                    and reserv_id = '".$queryResult[0]->id."' ");
        $check_review = count($check_review);
        }
        else if($data['reservation_type'] == "alacarte")
        {
           $check_review = DB::select("select vendor_location_id, user_id, reserv_id
                                    from vendor_location_reviews where vendor_location_id ='".$queryResult[0]->vendor_location_id."' 
                                    and user_id = '".$queryResult[0]->user_id."' 
                                    and reserv_id = '".$queryResult[0]->id."' ");
           $check_review = count($check_review);
        }
        if($check_review >=1)
        {
            $review_status = 'Yes';
        }
        else
        {
            $review_status = 'No';
        }
        $data['review_status'] = $review_status;
        
        return view('frontend.pages.addreview',$arrResponse)->with('data',$data);
		
	}

     /**
     * addReview view details of the user
     *
     * @static  true
     * @access  public
     * @param   post
     * @since   1.0.0
     */
     public function saveReview()
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
        
        $user_id            = Input::get('user_id');
        $rating             = Input::get('rating');
        $user_email         = Input::get('user_email');
        $member_name        = Input::get('member_name');
        $reservid           = Input::get('reservid');
        $reservation_type   = Input::get('reservation_type');
        $membership_num     = Input::get('membership_num');
        $seating_date       = Input::get('seating_date');
        $review_para        = Input::get('review_para');
        $name_server        = Input::get('name_server');
        $service            = Input::get('service');
        $suggestion         = Input::get('suggestion');
        $exp_name           = Input::get('exp_name');
        $city               = Input::get('city');

        if($reservation_type == "experience")
        {
            $product_id = Input::get('product_id');
            $productReviews = new ProductReviews();
            $productReviews->product_id = $product_id;
            $productReviews->user_id = $user_id;
            $productReviews->reserv_id = $reservid;
            $productReviews->review = $review_para;
            $productReviews->server_name = $name_server;
            $productReviews->service = $service;
            $productReviews->suggestions = $suggestion;
            $productReviews->rating = $rating;
            $productReviews->save();


            /*DB::insert("insert into product_reviews(product_id,user_id,reserv_id,review,server_name,service,suggestions,rating)
                        values ('$product_id', '$user_id', '$reservid', '$review_para', '$name_server', '$service', '$suggestion', '$rating')");*/
            //echo 'experience';
        }
        else if($reservation_type == "alacarte")
        {
            $vendor_location_id = Input::get('vendor_location_id');
            $vendorLocationReview = new VendorLocationsReviews();
            $vendorLocationReview->vendor_location_id = $vendor_location_id;
            $vendorLocationReview->user_id = $user_id;
            $vendorLocationReview->reserv_id = $reservid;
            $vendorLocationReview->review = $review_para;
            $vendorLocationReview->server_name = $name_server;
            $vendorLocationReview->service = $service;
            $vendorLocationReview->suggestions = $suggestion;
            $vendorLocationReview->rating = $rating;
            $vendorLocationReview->save();


             /*DB::insert("insert into vendor_location_reviews(vendor_location_id,user_id,reserv_id,review,server_name,service,suggestions,rating)
                        values ('$vendor_location_id', '$user_id', '$reservid', '$review_para', '$name_server', '$service', '$suggestion', '$rating')");*/
             //echo 'alacarte';
        }
        $add_points = 500;
        DB::update('UPDATE users SET points_earned = points_earned + '.$add_points.' WHERE id = '.$user_id);

         Mail::send('site.pages.review_mail_user',
                        ['member_name'    => $member_name,
                         'email_id'       => $user_email,
                         'exp_name'       => $exp_name,
                         'rating'         => $rating,
                         'review_para'    => $review_para,
                         'name_server'    => $name_server,
                         'service'        => $service,
                         'suggestion'     => $suggestion,
                         'membership_num' => $membership_num,], function($message) use ($exp_name, $member_name) {
                        $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                        $message->to('concierge@wowtables.com')->subject(' New Feedback on '.$exp_name.' by '.$member_name.'');
                        $message->cc(['kunal@wowtables.com', 'deepa@wowtables.com']);
                });

         $page_text = "<center><h2>Thank you for your review.</h2></center>";

            $page_text .= "<p>Dear ".ucfirst($member_name).",</p>";
            //if review is 1 or 2
            if($rating == 1 || $rating == 2){

                

                $page_text .= "<p>Thank you for your review. Your Gourmet Points have been added to your account.</p>";

                $page_text .= "<p>We are sorry you did not have a satisfactory dining experience. WowTables is dedicated to doing everything possible to make sure you enjoy our services and restaurants.</p>";

                $page_text .= "<p>Our concierge desk will be contacting you by phone or email to understand the issue better so that we can attempt to resolve it, convey the feedback to the restaurant's management and improve the experience for the next time. You can also reach out to our concierge desk at anytime by emailing <a href='mailto:concierge@wowtables.com'>concierge@wowtables.com</a> or by calling 09619551387.</p>";

                $page_text .= "<p>Our member feedback is reviewed by me personally every week. However if you wish to reach out to me directly, my personal email address is <a href='mailto:kunal@wowtables.com'>kunal@wowtables.com</a>.</p>";

                $page_text .= "<p>Thanks again and we assure you a better experience next time!</p>";

                $page_text .= "<p>Regards,<br/>Kunal Jain<br/>Co-founder - WowTables</p>";


            } else if($rating == 3) {
                //if review is 3

                $page_text .= "<p>Thank you for your review. Your Gourmet Points have been added to your account.</p>";

                if($reservation_type == "experience"){
                
                    $page_text .= "<p>All member feedback is reviewed by the WowTables founding team personally and used to continuously improve our experiences and services. </p>";

                }

                $page_text .= "<p>Thanks again and we assure you a better experience next time!</p>";

                $page_text .= "<p>Regards,<br/>The WowTables Team</p>";

            } else if($rating == 4 || $rating == 5) {
                //if review is 4 or 5

                $page_text .= "<p>Thank you for your review. Your Gourmet Points have been added to your account.</p>";

                $page_text .= "<p>We're glad that you had a good experience with WowTables and look forward to seeing you again!</p>";

                if($reservation_type == "experience"){
                
                    $page_text .= "<p>P.S. Help us grow by posting this review on social media & external review sites. Don't forget to mention that others can access this experience through the Wowtables website! </p>";

                    $page_text .= "<p>Send us a screenshot at <a href='mailto:concierge@wowtables.com'>concierge@wowtables.com</a> and we'll send you a Rs. 500 WowTables gift card for your next experience! </p>";

                } else if($reservation_type == "alacarte"){

                    $page_text .= "<p>P.S. Help us grow by posting this review on social media & external reveiw sites. Do mention our reservations services and send us a screenshot to get a Rs. 500 gift card towards your next experience reservation. Thanks!</p>";

                }

                $page_text .= "<p>Thanks again and we assure you a better experience next time!</p>";

                $page_text .= "<p>Regards,<br/>The WowTables Team</p>";

            }

            $page_text .= "<p class='text-center lead'>
                                <a class='btn btn-warning' href='".URL::to('/').'/'.$city."'>Return to experiences</a>
                           </p>";

         return view('frontend.pages.thankyou_addreview', $arrResponse)->with('page_text', $page_text);
    }

}
