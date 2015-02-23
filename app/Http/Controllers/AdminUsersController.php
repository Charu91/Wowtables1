<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Role;
use WowTables\Http\Models\Eloquent\User as EloquentUser;
use WowTables\Http\Models\User;
use WowTables\Core\Repositories\Users\UserRepository;
use WowTables\Http\Requests\Admin\CreateUserRequest;

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
	 * @param UserRepository $userRepo
	 * @param EloquentUser $eloquentUser
	 */
    function __construct(Request $request, User $user, UserRepository $userRepo,EloquentUser $eloquentUser)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
		$this->user = $user;
		$this->eloquentUser = $eloquentUser;
		$this->userRepo = $userRepo;
    }

	/**
	 * Display a listing of the resource.
	 *
     * @Get("/", as="AdminUsers")
	 * @return Response
	 */
	public function index()
	{
		$users = $this->eloquentUser->with('role')->get();

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
		$user = new EloquentUser();
		$user->role = new Role();

		return view('admin.users.create',['user'=>$user]);
	}

	/**
	 * Store a newly created resource in storage.
     *
	 * @Post("/", as="AdminUserStore")
	 * @return Response
	 */
	public function store(CreateUserRequest $createUserRequest)
	{
        $userCreate = $this->user->create($this->request->all());

        if($userCreate['status'] === 'success'){
            return response()->json([
                'status' => 'success'
            ], 200);
        }else{
            return response()->json([
                'status' => 'failure',
                'action' => $userCreate['action'],
                'message' => $userCreate['message']
            ], 400);
        }
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
        $user = $this->user->fetch($id);

		//return response()->json($user);

        if($user['status'] === 'success'){
            return 'Word!!';
        }else{
            return response()->json([
                'status' => 'failure',
                'action' => $userCreate['action'],
                'message' => $userCreate['message']
            ], 400);
        }
		//return view('admin.users.single');
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
		$user = $this->userRepo->getByUserId($id);

		return response()->json($user);

        return view('admin.users.edit',['user'	=> $user]);
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
		dd($this->request->all());
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
