<?php namespace WowTables\Http\Controllers;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\CareerModel;
use WowTables\Http\Models\Eloquent\Location;
use Auth;
use Input;
use Mail;
use Validator;

use Illuminate\Http\Request;

class CareerController extends Controller {

	function __construct(Request $request)
	{
		$this->request = $request;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$careers = CareerModel::all();
		return view('admin.careers.index',['careers'=>$careers]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('admin.careers.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$career = new CareerModel();
		$career->job_title = $this->request->get('job_title');
		$career->location = $this->request->get('job_location');
		$career->job_desc = $this->request->get('job_description');
		$career->job_qualification = $this->request->get('job_qualification');
		if(!empty($this->request->get('status'))){
			$career->status = 1;
		} else {
			$career->status = 0;
		}
		//var_dump($career);die();
		$career->save();
		flash()->success('Career listing has been created successfully');
		return redirect('admin/careers');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$career = CareerModel::find($id);
		return view('admin.careers.edit',['career'=>$career]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$career = CareerModel::find($id);
		$career->job_title = $this->request->get('job_title');
		$career->location = $this->request->get('job_location');
		$career->job_desc = $this->request->get('job_description');
		$career->job_qualification = $this->request->get('job_qualification');
		if(!empty($this->request->get('status'))){
			$career->status = 1;
		} else {
			$career->status = 0;
		}
		//var_dump($career);die();
		$career->save();
		flash()->success('Career listing has been edited successfully');
		return redirect('admin/careers');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		CareerModel::destroy($id);
		flash()->success('Career listing has been deleted successfully');
	}

	public function frontend(){
		$careers = CareerModel::where('status',1)->get();
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

		return view('frontend.pages.careers',$arrResponse)->with('careers',$careers);
	}

	public function apply($id=""){


		$career = CareerModel::find($id);

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

		return view('frontend.pages.career_apply',$arrResponse)->with('career',$career);
	}

	public function send_details(Request $request){

		//print_r($request);die();
		//echo "'fdsfasdf'";die;
		$rules = array(
            'name' => 'required|min:7',
            'email'     => 'required|required',
            'phone_no' => 'required|min:10|max:10'
        );



  	$validation = Validator::make($request->all(), $rules);

		$validation->after(function($validation) use ($request)
		{
		    if ($request->hasFile('resume'))
		    {
		        $validation->errors()->add('resume', 'Please upload your resume');
		    }
		});
		if ($validation->fails()){
				return redirect()->back()->withErrors($validation->errors());
		}

		Mail::send('site.pages.careers_email',[
				'name' => $request->get('name'),
				'email' => $request->get('email'),
				'phone_no' => $request->get('phone_no'),
				'cover_letter' => $request->get('cover_letter'),
				'salary' => $request->get('salary'),
		], function($message) use ($request){
				$message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');
				$message->to(['deepa@gourmetitup.com','hr@gourmetitup.com','x+15629009601835@mail.asana.com'])->subject('Application: '.$request->get('job_role').' by '.$request->get('name'));
				//$message->to(['manan@wowtables.com'])->subject('Application: '.$request->get('job_role').' by '.$request->get('name'));
				$message->attach($request->file('resume'),array(
        'as' => $request->get('name').'-resume.' .$request->file('resume')->getClientOriginalExtension(),
        'mime' => $request->file('resume')->getMimeType()));
		});

		flash()->success('Your application has successfully submitted');
		return redirect('pages/careers');
	}

}
