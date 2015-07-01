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
    public function __construct(Request $request, User $user)
    {
        $this->middleware('mobile.app.access', ['only' => ['set_location_id_phone']]);

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
	public function login(UserLoginRequest $userLoginRequest)
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
	public function fb_login(UserFBLoginRequest $userFBLoginRequest)
	{
        $input = $this->request->all();

        $userFbLogin = $this->user->mobileFbLogin($input);

        return response()->json($userFbLogin['data'], $userFbLogin['code']);
	}

    public function set_location_id_phone()
    {
        $input = $this->request->all();

        if(!isset($input['location_id']) && !isset($input['phone_number'])){
            response()->json([
                'action' => 'Check for the phone number and the location input',
                'message' => 'Check for validation errors in the input'
            ], 422);
        }else{

            $updateUser = $this->user->updateLocationAndPhone(
                $input['user']->user_id,
                [
                    'phone_number' => $input['phone_number'],
                    'location_id' => $input['location_id']
                ]
            );

            return response()->json($updateUser['data'], $updateUser['code']);
        }
    }

    /**
     * Get total points of the user
     *
     * @return Response
     */
    public function getPoints() {
        $userPoints = User::showPoints();       
        return response()->json($userPoints, 200);
    }
}
