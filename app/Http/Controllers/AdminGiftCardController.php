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
use WowTables\Http\Controllers\ConciergeApi\ReservationController;

/**
 * Class AdminExperiencesController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="/admin/adminreservations")
 */

class AdminGiftCardController extends Controller{

    protected $listId = '986c01a26a';

    public function __construct(Mailchimp $mailchimp,RestaurantLocationsRepository $alacarterepository,ExperiencesRepository $repository,ExperienceModel $experiences_model,AlacarteModel $alacarte_model, ReservationController $restaurantapp) {
        $this->middleware('admin.auth');
        $this->mailchimp = $mailchimp;
        $this->experiences_model = $experiences_model;
        $this->repository = $repository;
        $this->alacarterepository = $alacarterepository;
        $this->alacarte_model = $alacarte_model;
        $this->restaurantapp = $restaurantapp;
    }

    public function index(){

        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');

        return view('admin.giftcards.admin_giftcards',['cities'=>$cities]);
    }

    public function checkGiftCard(){
        //echo "asd"; die;
        $data = array('error'=>0);
        $dataPost = array();
        $dataPost['CardId'] = Input::get('CardId');
        /*$arrRules = array(
            'CustomerEmail' => 'required|email|max:255',
        ) ;
*/       /*
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
		*/
            $result = DB::select('SELECT * FROM gift_card_by_admin WHERE gift_card_id = "'.$dataPost['CardId'].'"');
            if(count($result)>=1){
                $data['exists'] = true;
                $data['gift_card'] = $result[0];
            } else{
                $data['exists'] = false;
            }
       // }
        echo json_encode($data);die;
    }



    public function addGiftCard(){
      //  $password = str_random(6);
        $GiftId = Input::get('GiftId');
        $Gift_card_id = Input::get('Gift_card_id');
        $Buyer_contact_email = Input::get('Buyer_contact_email');
        $Giftee_contact_email = Input::get('Giftee_contact_email');
        $Buyer_name = Input::get('Buyer_name');
        $Gift_details = Input::get('Gift_details');
        $Buyer_contact = Input::get('Buyer_contact');
        $Number_of_guest = Input::get('Number_of_guest');
        $Cash_value = Input::get('Cash_value');
        $Name_of_giftee = Input::get('Name_of_giftee');
        $Giftee_detail = Input::get('Giftee_detail');
        $Redeemed = Input::get('Redeemed');
        $Expire_date = Input::get('Expire_date');
        $Credit_remaining = Input::get('Credit_remaining');
        $Gift_note = Input::get('Gift_note');
		$date = date_create($Expire_date);
        $Expire_date= date_format($date, 'Y-m-d');
		if($Gift_card_id =='0'){
		$gift_id = DB::table('gift_card_by_admin')->insertGetId([
            'gift_card_id' => $GiftId,
            'buyer' => $Buyer_name,
            'buyer_email' => $Buyer_contact_email,
            'buyer_contact' => $Buyer_contact,
            'buyer_detail' => $Gift_details,
            'number_of_guest' => $Number_of_guest,
            'cash_value' => $Cash_value,
            'name_of_giftee' => $Name_of_giftee,
            'contact_of_giftee' => $Giftee_detail,
            'giftee_email' => $Giftee_contact_email,
            'gift_card_expire_date' => $Expire_date,
            'redeem' => $Redeemed,
            'credit_remaining' => $Credit_remaining,
            'notes' => $Gift_note,
        ]);
		
		
        

        if($gift_id) {
			$success_message = "The GIft Card ID ".$GiftId." has been created";
            $data = array(
                'gift_id'=>$GiftId,
                'success_message'=>$success_message,
            );
            echo json_encode($data);
        }
		}
		else {
		
	$update_gift_id=DB::table('gift_card_by_admin')->where('id', '=', $Gift_card_id)
	->update(array('gift_card_id' => $GiftId , 'buyer' => $Buyer_name , 'buyer_contact' => $Buyer_contact,
			'buyer_detail' => $Gift_details,'number_of_guest' => $Number_of_guest , 'cash_value' => $Cash_value,
			'name_of_giftee' => $Name_of_giftee,'contact_of_giftee' => $Giftee_detail , 'gift_card_expire_date' => $Expire_date,
			'redeem' => $Redeemed,'credit_remaining' => $Credit_remaining , 'notes' => $Gift_note
				));
				
        if($update_gift_id) {
			$success_message = "The GIft Card ID ".$GiftId." has been Updated";
            $data = array(
                'gift_id'=>$GiftId,
                'success_message'=>$success_message,
            );
            echo json_encode($data);
        }	

	
		}
		
        
    }

    
   

    public function detailGiftCard(){

        $GIft_CardId = Input::get('GIft_CardId');

        if(isset($GIft_CardId) && $GIft_CardId != "" && $GIft_CardId > 0){
           $gift_detail = DB::table('gift_card_by_admin')->where('id','=',$GIft_CardId)->first();
          echo json_encode($gift_detail);
        }

    }

  

    

   
    
        }
