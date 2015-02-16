<?php namespace WowTables\Http\Middleware;

use Closure;
use WowTables\Http\Models\User;

class RedirectAdminIfAuthenticated {

    /**
     * The user Model property
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        if ($this->user->auth->check())
        {
            if($this->user->can('access', 'admin')){
                return redirect()->route('AdminDashboard');
            }else{
                return redirect('/');
            }

        }

        return $next($request);
	}

}
