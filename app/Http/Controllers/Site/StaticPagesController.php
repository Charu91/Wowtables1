<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Controllers\Controller;
use App;
use Cookie;
use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Page;

class StaticPagesController extends Controller {

	public function home()
	{
		if(Cookie::get('inform_rebranding') == null)
		{
			return response()->view('site.pages.home')->withCookie(cookie('inform_rebranding', true, 1440));
		}
		else{
			return response()->view('site.pages.home');
    	}
	}

	public function loggedInHome()
	{
		return view('site.users.home');
	}

	public function show($slug)
	{
		$page = Page::where('slug',$slug)->where('status','Active')->first();

		if( $page != null )
		{
			return view('site.pages.static_page',[
				'page'=>$page,
				'seo_title' => $page->seo_title,
				'seo_meta_description' => $page->seo_meta_description,
				'seo_meta_keywords' => $page->seo_meta_keywords
			]);
		}
		else {
			App::abort('404');
		}
	}
}
