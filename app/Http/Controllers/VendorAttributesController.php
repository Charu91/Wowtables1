<?php namespace WowTables\Http\Controllers;

use WowTables\Http\Models\Eloquent\Vendors\VendorAttributes;
use WowTables\Http\Requests\CreateVendorAttributeRequest;
use WowTables\Http\Requests\EditVendorAttributeRequest;

class VendorAttributesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $attributes = VendorAttributes::all();

        return view('admin.vendors.attributes.index',['attributes'=>$attributes]);
    }


    /**
     * Store a newly created resource in storage.
     * @param CreateVendorAttributeRequest $request
     * @return Response
     */
    public function store(CreateVendorAttributeRequest $request)
    {
        $attribute = new VendorAttributes();

        $attribute->name = $request->get('name');
        $attribute->alias = $request->get('alias');
        $attribute->type = $request->get('type');

        $attribute->save();

        flash()->success('The attribute has been created successfully');

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
        $attribute = VendorAttributes::find($id);

        return view('admin.vendors.attributes.edit',['attribute'=>$attribute]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param EditUserAttributeRequest|EditVendorAttributeRequest $request
     * @return Response
     */
    public function update($id,EditVendorAttributeRequest $request)
    {
        $attribute = VendorAttributes::find($id);

        $attribute->name = $request->get('name');
        $attribute->alias = $request->get('alias');
        $attribute->type = $request->get('type');

        $attribute->save();

        flash()->success('The attribute has been edited successfully');

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
        VendorAttributes::destroy($id);

        flash()->success('The Attribute has been deleted successfully');
    }

}
