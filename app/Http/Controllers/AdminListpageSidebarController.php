<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\ListpageSidebar;
use WowTables\Http\Requests\CreateListpageSidebarRequest;
use WowTables\Http\Requests\EditListpageSidebarRequest;

class AdminListpageSidebarController extends Controller {


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
        $listpage_sidebars = ListpageSidebar::all();

        return view('admin.promotions.sidebar.index',['sidebars'=>$listpage_sidebars]);
    }

    public function create()
    {
        return view('admin.promotions.sidebar.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserAttributeRequest $request
     * @return Response
     */
    public function store(CreateListpageSidebarRequest $request)
    {
        $sidebars = new ListpageSidebar();

        $sidebars->link = $this->request->get('link');
        $sidebars->title = $this->request->get('title');
        $sidebars->description = $this->request->get('description');
        $sidebars->promotion_title = ($this->request->get('promotion_title') ? $this->request->get('promotion_title') : 0);;
        $sidebars->media_id = $this->request->get('media_id');
        $sidebars->city_id = $this->request->get('location_id');
        $sidebars->show_in_experience = ($this->request->get('show_in_experience') ? $this->request->get('show_in_experience') : 0);
        $sidebars->show_in_alacarte = ($this->request->get('show_in_alacarte') ? $this->request->get('show_in_alacarte') : 0);

        $sidebars->save();

        flash()->success('The Sidebar has been created successfully');

        return redirect('admin/promotions/listpage_sidebar');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $sidebar = ListpageSidebar::find($id);

        return view('admin.promotions.sidebar.edit',['sidebars'=>$sidebar]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param EditUserAttributeRequest $request
     * @return Response
     */
    public function update($id,EditListpageSidebarRequest $request)
    {
        $sidebars = ListpageSidebar::find($id);

        $sidebars->link = $this->request->get('link');
        $sidebars->title = $this->request->get('title');
        $sidebars->description = $this->request->get('description');
        $sidebars->promotion_title = ($this->request->get('promotion_title') ? $this->request->get('promotion_title') : 0);;
        $sidebars->media_id = $this->request->get('media_id');
        $sidebars->city_id = $this->request->get('location_id');
        $sidebars->show_in_experience = ($this->request->get('show_in_experience') ? $this->request->get('show_in_experience') : 0);
        $sidebars->show_in_alacarte = ($this->request->get('show_in_alacarte') ? $this->request->get('show_in_alacarte') : 0);

        $sidebars->save();

        flash()->success('The Sidebar has been edited successfully');

        return redirect('admin/promotions/listpage_sidebar');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        ListpageSidebar::destroy($id);

        flash()->success('The Sidebar has been deleted successfully');
    }

}
