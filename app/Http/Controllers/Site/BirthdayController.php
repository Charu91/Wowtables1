<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Frontend\BirthdayBash;

use Illuminate\Http\Request;

class BirthdayController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @returns Response
	 */
	public function index()
	{
        return view('frontend.pages.birthday');
    }

    public function enter_details(Request $request){

        //var_dump($request->get('name'));die();

        /*$v = Validator::make($request->all(),[
            'email' => 'required|email',
            'name' => 'required',
            'phone_no' => 'required|digits:10',
            'lunch_option' => 'required'
        ]);

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }*/

        $birthDayDetails = new BirthdayBash();
        $birthDayDetails->email = $request->get('email');
        $birthDayDetails->name = $request->get('name');
        $birthDayDetails->phone_no = $request->get('phone_no');
        $birthDayDetails->lunch_option = $request->get('lunch_option');
        $birthDayDetails->promotion_type = $request->get('promotion_type');
        $birthDayDetails->city = $request->get('city_sel');
        $birthDayDetails->save();

        return json_encode(true);
    }

}
