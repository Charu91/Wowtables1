<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
	
	/**
	 * 
	 */
	protected $table = 'tags';
	
	/**
	 * 
	 */
	protected $guarded = array('id');
	
	//-----------------------------------------------------------------
	
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
									'name' => $result->name
									);
		}
		
		return $arrData;		 
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Returns formatted array containing the tags for filtering.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array 	$arrTags
	 * @return	array
	 * @since	1.0.0
	 */
	public static function formatTagFilters($arrTags) {
		//array to store the tag filters
		$arrTagFilter = array();
		
		$arrTagFilter['name'] = 'Tags';
		$arrTagFilter['type'] = 'multi';
		
		$dbResult = Self::whereIn('name',$arrTags)->select('id','name')->get();
		
		if($dbResult) {
			foreach($dbResult as $row) {
				$arrTagFilter['options'][] = array(
												'id' => $row->id,
												'name' => $row->name
											);
			}
		}
		return $arrTagFilter;
	}
}
//end of class Tag
//end of file Wowtables/Http/Models/Tag.php
