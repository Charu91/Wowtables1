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

        $this->app['validator']->extend('flagarray', function ($attribute, $value, $parameters)
        {
            if(!is_array($value) || !count($value)){
                return false;
            }else{
                if(count($value) === DB::table('flags')->whereIn('id', $value)->count()){
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

        $this->app['validator']->extend('vendorcuisinesarray', function ($attribute, $value, $parameters)
        {
            if(!is_array($value) || !count($value)){
                return false;
            }else{
                $cusinesIdCount = DB::table('vendor_attributes as va')
                                        ->join('vendor_attributes_select_options as vao', 'vao.vendor_attribute_id', '=', 'va.id')
                                        ->whereIn('vao.id', $value)
                                        ->where('va.alias', 'cuisines')
                                        ->count();

                if(count($value) === $cusinesIdCount){
                    return true;
                }else{
                    return false;
                }
            }
        });

        $this->app['validator']->extend('productcuisinesarray', function ($attribute, $value, $parameters)
        {
            if(!is_array($value) || !count($value)){
                return false;
            }else{
                $cusinesIdCount = DB::table('product_attributes as pa')
                    ->join('product_attributes_select_options as paso', 'paso.product_attribute_id', '=', 'pa.id')
                    ->whereIn('paso.id', $value)
                    ->where('pa.alias', 'cuisines')
                    ->count();

                if(count($value) === $cusinesIdCount){
                    return true;
                }else{
                    return false;
                }
            }
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
