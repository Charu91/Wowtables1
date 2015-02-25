<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\UserAttributes;
use WowTables\Http\Requests\CreateUserAttributeRequest;
use WowTables\Http\Requests\EditUserAttributeRequest;

class UserAttributesController extends Controller {


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
		$attributes = UserAttributes::all();

		return view('admin.users.attributes.index',['attributes'=>$attributes]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param CreateUserAttributeRequest $request
	 * @return Response
	 */
	public function store(CreateUserAttributeRequest $request)
	{
		$attribute = new UserAttributes();

		$attribute->name = $this->request->get('name');
		$attribute->alias = $this->request->get('alias');
		$attribute->type = $this->request->get('type');

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
		$attribute = UserAttributes::find($id);

		return view('admin.users.attributes.edit',['attribute'=>$attribute]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 * @param EditUserAttributeRequest $request
	 * @return Response
	 */
	public function update($id,EditUserAttributeRequest $request)
	{
		$attribute = UserAttributes::find($id);

		$attribute->name = $this->request->get('name');
		$attribute->alias = $this->request->get('alias');
		$attribute->type = $this->request->get('type');

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
		UserAttributes::destroy($id);

		flash()->success('The Attribute has been deleted successfully');
	}

}
