<?php namespace WowTables\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Cookie;

class SiteComposer {

    protected $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function compose(View $view)
    {
        $view->with('inform_rebranding', Cookie::get('inform_rebranding') ? true : false );
    }
}