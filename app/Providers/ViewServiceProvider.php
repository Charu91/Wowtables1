<?php namespace WowTables\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\Factory as ViewFactory;

class ViewServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot(ViewFactory $view)
	{
        $view->composer('admin/*', 'WowTables\Http\ViewComposers\AdminComposer');
		$view->composer('*', 'WowTables\Http\ViewComposers\SiteComposer');
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{

	}

}
