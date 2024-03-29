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
use WowTables\Http\Models\Eloquent\Location;
use WowTables\Http\Models\Profile;
use Validator;
use Redirect;
use Request as RequestData;
use DB;
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
		//$users = $this->eloquentUser->with('role')->get();
		$users = DB::table('users')->select('id','full_name','email','phone_number')->paginate(100);
		//echo "<pre>"; print_r($users); die;

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
		//$user = $this->userRepo->getByUserId($id);
		$cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
		$data=Profile::getUserProfileWeb($id);
		$role = DB::table('roles')
                    -> select('id','name')
                    -> get();
           /*		print_r($role);
		exit;*/
        return view('admin.users.edit',['data'	=> $data])->with('cities',$cities)->with('role',$role);
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
		$data = RequestData::all();
				$rules = array(
        		'full_name' => 'required',
				'role_id' => 'required',
				'phone_number' => 'required',
				'date_of_birth' => 'required',
				'gender' => 'required',
				'location_id' => 'required',
				'newsletter_frequency' => 'required'			
			);

			$message = array(
				'required' => 'The :attribute is required', 
			);

			$validation = Validator::make($data, $rules, $message);

			if($validation->fails())
			{
				return Redirect::to("admin/users/$id/edit")->withErrors($validation);
			}
			else
			{
        	$arrResponse=Profile::updateProfileWebAdmin($data, $id);
        	return Redirect::to('/admin/users')
		                ->with('flash_notice', '');
		     }

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

		$points_earned = $this->user->get_all_points_earned($id);
		$points_spent = $this->user->get_all_points_spent($id);

        return view('admin.users.rewards',['user_id'=>$id,'rewards'=>$rewards,'users'=>$users,'points_earned'=>$points_earned,'points_spent'=>$points_spent]);
    }

    public function store_rewards(CreateRewardRequest $request)
    {

		$points = $this->request->get('points');
		$description = $this->request->get('short_description');
		$user_id = $this->request->get('user_id');
		$status = $this->request->get('status');

		Profile::updatePointsManually($points,$description,$user_id,$status);


        flash()->success('The Reward has been created successfully');

        return redirect('admin/users');
    }

	public function search_users($userVal){

		$usersResults = DB::select('SELECT id,full_name,email,phone_number from users WHERE full_name LIKE "%'.$userVal.'%" OR email LIKE "%'.$userVal.'%" OR phone_number LIKE "%'.$userVal.'%"');
		$createTableStructure = '';
		if(!empty($usersResults)){
			$createTableStructure = '<tr>';
			foreach($usersResults as $userData){

				$createTableStructure .= '<th>'.$userData->id.'</th>';
				$createTableStructure .= '<th>'.$userData->full_name.'</th>';
				$createTableStructure .= '<th>'.$userData->email.'</th>';
				$createTableStructure .= '<th>'.$userData->phone_number.'</th>';
				$createTableStructure .= '<th>'.link_to_route("AdminUserEdit","Edit",$userData->id,["target"=>"_blank","class"=>"btn btn-xs btn-primary","data-user-id"=>$userData->id]).' &nbsp;|&nbsp;<a data-user-id="'.$userData->id.'" class="btn btn-xs btn-danger delete-user-btn">Delete</a>&nbsp;|&nbsp;<a href="/admin/users/'.$userData->id.'/create_reward" data-user-id="'.$userData->id.'" class="btn btn-xs btn-primary">Rewards</a>&nbsp;|&nbsp;<a data-email-id="'.$userData->email.'" class="btn btn-xs btn-primary" id="ad_forgot_password">Reset Password</a></th>';
				$createTableStructure .= '</tr>';
			}


		} else {
			$createTableStructure = '<tr> <th colspan="5"> No Data Found!! </th><tr>';
		}

		echo "<tbody>".$createTableStructure."</tbody>";

	}

}
