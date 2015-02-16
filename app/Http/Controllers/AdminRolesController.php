<?php namespace WowTables\Http\Controllers;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use Illuminate\Http\Request;
use WowTables\Http\Models\Roles;
use WowTables\Http\Requests\CreateRoleRequest;

class AdminRolesController extends Controller {

    /**
     * The variable holding the roles model object
     *
     * @var Roles
     */
    protected $roles;

    /**
     * The constructor Method
     *
     * @param Request $request
     */
    function __construct(Request $request, Roles $roles)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
        $this->roles = $roles;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        //$this->roles->fetchAll()
	}

	/**
	 * Show the form for creating a new role.
	 *
	 * @return Response
	 */
	public function create()
	{

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateRoleRequest $request)
	{
        $input = $request->all();

        $new_role = $this->roles->addRole($input['role_name'], $input['permissions']);

        if($new_role['status'] === 'success'){
            return response()->json([], 200);
        }else{
            return response()->json(['message' => $new_role['message']], 400);
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

	}

	/**
	 * Update the specified resource in storage.
	 *
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
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
