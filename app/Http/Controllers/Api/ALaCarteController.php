<?php namespace WowTables\Http\Controllers\Api;

use Illuminate\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\ALaCarte;


/**
 * Controller class LaCarteController.
 * 
 * @since	1.0.0
 * @version	1.0.0
 * @author	Parth Shukla<shuklaparth@hotmail.com>
 */
class ALaCarteController extends Controller {
	
	/**
	 * Displays the details of the aLaCarte matching
	 * passed id.
	 * 
	 * @access	public
	 * @param	string	$name
	 * @return	json
	 * @since	1.0.0
	 */
	public function show( $aLaCarteID ) {
		$arrALaCarte = ALaCarte::getALaCarteDetails($aLaCarteID);
		
		return response()->json($arrALaCarte,200);
	}
}
//end of class LaCarteController
//end of file WowTables\Http\Controllers\Api\LaCarteController.php