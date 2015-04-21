<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

class Location extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'locations';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = array('id');

    protected $visible = array('id','name');
	
	//-----------------------------------------------------------------
	
	/**
	 * Formats the details of the location for filtering  
	 * whose name is in the passed array.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array 	$arrLocation
	 * @return	array
	 * @since	1.0.0
	 */
	public static function formatLocationFilters($arrLocation) {
		//array to store the locaiton details
		$arrLocationFilter = array();
		
		$arrLocationFilter['name'] = 'Locations';
		$arrLocationFilter['type'] = 'Multi';
		
		//query to read the location details
		$queryResult = Self::whereIn('name',$arrLocation)->select('id','name')->get();
		
		if($queryResult) {
			foreach ($queryResult as $row) {
				$arrLocationFilter['options'][] = array(
														"id" => $row->id,
														"name" => $row->name
													);
			}
		}		
		return $arrLocationFilter;
	}	
	
}
//end of class Location
//end of file WowTables\Http\Models\Eloquent\Location.php