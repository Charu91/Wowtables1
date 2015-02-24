<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Products;
use Illuminate\Http\Request;

class ExperiencesController extends Controller {

    /**
     * The products Object
     *
     * @var object
     */
    protected $products;

    /**
     * The Http Request Object
     *
     * @var object
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request, Products $products)
    {
        $this->middleware('mobile.app.access');

        $this->products = $products;
        $this->request = $request;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $input = $this->request->all();

        $experiences = $this->products->fetchAll('mobile', ['events, experiences'], $input);

        return response()->json($experiences['data'], $experiences['code']);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

}
