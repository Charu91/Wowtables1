<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Core\Repositories\Restaurants\RestaurantLocationsRepository;
use WowTables\Http\Controllers\Controller;
use App;
use Cookie;
use Illuminate\Http\Request;
use Session;
use Config;
use Input;
use Validator;
use Hash;
use DB;
use Auth;
use Redirect;
use Mail;
use Mailchimp;

class PaymentController extends Controller{
    public function test(){
        return view('site.pages.payment');
    }

    public function process_response(){
        echo "sad"; die;
        echo "<pre>"; print_r($_REQUEST);
    }
}
?>