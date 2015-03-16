<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Requests\Admin\CreateExperienceLocationRequest;

/**
 * Class AdminExperiencesController
 */

class AdminExperienceLocationsController extends Controller {

    /**
     * The constructor Method
     *
     * @param Request $request
     */
    function __construct(Request $request)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $locations = [];

        return view('admin.experiences.locations.index',['locations'=>$locations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('admin.experiences.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(CreateExperienceLocationRequest $createExperienceLocationRequest)
    {
        dd($this->request->all());
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show($id)
    {
        return view('admin.experiences.locations.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit($id)
    {
        return view('admin.experiences.locations.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update($id)
    {
        dd($this->request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy()
    {
        //
    }

}
