<?php namespace WowTables\Http\Models;

use DB;
use Config;


/**
 * Model class Collection
 *
 * @package WowTables
 */
class CollectionTags {

	
	/**
     * Get the Collection List
     *
     * @static	true
     * @access	public
     * @param	 
     * @since	1.0.0
     */
        public static function readAllCollection() { 

        //echo "Hi...."; 
        	$queryResult=DB::table('tags as t')        	 		
        	 		->leftjoin('media_resized_new as mrn','t.media_id','=','mrn.id')
        	 		->where('status','=','available')
        	 		->select('t.id','t.name','t.status','t.description',
        	 				  DB::Raw('IFNULL(mrn.file,"") AS file')
        	 				)
        	 		->get();

        	 		$arrResponse['data']=$queryResult;        	 		
        	 		$arrResponse['status'] = Config::get('constants.API_SUCCESS');
            
            return $arrResponse;
        }
     
}
//end of class Collection.
//end of file Collection.php