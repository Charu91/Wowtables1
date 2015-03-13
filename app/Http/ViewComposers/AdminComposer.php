<?php namespace WowTables\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
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
        $view->with('restaurants_list',array_add(DB::table('vendors')->lists('name','id'),'0','Select Restaurant'));
        $view->with('restaurant_locations_list',VendorLocation::wherehas('vendor.vendorType', function($q){$q->where('type','Restaurants');})->lists('slug','id'));
        $view->with('locations_list',array_add(Location::where('Type','Locality')->lists('name','id'),'0','Select Location'));
        $view->with('locations_area_list',Location::where('Type','Area')->lists('name','id'));
        $view->with('cuisines',VendorAttributesSelectOptions::wherehas('attribute', function($q){$q->where('alias','cuisines');})->lists('option','id'));
        $view->with('_token', $this->encrypter->encrypt(csrf_token()));
    }
}