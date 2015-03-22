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
        $collection = new Collection();

        $collection->name = $this->request->get('name');
        $collection->slug = $this->request->get('slug');
        $collection->description = $this->request->get('description');
        $collection->seo_title = $this->request->get('seo_title');
        $collection->seo_meta_description = $this->request->get('seo_meta_description');
        $collection->seo_meta_keywords = $this->request->get('seo_meta_keywords');
        $collection->media_id = $this->request->get('media_id');
        $collection->status = $this->request->get('status');
        $collection->show_in_experience = ($this->request->get('show_in_experience') ? $this->request->get('show_in_experience') : 0);
        $collection->show_in_alacarte = ($this->request->get('show_in_alacarte') ? $this->request->get('show_in_alacarte') : 0);

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

        $collection->name = $this->request->get('name');
        $collection->slug = $this->request->get('slug');
        $collection->description = $this->request->get('description');
        $collection->seo_title = $this->request->get('seo_title');
        $collection->seo_meta_description = $this->request->get('seo_meta_description');
        $collection->seo_meta_keywords = $this->request->get('seo_meta_keywords');
        $collection->media_id = $this->request->get('media_id');
        $collection->status = $this->request->get('status');
        $collection->show_in_experience = ($this->request->get('show_in_experience') ? $this->request->get('show_in_experience') : 0);
        $collection->show_in_alacarte = ($this->request->get('show_in_alacarte') ? $this->request->get('show_in_alacarte') : 0);

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
