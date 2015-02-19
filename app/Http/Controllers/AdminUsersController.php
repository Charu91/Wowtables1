<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\User;

/**
 * Class AdminUsersController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="admin/users")
 */

class AdminUsersController extends Controller {

	/**
	 * The constructor Method
	 *
	 * @param Request $request
	 * @param User $user
	 */
    function __construct(Request $request,User $user)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
		$this->user = $user;
    }

	/**
	 * Display a listing of the resource.
	 *
     * @Get("/", as="AdminUsers")
	 * @return Response
	 */
	public function index()
	{
		$users = $this->user->with('role')->get();

		return view('admin.users.index',['users' => $users]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
     * @Get("/create", as="AdminUserCreate")
	 * @return Response
	 */
	public function create()
	{
		return view('admin.users.create');
	}

	/**
	 * Store a newly created resource in storage.
     *
	 * @Post("/", as="AdminUserStore")
	 * @return Response
	 */
	public function store()
	{

	}

	/**
	 * Display the specified resource.
	 *
     * @Get("/{id}", as="AdminUsersShow")
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return view('admin.users.single');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
     * @Get("/edit/{id}", as="AdminUserEdit")
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return view('admin.users.update');
	}

	/**
	 * Update the specified resource in storage.
	 *
     * @Put("/{id}", as="AdminUserUpdate")
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
     * @Delete("/", as="AdminUserDelete")
	 * @return Response
	 */
	public function destroy()
	{
		//
	}

}
