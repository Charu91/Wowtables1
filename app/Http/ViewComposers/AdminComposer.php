<?php namespace WowTables\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use WowTables\Http\Models\User;
use Illuminate\Contracts\Encryption\Encrypter;

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
        $view->with('user', $this->user);
        $view->with('_token', $this->encrypter->encrypt(csrf_token()));
    }
}