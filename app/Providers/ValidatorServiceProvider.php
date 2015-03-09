<?php namespace WowTables\Providers;

use Illuminate\Support\ServiceProvider;
use DB;

class ValidatorServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        $this->app['validator']->extend('nonemptyarray', function ($attribute, $value, $parameters)
        {
            if(!is_array($value) || !count($value)){
                return false;
            }

            return true;
        });

        $this->app['validator']->extend('curatorarray', function ($attribute, $value, $parameters)
        {
            if(!is_array($value) || !count($value)){
                return false;
            }else{
                if(count($value) === DB::table('curators')->whereIn('id', $value)->count()){
                    return true;
                }else{
                    return false;
                }

            }


        });

        $this->app['validator']->extend('tagarray', function ($attribute, $value, $parameters)
        {
            if(!is_array($value) || !count($value)){
                return false;
            }else{
                if(count($value) === DB::table('tags')->whereIn('id', $value)->count()){
                    return true;
                }else{
                    return false;
                }
            }
        });

        $this->app['validator']->extend('galleryarray', function ($attribute, $value, $parameters)
        {
            if(!is_array($value) || !count($value)){
                return false;
            }else{
                if(count($value) === DB::table('media')->whereIn('id', $value)->count()){
                    return true;
                }else{
                    return false;
                }
            }
        });

        $this->app['validator']->extend('schedulearray', function ($attribute, $value, $parameters)
        {
            if(!is_array($value) || !count($value)){
                return false;
            }else{
                if(count($value) === DB::table('schedules')->whereIn('id', $value)->count()){
                    return true;
                }else{
                    return false;
                }
            }
        });

        $this->app['validator']->extend('restaurant', function ($attribute, $value, $parameters)
        {
            $restaurant_exists = DB::table('vendors')
                                    ->join('vendor_types','vendors.vendor_type_id','=','vendor_types.id')
                                    ->where('vendors.id',$value)
                                    ->count();
            if(!$restaurant_exists){
                return false;
            }

            return true;
        });
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

}
