<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\PriceType;
use WowTables\Http\Requests\CreatePriceTypeRequest;
use WowTables\Http\Requests\EditPriceTypeRequest;

class AdminPriceTypesController extends Controller {


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
        $price_types = PriceType::all();

        return view('admin.promotions.price_type.index',['price_types'=>$price_types]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserAttributeRequest $request
     * @return Response
     */
    public function store(CreatePriceTypeRequest $request)
    {
        $price_type = new PriceType();

        $price_type->type_name = $this->request->get('type_name');

        $price_type->save();

        flash()->success('The Price Type has been created successfully');

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
        $price_type = PriceType::find($id);

        return view('admin.promotions.price_type.edit',['price_type'=>$price_type]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param EditUserAttributeRequest $request
     * @return Response
     */
    public function update($id,EditPriceTypeRequest $request)
    {
        $price_type = PriceType::find($id);

        $price_type->type_name = $this->request->get('type_name');

        $price_type->save();

        flash()->success('The price type has been edited successfully');

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
        PriceType::destroy($id);

        flash()->success('The Price Type has been deleted successfully');
    }

}
