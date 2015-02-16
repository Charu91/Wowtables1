<?php namespace WowTables\Http\Controllers;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Http\Request;

/**
 * Class AdminSettingsController
 * @package WowTables\Http\Controllers
 *
 * @Controller(prefix="admin/settings")
 */

class AdminSettingsController extends Controller {

    /**
     * The constructor Method
     *
     * @param Request $request
     */
    function __construct(Request $request)
    {
        $this->middleware('admin.auth');
        $this->request = $request;
    }

    /**
     * Display the general settings for the app
     *
     * @Get("/general", as="adminSettingsGeneral")
     * @return Response
     */
    public function general()
    {

    }

	/**
	 * Display the locations available for gourmetitup
	 *
     * @Get("/locations", as="adminSettingsLocations")
	 * @return Response
	 */
	public function locations(Encrypter $encrypter)
	{
        $token = $encrypter->encrypt(csrf_token());

		return view('admin.settings.locations', ['_token' => $token]);
	}

}
