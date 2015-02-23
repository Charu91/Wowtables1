<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\UserDevices;
use Illuminate\Http\Request;

class UserDevicesController extends Controller {

    /**
     * The user devices object
     *
     * @var Object
     */
	protected $userDevices;

    /**
     * The user devices object
     *
     * @var Object
     */
    protected $request;

    /**
     * The Contructor Object
     *
     * @param UserDevices $userDevices
     * @param Request $request
     */
    public function __construct(UserDevices $userDevices, Request $request)
    {
        $this->middleware('mobile.app.access');

        $this->request = $request;
        $this->userDevices = $userDevices;
    }

    /**
     * Unlink a logged in user from his device
     *
     * @return Response
     */
    public function unlink()
    {
        $input = $this->request->all();

        $userDeviceUnlink = $this->userDevices->unlink($input);

        return response()->json($userDeviceUnlink['data'], $userDeviceUnlink['code']);
    }

    /**
     * Add/Update a notification id for the device
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function notification()
    {
        $input = $this->request->all();

        $userDeviceNotification = $this->userDevices->addNotificationId($input);

        return response()->json($userDeviceNotification['data'], $userDeviceNotification['code']);
    }

}
