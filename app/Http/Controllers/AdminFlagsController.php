<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Flag;
use WowTables\Http\Requests\CreateFlagRequest;
use WowTables\Http\Requests\EditFlagRequest;

class AdminFlagsController extends Controller {


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
        $flags = Flag::all();

        return view('admin.promotions.flags.index',['flags'=>$flags]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserAttributeRequest $request
     * @return Response
     */
    public function store(CreateFlagRequest $request)
    {
        $flag = new Flag();

        $flag->name = $this->request->get('name');
        $flag->color = $this->request->get('color');

        $flag->save();

        flash()->success('The flag has been created successfully');

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $flag = Flag::find($id);

        return view('admin.promotions.flags.edit',['flag'=>$flag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param EditUserAttributeRequest $request
     * @return Response
     */
    public function update($id,EditFlagRequest $request)
    {
        $flag = Flag::find($id);

        $flag->name = $this->request->get('name');
        $flag->color = $this->request->get('color');

        $flag->save();

        flash()->success('The flag has been edited successfully');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Flag::destroy($id);

        flash()->success('The Flag has been deleted successfully');
    }

}
