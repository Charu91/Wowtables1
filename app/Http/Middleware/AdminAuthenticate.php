<?php namespace WowTables\Http\Middleware;

use Closure;
use WowTables\Http\Models\User;

class AdminAuthenticate {

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
        if ($this->user->auth->guest())
        {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                return redirect()->route('AdminLogin');
            }
        }

        return $next($request);
    }

}

