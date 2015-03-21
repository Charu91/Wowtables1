<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Events\Site\NewUserWasRegistered;
use WowTables\Http\Models\Eloquent\Reward;
use WowTables\Http\Models\Eloquent\Role;
use WowTables\Http\Models\Eloquent\User as EloquentUser;
use WowTables\Http\Models\User;
use WowTables\Core\Repositories\Users\UserRepository;
use WowTables\Http\Requests\Admin\CreateUserRequest;
use WowTables\Http\Requests\CreateRewardRequest;

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
		return view('admin.users.create');
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

			event(new NewUserWasRegistered($this->request->get('email'),$this->request->get('full_name')));

			if($createUserRequest->ajax())
			{
				return response()->json([
					'status' => 'success'
				], 200);
			}

			flash()->success('User has been successfully created!');

			return redirect()->route('AdminUsers');

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
	public function destroy($id)
	{
		EloquentUser::destroy($id);

		flash()->success('The User has been deleted successfully');

	}

    public function create_reward($id)
    {
        $users = \WowTables\Http\Models\Eloquent\User::find($id);

        $rewards = $this->user->get_all_records($id);
        return view('admin.users.rewards',['user_id'=>$id,'rewards'=>$rewards,'users'=>$users]);
    }

    public function store_rewards(CreateRewardRequest $request)
    {
        $reward = new Reward();
        $users = \WowTables\Http\Models\Eloquent\User::find($this->request->get('user_id'));

        $reward->user_id = $this->request->get('user_id');
        if($this->request->get('status') == "add_points"){
            $reward->points_earned = $this->request->get('points');
            $reward->points_redeemed = 0;
            $reward->points_removed = 0;
            $users->total_points = $users->total_points + $this->request->get('points');
        } else if($this->request->get('status') == "redeem_points"){
            $reward->points_earned = 0;
            $reward->points_removed = 0;
            $reward->points_redeemed = $this->request->get('points');;
            $users->total_points = $users->total_points - $this->request->get('points');
        } else if($this->request->get('status') == "remove_points"){
            $reward->points_earned = 0;
            $reward->points_redeemed = 0;
            $reward->points_removed = $this->request->get('points');;
            $users->total_points = $users->total_points - $this->request->get('points');
        }

        $reward->description = $this->request->get('short_description');

        $reward->save();
        $users->save();

        flash()->success('The Reward has been created successfully');

        return redirect('admin/users');
    }

}
