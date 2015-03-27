<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model {
	
	/**
	 * 
	 */
	protected $table = 'vendor_attributes_select_options';
	
	/**
	 * 
	 */
	protected $guarded = array('id');
	
	/**
	 * 
	 */
	public static function readAll() {
		$results = Self::all();
		
		#formatting the result to json
		$arrData = array();
		
		foreach ($results as $result) {
			$arrData['data'][] = array(
									'id' => $result->id,
									'name' => $result->option
									);
		}		
		return $arrData;		 
	}
}
//end of class Tag
//end of file Wowtables/Http/Models/Cuisine.php