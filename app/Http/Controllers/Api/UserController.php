<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use Illuminate\Http\Request;
use WowTables\Http\Models\User;
use WowTables\Http\Requests\Api\UserLoginRequest;
use WowTables\Http\Requests\Api\UserRegistrationRequest;
use WowTables\Http\Requests\Api\UserFBLoginRequest;

class UserController extends Controller {

    /**
     * The user Model Object
     *
     * @var Object
     */
    protected $user;

    /**
     * The Http Request Object
     *
     * @var Object
     */
    protected $request;
    /**
     * The Constructor Method
     *
     * @param Request $request
     * @param User $user
     */
    public function __construct(Request $request, User $user){
        $this->user = $user;
        $this->request = $request;
    }

	/**
	 * Register user to the mobile app
	 *
	 * @return Response
	 */
	public function register(UserRegistrationRequest $userRegistrationRequest)
	{
        $input = $this->request->all();

        $userRegister = $this->user->mobileRegister($input);

        return response()->json($userRegister['data'], $userRegister['code']);
	}

	/**
	 * Login an existing user into the App
	 *
	 * @return Response
	 */
	public function login()
	{
        $input = $this->request->all();

        $userLogin = $this->user->mobileLogin($input);

        return response()->json($userLogin['data'], $userLogin['code']);
	}

	/**
	 * Login or Register a user with his FB credentials
	 *
	 * @return Response
	 */
	public function fb_login()
	{
        $input = $this->request->all();

        $userFbLogin = $this->user->mobileFbLogin($input);

        return response()->json($userFbLogin['data'], $userFbLogin['code']);
	}

    /**
     * Unlink a logged in user from his device
     *
     * @return Response
     */

    public function unlink()
    {

    }
}
