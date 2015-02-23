<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Controllers\Controller;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Page;

class StaticPagesController extends Controller {

	public function home()
	{
		return view('site.pages.home');
	}

	public function loggedInHome()
	{
		return view('site.users.home');
	}

	public function show($slug)
	{
		$page = Page::where('slug',$slug)->first();

		return view('site.pages.static_page',['page'=>$page]);
	}
}
