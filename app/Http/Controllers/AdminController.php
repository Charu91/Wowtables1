<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Http\Response;
use WowTables\Http\Requests\LoginUserRequest;
use Illuminate\Contracts\Encryption\Encrypter;
use WowTables\Http\Models\User;
use Session;

/**
 * Class AdminController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="admin")
 */

class AdminController extends Controller {

    /**
     * The user model object
     *
     * @var User
     */
    protected $user;

    /**
     * The constructor method
     */
    public function __construct(User $user)
    {
        $this->middleware('admin.auth', ['except' => ['loginView', 'login', 'logout']]);
        $this->middleware('redirect.admin.auth', ['only' => 'loginView']);

        $this->user = $user;
    }
	/**
	 * Redirect to admin dashboard if logged in. Else to the login page
	 *
	 * @return Response
	 */

	public function index()
	{
        return redirect()->route('AdminDashboard');
	}

    /**
     * Redirect to admin dashboard if logged in. Else to the login page
     *
     * @return Response
     */

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * The login View
     *
     * @return Response
     */
    public function loginView(Encrypter $encrypter){

        $token = $encrypter->encrypt(csrf_token());

        return view('admin.login', ['_token' => $token]);
    }

    /**
     * The login authentication
     *
     * @return Response
     */
    public function login(Request $request, LoginUserRequest $loginUserRequest){

        $login = $this->user->login($request->input('email'), $request->input('password'), 1);

        if($login['state'] === 'success')
			{
				if($login['role'] === 'Admin')
					{
						return response()->json(['redirect_url' => '/admin/dashboard']);
					}
				else 
					{
						return response()->json(['message' => 'You are not admin'], 400);
					}
			}
		else
			{
            return response()->json(['message' => $login['message']], 400);
			}
    }

    /**
     * The user logout method
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(){

        $this->user->logout();
        Session::flush();
        return redirect()->route('AdminLoginView');
    }
}
