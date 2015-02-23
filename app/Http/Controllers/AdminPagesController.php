<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Page;
use WowTables\Http\Requests\CreatePageRequest;

/**
 * Class AdminController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="admin")
 */

class AdminPagesController extends Controller {


    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        $pages = Page::all();

        return view('admin.pages.index',['pages'=>$pages]);
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(CreatePageRequest $request)
    {

        Page::create([
            'title' => $this->request->get('title'),
            'slug' => $this->request->get('slug'),
            'main_content' => $this->request->get('main_content'),
            'seo_title' => $this->request->get('seo_title'),
            'seo_meta_description' => $this->request->get('seo_meta_description'),
            'seo_meta_keywords' => $this->request->get('seo_meta_keywords'),
        ]);

        flash()->success('The Page has been successfully created!');

        return redirect()->back();

    }

    public function preview($id)
    {
        $page = Page::find($id);

        return view('site.pages.static_page',['page'=>$page]);
    }


    public function edit($id)
    {
        $page = Page::find($id);

        return view('admin.pages.edit',['page'=>$page]);
    }

    public function update($id)
    {
        dd($this->request->all());
    }

    public function destroy($id)
    {
        Page::destroy($id);

        flash()->success('The Page has been successfully deleted!');
    }

}
