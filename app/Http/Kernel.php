<?php namespace WowTables\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Pipeline\Pipeline;

class Kernel extends HttpKernel {

    /**
     * The Websites global HTTP middleware stack.
     *
     * @var array
     */
    protected $siteMiddleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        //'WowTables\Http\Middleware\VerifyCsrfToken',
    ];

    /**
     * The API global HTTP middleware stack.
     *
     * @var array
     */
    protected $apiMiddleware = [
        'WowTables\Http\Middleware\ContentTypeJson'
    ];

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'WowTables\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'WowTables\Http\Middleware\RedirectIfAuthenticated',
        'admin.auth' => 'WowTables\Http\Middleware\AdminAuthenticate',
        'redirect.admin.auth' => 'WowTables\Http\Middleware\RedirectAdminIfAuthenticated',
        'mobile.app.access' => 'WowTables\Http\Middleware\CheckMobileAccess',
        'wow.api' => 'WowTables\Http\Middleware\AuthorizeMiddleware',
	];

    /**
     * Send the given request through the middleware / router.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendRequestThroughRouter($request)
    {
        $this->app->instance('request', $request);

        $this->bootstrap();

        if($request->getHost() === env('WEBSITE_URL')){
            $this->middleware = $this->siteMiddleware;
        }else if($request->getHost() === env('API_URL')){
            $this->middleware = $this->apiMiddleware;
        }


        return (new Pipeline($this->app))
            ->send($request)
            ->through($this->middleware)
            ->then($this->dispatchToRouter());
    }

}
