<?php namespace GIU\Http\Controllers\Site;

use GIU\Http\Controllers\Controller;

use Illuminate\Http\Request;

class RegistrationsController extends Controller {


	function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function registerView()
	{
		return view('site.users.register');
	}

	public function register()
	{
		dd($this->request->all());
	}
}
