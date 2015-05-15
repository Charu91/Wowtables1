<?php namespace GIU\Http\Controllers\Site;

use GIU\Http\Controllers\Controller;
use GIU\Http\Models\User;
use GIU\Http\Requests\Site\CustomerLoginUserRequest;
use Illuminate\Http\Request;

class SessionsController extends Controller {


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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(CustomerLoginUserRequest $customerLoginUserRequest)
    {
        $login = $this->user->login($this->request->input('email'), $this->request->input('password'), $this->request->input('remember_me') ? 1 :0);

        if($login['state'] === 'success'){
            return redirect()->route('SiteHomePageLoggedIn');
        }else{
            return redirect()->back()->withErrors($customerLoginUserRequest)->withInput();
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
