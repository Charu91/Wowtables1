<?php namespace WowTables\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Role;
use WowTables\Http\Models\Eloquent\Location;
use WowTables\Http\Models\Eloquent\UserAttributes;
use WowTables\Http\Models\User;
use Illuminate\Contracts\Encryption\Encrypter;
use Auth;

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
        $view->with('user_attributes_types_list',UserAttributes::lists('type','type'));
        $view->with('locations_list',Location::where('Type','City')->lists('name','id'));
        $view->with('_token', $this->encrypter->encrypt(csrf_token()));
    }
}