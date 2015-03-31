<?php namespace WowTables\Http\Controllers\Api;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;
use WowTables\Http\Models\Eloquent\Tag;

class TagController extends Controller {
	
	/**
	 * 
	 */
	 public function index() {
	 	$result = Tag::readAll();
		
		return response()->json($result,200);
	 }
}
//end of class TagController
//end of file TagController.php