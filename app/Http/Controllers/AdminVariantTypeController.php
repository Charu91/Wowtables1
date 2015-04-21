<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\VariantType;
use WowTables\Http\Requests\CreateVariantTypeRequest;

class AdminVariantTypeController extends Controller {


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
        $varianttypes = VariantType::all();

        return view('admin.promotions.variant_type.index',['variant_types'=>$varianttypes]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserAttributeRequest $request
     * @return Response
     */
    public function store(CreateVariantTypeRequest $request)
    {
        $varianttype = new VariantType();

        $varianttype->variation_name = $this->request->get('variation_name');
        $varianttype->variant_alias = $this->request->get('variant_alias');

        $varianttype->save();

        flash()->success('The Variant Type has been created successfully');

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
        $varianttype = VariantType::find($id);

        return view('admin.promotions.variant_type.edit',['variant_type'=>$varianttype]);
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
