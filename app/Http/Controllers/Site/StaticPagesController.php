<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Controllers\Controller;

use Illuminate\Http\Request;

class StaticPagesController extends Controller {

	public function home()
	{
		return view('site.pages.home');
	}

	public function loggedInHome()
	{
		return view('site.users.home');
	}
}
