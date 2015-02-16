<?php namespace WowTables\Http\Controllers;

/**
 * Class AdminEventsController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="/admin/events")
 */

class AdminEventsController extends Controller {


    public function __construct()
    {
        $this->middleware('admin.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @Get("/", as="AdminEvents")
     * @return Response
     */
    public function index()
    {
        return view('admin.events.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @Get("/create", as="AdminEventsCreate")
     * @return Response
     */
    public function create()
    {
        return view('admin.events.add_update');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @Post("/", as="AdminEventsStore")
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @Get("/{id}", as="AdminEventsShow")
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin.events.single');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @Get("/edit/{id}", as="AdminEventsEdit")
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin.events.add_update');
    }

    /**
     * Update the specified resource in storage.
     *
     * @Put("/{id}", as="AdminEventsUpdate")
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @Delete("/{id}", as="AdminEventsDelete")
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
