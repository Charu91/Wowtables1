<?php namespace WowTables\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laracasts\Utilities\JavaScript\JavaScriptFacade;
use WowTables\Http\Models\Eloquent\Collection;
use WowTables\Http\Models\Eloquent\Curator;
use WowTables\Http\Models\Eloquent\Flag;
use WowTables\Http\Models\Eloquent\Role;
use WowTables\Http\Models\Eloquent\Location;
use WowTables\Http\Models\Eloquent\UserAttributes;
use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocation;
use WowTables\Http\Models\Eloquent\Vendors\VendorAttributes;
use WowTables\Http\Models\Eloquent\Vendors\Vendor;
use WowTables\Http\Models\Eloquent\Vendors\VendorAttributesSelectOptions;
use WowTables\Http\Models\User;
use Illuminate\Contracts\Encryption\Encrypter;
use Auth;
use DB;
use Config;

class AdminComposer {

    protected $request;

    protected $user;

    protected $encrypter;

    public function __construct(Request $request, User $user, Encrypter $encrypter){
        $this->request = $request;
        $this->user = $user;
        $this->encrypter = $encrypter;
    }

    public function compose(View $view){
        $view->with('uri', $this->request->path());
        $view->with('currentUser', $this->user);
        $view->with('roles_list',Role::lists('name','id'));
        $view->with('user_attributes_list',UserAttributes::lists('name','alias'));
        $view->with('restaurant_attributes_list',VendorAttributes::lists('name','alias'));
        $view->with('restaurant_locations_list',VendorLocation::wherehas('vendor.vendorType', function($q){$q->where('type','Restaurants');})->lists('slug','id'));
        $view->with('cities_list',Location::where('Type','City')->lists('name','id'));
        $view->with('locations_area_list',Location::where('Type','Area')->lists('name','id'));
        $view->with('cuisines',VendorAttributesSelectOptions::wherehas('attribute', function($q){$q->where('alias','cuisines');})->lists('option','id'));
        $view->with('_token', $this->encrypter->encrypt(csrf_token()));
        $view->with('media_url',Config::get('media.base_s3_url'));
        $view->with('curator_list',Curator::lists('name','id'));
        $view->with('tags_list',Collection::lists('name','id'));
        $view->with('flags_list',Flag::lists('name','id'));
        $view->with('restaurants_list',DB::table('vendors')->lists('name','id'));
        $view->with('locations_list',Location::where('Type','Locality')->lists('name','id'));

        $variant_lists = DB::table('product_variant_options')->lists('variation_name','id');
        $view->with('variant_list',$variant_lists);
        $complex_experience_list = DB::table('products')->where('type', "complex")->lists('name','id');
        $view->with('complex_experience_list',$complex_experience_list);

        $curators_list = Curator::all()->lists('name','id');
        $curatorsList = [];
        foreach($curators_list as $key => $value)
        {
            $curatorsList[] = [ 'id' => $key , 'text' => $value];
        }

        $restaurants_list = DB::table('vendors')->lists('name','id');
        $restaurantsList = [];
        foreach($restaurants_list as $key => $value)
        {
            $restaurantsList[] = [ 'id' => $key , 'text' => $value];
        }

        $localities_list = Location::where('Type','Locality')->lists('name','id');
        $localitiesList = [];
        foreach($localities_list as $key => $value)
        {
            $localitiesList[] = [ 'id' => $key , 'text' => $value];
        }


        JavaScriptFacade::put([
           'curatorsList' =>  $curatorsList,
           'restaurantsList' => $restaurantsList,
           'localitiesList' => $localitiesList
        ]);

    }
}