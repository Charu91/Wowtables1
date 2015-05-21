<?php  namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Profile;
use Request;

class ProfileController extends Controller {

    /**
     * Show the Profile Detail of the user
     *
     * @access	public
     * @param	string	$data
     * @return	json
     * @since	1.0.0
     */
    public function show($token) {
        return response()->json(Profile::getUserProfile($token),200);
    }

    //---------------------------------------------------------------------

    /**
     * Update the Profile Detail of the user
     *
     * @access	public
     * @param	string	$data
     * @return	json
     * @since	1.0.0
     */
    public function update() {
        $data = Request::all();
        return response()->json(Profile::updateProfile($data),200);
    }

    //---------------------------------------------------------------------

    /**
     * Set the Preferred Areas for the user
     *
     * @access	public
     * @param	string	$area
     * @return	json
     * @since	1.0.0
     */
    public function setPreferredAreas() {

        $area = Request::all();
        return response()->json(Profile::savePreferredAreas($area),200);
    }

    //---------------------------------------------------------------------

    /**
     * List the Preferred Areas for the user
     *
     * @access	public
     * @param	string	$userID
     * @return	json
     * @since	1.0.0
     */
    public function getPreferredArea($token) {

        $area = Request::all();
        return response()->json(Profile::getUserPreferences($token),200);
    }
} 