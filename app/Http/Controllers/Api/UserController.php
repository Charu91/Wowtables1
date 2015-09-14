<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use Illuminate\Http\Request;
use WowTables\Http\Models\User;
use WowTables\Http\Requests\Api\UserLoginRequest;
use WowTables\Http\Requests\Api\UserRegistrationRequest;
use WowTables\Http\Requests\Api\UserFBLoginRequest;
use Mailchimp;
use WowTables\Http\Models\Eloquent\Location;

class UserController extends Controller {

    protected $mailchimp;
    protected $listId = '986c01a26a';

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
    public function __construct(Request $request, User $user, Mailchimp $mailchimp)
    {
        $this->middleware('mobile.app.access', ['only' => ['set_location_id_phone']]);

        $this->user = $user;
        $this->request = $request;
        $this->mailchimp = $mailchimp;
    }

	/**
	 * Register user to the mobile app
	 *
	 * @return Response
	 */
	public function register(UserRegistrationRequest $userRegistrationRequest)
	{
        $input = $this->request->all();

        //$userRegister = $this->user->mobileRegister($input, $this->mailchimp);
        $userRegister = $this->user->mobileRegister($input);
        
        if($userRegister['code'] == 200) {
        //===================Mail Chimp Start ======================
        $users = $input;

        $cityarray = DB::select("SELECT name FROM locations WHERE id=".$data['location_id']);
        $city_name = $cityarray[0]->name; 
        //$city_name = Location::where(['Type' => 'City', 'id' => $users['city']])->pluck('name');
        if(empty($city_name))
        {
            $city_name = 'mumbai';
        }
        
        $merge_vars = array(
            'NAME'      => isset($users['full_name'] )? $users['full_name']: '',
            'SIGNUPTP'  => isset($facebook_id)? 'Facebook': 'Email',
            'BDATE'     => isset($users['dob'])? $users['dob']: '',
            'GENDER'    => isset($users['gender'])? $users['gender']: '',
            'MERGE11'   => 0,
            'MERGE17'   => 'Has WowTables account',
            'PHONE'     => isset($users['phone'])? $users['phone']: '',
            'MERGE18'   => isset($_GET["utm_source"])? $_GET["utm_source"]: '',
            'MERGE19'   => isset($_GET["utm_medium"])? $_GET["utm_medium"]: '',
            'MERGE20'   => isset($_GET["utm_campaign"])? $_GET["utm_campaign"]: ''
        );

        $this->mailchimp->lists->subscribe($this->listId, ["email"=>$_POST['email']],$merge_vars,"html",false,true );
        
        $my_email = $users['email_address'];
        
        $city = $city_name;
            $mergeVars = array(
                'GROUPINGS' => array(
                    array(
                        'id' => 9613,
                        'groups' => ucfirst($city),
                    )
                )
            );
            
        $this->mailchimp->lists->updateMember($this->listId, $my_email, $mergeVars);
        //===================MAil Chimp End ========================
        }

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
