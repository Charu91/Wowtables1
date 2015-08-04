<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Collection;
use WowTables\Http\Requests\CreateCollectionRequest;
use WowTables\Http\Requests\EditCollectionRequest;

class AdminCollectionsController extends Controller {


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
        $collections = Collection::all();

        //echo "<pre>"; print_r($collections);

        return view('admin.promotions.collections.index',['collections'=>$collections]);
    }

    public function create()
    {
        return view('admin.promotions.collections.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserAttributeRequest $request
     * @return Response
     */
    public function store(CreateCollectionRequest $request)
    {
        $input = $this->request->all();

        $collection = new Collection();

        $collection->name = $input['name'];
        $collection->slug = $input['slug'];
        $collection->description = $input['description'];
        $collection->seo_title = $input['seo_title'];
        $collection->seo_meta_description = $input['seo_meta_description'];
        $collection->seo_meta_keywords = $input['seo_meta_keywords'];
        $collection->media_id = $input['media']['mobile'];
        $collection->web_media_id = $input['media']['web_collection'];
        $collection->status = $input['status'];
        $collection->show_in_experience = (isset($input['show_in_experience']) && $input['show_in_experience'] != "" ? $input['show_in_experience'] : 0);
        $collection->show_in_alacarte = (isset($input['show_in_alacarte']) && $input['show_in_alacarte'] != "" ? $input['show_in_alacarte'] : 0);
        $collection->hide_in_mobile = (isset($input['hide_in_mobile']) && $input['hide_in_mobile'] != "" ? $input['hide_in_mobile'] : 0);

        $collection->save();

        flash()->success('The Collection has been created successfully');

        return redirect('admin/promotions/collections');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $collection = Collection::find($id);

        //echo "<pre>"; print_r($collection); die;

        return view('admin.promotions.collections.edit',['collection'=>$collection]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param EditUserAttributeRequest $request
     * @return Response
     */
    public function update($id,EditCollectionRequest $request)
    {
        $collection = Collection::find($id);
        //dd($request);
        $input = $this->request->all();
        //echo "<pre>"; print_r($input); die;
        $collection->name = $input['name'];
        $collection->slug = $input['slug'];
        $collection->description = $input['description'];
        $collection->seo_title = $input['seo_title'];
        $collection->seo_meta_description = $input['seo_meta_description'];
        $collection->seo_meta_keywords = $input['seo_meta_keywords'];
        $collection->media_id = $input['media']['mobile'];
        $collection->web_media_id = $input['media']['web_collection'];
        $collection->status = $input['status'];
        $collection->show_in_experience = (isset($input['show_in_experience']) && $input['show_in_experience'] != "" ? $input['show_in_experience'] : 0);
        $collection->show_in_alacarte = (isset($input['show_in_alacarte']) && $input['show_in_alacarte'] != "" ? $input['show_in_alacarte'] : 0);
        $collection->hide_in_mobile = (isset($input['hide_in_mobile']) && $input['hide_in_mobile'] != "" ? $input['hide_in_mobile'] : 0);

        $collection->save();

        flash()->success('The curator has been edited successfully');

        return redirect('admin/promotions/collections');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Collection::destroy($id);

        flash()->success('The Collection has been deleted successfully');
    }

}
