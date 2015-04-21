<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\User;
use WowTables\Http\Requests\Site\CustomerLoginUserRequest;
use Illuminate\Http\Request;

/**
 * Class SessionsController
 * @package WowTables\Http\Controllers\Site
 */
class SessionsController extends Controller {


    /**
     * @param User $user
     * @param Request $request
     */
    function __construct(User $user,Request $request)
    {
        $this->user = $user;
        $this->request = $request;
    }


    /**
     * The user login  View method
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginView()
    {
        return view('site.users.login');
    }

    /**
     * The user login method
     *
     * @param CustomerLoginUserRequest $customerLoginUserRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(CustomerLoginUserRequest $customerLoginUserRequest)
    {
        $login = $this->user->login($this->request->input('email'), $this->request->input('password'), $this->request->input('remember_me') ? 1 :0);

        if($login['state'] === 'success'){
            flash()->success('You are now logged In!');
            return redirect()->route('SiteHomePageLoggedIn');
        }else{
            return redirect()->back()->withErrors($login['message'])->withInput();
        }
    }


    /**
     * The user logout method
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        $this->user->logout();

        return redirect()->route('SiteHomePage');
    }
}
