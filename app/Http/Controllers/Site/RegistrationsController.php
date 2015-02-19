<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\User;
use Illuminate\Http\Request;
use WowTables\Http\Requests\Site\CustomerRegisterUserRequest;

class RegistrationsController extends Controller {


	/**
	 * @var User
	 */
	protected $user;

	function __construct(Request $request,User $user)
	{
		$this->request = $request;
		$this->user = $user;
	}

	public function registerView()
	{
		return view('site.users.register');
	}

	public function register(CustomerRegisterUserRequest $customerRegisterUserRequest)
	{
		$createUser = $this->dispatchFrom('WowTables\Commands\Site\RegisterUserCommand', $customerRegisterUserRequest);

		if ( $createUser['state'] == 'success' )
		{
			return redirect()->route('SiteHomePageLoggedIn');
		}
	}
}
