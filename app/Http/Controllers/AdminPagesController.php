<?php namespace WowTables\Http\Controllers;

use WowTables\Http\Models\Eloquent\Page;
use WowTables\Http\Requests\CreatePageRequest;
use WowTables\Http\Requests\UpdatePageRequest;

/**
 * Class AdminController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="admin")
 */

class AdminPagesController extends Controller {


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
            'page_title' => $request->get('page_title'),
            'slug' => $request->get('slug'),
            'page_contents' => $request->get('page_contents'),
            'seo_title' => $request->get('seo_title'),
            'meta_desc' => $request->get('meta_desc'),
            'meta_keywords' => $request->get('meta_keywords'),
        ]);

        flash()->success('The Page has been successfully created!');

        return redirect()->route('AdminPages');

    }

    public function preview($id)
    {
        $page = Page::find($id);

        return view('site.pages.static_page',['page'=>$page]);
    }


    public function edit($id)
    {
        $page = Page::find($id);
        //echo "<pre>"; print_r($page); die;

        return view('admin.pages.edit',['page'=>$page]);
    }

    public function update($id,UpdatePageRequest $request)
    {
        $page = Page::find($id);

        $page->page_title = $request->get('page_title');
        $page->slug = $request->get('slug');
        $page->page_contents = $request->get('page_contents');
        $page->seo_title = $request->get('seo_title');
        $page->meta_desc = $request->get('meta_desc');
        $page->meta_keywords = $request->get('meta_keywords');
        $page->save();

        flash()->success('The Page has been updated Successfully!');

        return redirect()->route('AdminPages');
    }

    public function destroy($id)
    {
        Page::destroy($id);

        flash()->success('The Page has been successfully deleted!');
    }

}
