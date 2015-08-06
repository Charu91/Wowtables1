<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Curator;
use WowTables\Http\Requests\CreateCuratorRequest;
use WowTables\Http\Requests\EditCuratorRequest;

class AdminCuratorsController extends Controller {


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
        $curators = Curator::all();

        return view('admin.users.curators.index',['curators'=>$curators]);
    }

    public function create()
    {
        return view('admin.users.curators.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserAttributeRequest $request
     * @return Response
     */
    public function store(CreateCuratorRequest $request)
    {

        $curator = new Curator();

        $curator->name = $this->request->get('name');
        //$curator->media_id = $this->request->get('media_id');
        $curator->bio = $this->request->get('bio');
        $curator->link = $this->request->get('link');
        $curator->city_id = $this->request->get('location_id');

        $curator->save();

        flash()->success('The Curator has been created successfully');

        return redirect('admin/user/curators');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $curator = Curator::find($id);

        return view('admin.users.curators.edit',['curator'=>$curator]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param EditUserAttributeRequest $request
     * @return Response
     */
    public function update($id,EditCuratorRequest $request)
    {
        $curator = Curator::find($id);

        $curator->name = $this->request->get('name');
        //$curator->media_id = $this->request->get('media_id');
        $curator->bio = $this->request->get('bio');
        $curator->link = $this->request->get('link');
        $curator->city_id = $this->request->get('location_id');

        $curator->save();

        flash()->success('The curator has been edited successfully');

        return redirect('admin/user/curators');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Curator::destroy($id);

        flash()->success('The Curator has been deleted successfully');
    }

}
