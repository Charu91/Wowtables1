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
        	 		//->leftjoin('media_resized_new as mrn','t.media_id','=','mrn.id')
        	 		->leftJoin('media_resized_new as mrn1', function($join) {
                                                $join->on('mrn1.media_id', '=', 't.media_id')
                                                     ->where('mrn1.image_type', '=' , 'mobile_listing_ios_experience');
                        })
                    ->leftJoin('media_resized_new as mrn2', function($join) {
                                                $join->on('mrn2.media_id', '=', 't.media_id')
                                                     ->where('mrn2.image_type', '=', 'mobile_listing_android_experience');
                        })
                    ->where('status','=','available')
        	 		->select('t.id','t.name','t.status','t.description',
        	 				  //DB::Raw('IFNULL(mrn.file,"") AS file',
                            'mrn1.file as ios_image','mrn2.file as android_image',
                            't.slug'                               
        	 				)   
        	 		->get();

                    if($queryResult) {
                        foreach($queryResult as $row) {
                            $data[] = array(
                                                            'id'          => $row->id,
                                                            'name'        => $row->name,
                                                            'status'      => (empty($row->status)) ? "" : $row->status,
                                                            'description' => (empty($row->description)) ? "" : $row->description,
                                                            'slug'        => (empty($row->slug)) ? "" : $row->slug,                                                            
                                                            'image' => array(
                                                                                'mobile_listing_ios_experience' => (empty($row->ios_image))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->ios_image,
                                                                                'mobile_listing_android_experience' => (empty($row->android_image))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->android_image,
                                                                             )
                                                        );
                        }
                    }
                    else {
                        $data[] = array();
                    }

                            
            $arrResponse['data']=$data;      
        	//$arrResponse['data']=$queryResult;        	 		
        	$arrResponse['status'] = Config::get('constants.API_SUCCESS');
            
            return $arrResponse;
        }

        //-------------------------------------------------------------

        /**
         * Returns the ID of the slug.
         *
         * @static
         * @access  public
         * @param   string   $slug
         * @return    integer
         * @since   1.0.0
         */
        public static function getSlugID($slug) {
            $query = DB::table('products')
                            ->where('slug', $experienceID)
                            ->select('id')
                            ->first();
            if($query){
                return $query->id;
            }

            return 0;
        }
     
}
//end of class Collection.
//end of file Collection.php