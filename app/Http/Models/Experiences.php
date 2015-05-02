<?php namespace WowTables\Http\Models;

use DB;
use Config;
use WowTables\Http\Models\Review;


/**
 * Class Experiences
 * 
 * @since	1.0.0
 * @version	1.0.0
 * @author	Parth Shukla <shuklaparth@hotmail.com>
 */
class Experiences {
	
	/**
	 * Returns the details of the experience
	 * matching the passed id.
	 * 
	 * @access	public
	 * @param	integer		$experienceID
	 * @since	1.0.0
	 * @version	1.0.0
	 */
	public function find($experienceID) {
		//query to read product type
		$queryType = DB::table('products')
						->where('id',$experienceID)
						->select('name','type')
						->first();
		
		//query to read the experience detail
		$queryExperience = DB::table('products')
							->leftJoin(DB::raw('product_attributes_text as pat1'),'pat1.product_id','=','products.id')
							->leftJoin(DB::raw('product_attributes_text as pat2'),'pat2.product_id','=','products.id')
							->leftJoin(DB::raw('product_attributes as pa1'), 'pa1.id','=','pat1.product_attribute_id')
							->leftJoin(DB::raw('product_attributes as pa2'), 'pa2.id','=','pat2.product_attribute_id')
							->leftJoin(DB::raw('product_pricing as pp'), 'pp.product_id','=','products.id')
							->leftJoin(DB::raw('product_vendor_locations as pvl'),'pvl.product_id','=','products.id')
							->leftJoin(DB::raw('vendor_locations as vl'),'vl.id','=','pvl.vendor_location_id')
							->leftJoin(DB::raw('product_media_map as pmm'), function($join){
								$join->on('pmm.product_id','=','products.id')
									->where('pmm.media_type','=','main');
							})
							->leftJoin(DB::raw('product_curator_map as pcm'),'pcm.product_id','=','products.id')
							->leftJoin('curators','curators.id','=','pcm.curator_id')
							->leftJoin('media','media.id','=','pmm.media_id')
							->leftJoin(DB::raw('media_resised_new as cm'),'cm.id','=','curators.media_id')
							->where('products.id',$experienceID)
							->where('pa1.alias','experience_info')
							->where('pa2.alias','short_description');
		
		//adding additional parameters in case of simple experience
		if($queryType && $queryType->type == 'simple') {			
			$queryExperience->leftJoin(DB::raw('product_attributes_text as pat3'),'pat3.product_id','=','products.id')
							->leftJoin(DB::raw('product_attributes as pa3'), 'pa3.id','=','pat3.product_attribute_id')
							->where('pa3.alias','menu')
							->select('products.id','products.name','products.type','pp.price','pp.tax','pp.price_type',
								'pp.is_variable','pp.post_tax_price', DB::raw('pat1.attribute_value as experience_info'),
								DB::raw('pat2.attribute_value as short_description, media.file as experience_image'),
								DB::raw('curators.name as curator_name, curators.bio as curator_bio, curators.designation'),
								DB::raw('pat3.attribute_value as menu'),
								'cm.file as curator_image');
							
		}
		else {
				$queryExperience->leftJoin(DB::raw('product_attributes_text as pat3'),'pat3.product_id','=','products.id')
							->leftJoin(DB::raw('product_attributes as pa3'), 'pa3.id','=','pat3.product_attribute_id')
							->where('pa3.alias','menu')
							->select('products.id','products.name','products.type','pp.price','pp.tax','pp.price_type',
								'pp.is_variable','pp.post_tax_price', DB::raw('pat1.attribute_value as experience_info'),
								DB::raw('curators.name as curator_name, curators.bio as curator_bio, curators.designation'),
								DB::raw('pat2.attribute_value as short_description, 
									media.file as experience_image'),
									'cm.file as curator_image');
		}

		//running the query to get the results
		//echo $queryExperience->toSql();
		$expResult = $queryExperience->first();		
		
		//array to store the experience details
		$arrExpDetails = array();
		
		if($expResult) {
			//getting the reviews for the particular experience
				$arrReviews = Review::readProductReviews($expResult->id);
				$arrLocation = Self::getProductLocations($expResult->id);
				$arrImage = Self::getProductImages($experienceID);			
				$arrExpDetails['data'] = array(
										'id' => $expResult->id,
										'name' => $expResult->name,
										'experience_info' => $expResult->experience_info,
										'short_description' => $expResult->short_description,
										'image' => $arrImage,
										'type' => $expResult->type,
										'price' => (is_null($expResult->post_tax_price)) ? $expResult->price : $expResult->post_tax_price,
										'taxes' => (is_null($expResult->post_tax_price)) ? 'exclusive':'inclusive',
										'pre_tax_price' => (is_null($expResult->price))? "" : $expResult->price,
										'post_tax_price' => (is_null($expResult->post_tax_price)) ? "" : $expResult->post_tax_price,
										'tax' => (is_null($expResult->tax)) ? "": $expResult->tax,
										'price_type' => (is_null($expResult->price_type)) ? "": $expResult->price_type,
										'curator_name' => (is_null($expResult->curator_name)) ? "":$expResult->curator_name,
										'curator_bio' => (is_null($expResult->curator_bio)) ? "":$expResult->curator_bio,
										'curator_image' => (is_null($expResult->curator_image)) ? "" : Config::get('constants.API_MOBILE_IMAGE_URL').$expResult->curator_image,
										'curator_designation' => (is_null($expResult->designation)) ? "":$expResult->designation,
										'menu' => $expResult->menu,
										'rating' => (is_null($arrReviews['avg_rating'])) ? 0 : $arrReviews['avg_rating'],
										'total_reviews' => $arrReviews['total_rating'],
										'review_detail' => $arrReviews['reviews'],
										'location' => $arrLocation,
										'similar_option' => array(),
									);
				
				
		}
		return $arrExpDetails;					
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Reads the details of all the complex project
	 */
	public function getComplexProductAttribute($productID) {
		//query to read complex record id
		$queryComplexProduct = DB::table('products')
								->join(DB::raw('product_complex_options_map AS pcom'), 'pcom.variant_product_id', '=', 'products.id')
								->leftJoin(DB::raw('product_variant_options AS pvo'), 'pvo.id', '=', 'pcom.product_variant_option_id')
								->leftJoin(DB::raw('product_attributes_text AS pat1'), 'pat1.product_id', '=', 'pcom.variant_product_id')
								->leftJoin(DB::raw('product_attributes_text AS pat2'), 'pat2.product_id', '=', 'pcom.variant_product_id')
								->leftJoin(DB::raw('product_attributes AS pa'), 'pa.id', '=', 'pat1.product_attribute_id')
								->leftJoin(DB::raw('product_attributes AS pa2'), 'pa2.id', '=', 'pat2.product_attribute_id')
								->leftJoin(DB::raw('product_pricing AS pp'), 'pp.product_id','=','pcom.variant_product_id')
								->where('pcom.complex_product_id',$productID)
								->where('pa.alias','short_description')
								->where('pa2.alias','Menu')
								->groupBy('products.id')
								->select('products.id','products.name','products.type','pvo.variation_name',
									DB::raw('pat1.attribute_value as experience_includes, pat2.attribute_value as
									 menu'),'pp.price','pp.price_type','pp.tax','pp.post_tax_price')
								->get();
		
		//array to hold the details for the complex attributes
		$arrDetails = array();
		
		if($queryComplexProduct) {
			foreach ($queryComplexProduct as $row) {
				$arrDetails[] = array(
								'id' => $row->id,
								'name' => $row->name,
								'type' => $row->type,
								'variation_name' => $row->variation_name,
								'experince_includes' => $row->experience_includes,
								'menu' => $row->menu,
								'price' => $row->price,
								'price_type' => (empty($row->price_type)) ? '':$row->price_type,
								'taxes' => (empty($row->tax)) ? '':$row->tax
							);
			}
		}		
		return $arrDetails;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Returns the locations where product can be found.
	 * 
	 * @static	true
	 * @access	public
	 * @param	integer	$productID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getProductLocations($productID) {
		$queryResult = 		DB::table('product_vendor_locations as pvl')	
							->leftJoin(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','pvl.vendor_location_id')
							->leftJoin('locations', 'locations.id','=','vla.area_id')
							->select('locations.name as area','vla.latitude','vla.longitude')
							->where('pvl.product_id',$productID)
							->get();
		
		//array to hold location details
		$arrLocation = array();
		if($queryResult) {
			foreach($queryResult as $row) {
				$arrLocation[] = array(
									'area' => $row->area,
									'latitude' => $row->latitude,
									'longitude' => $row->longitude
								);
			}
		}		
		return $arrLocation;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * 
	 */
	public static function getSimilarProductListing() {
		$queryResult = DB::table('products')
						->leftJoin(DB::raw('product_attributes_text as pat1'),'pat1.product_id','=','products.id')
						->leftJoin(DB::raw('product_attributes_text as pat2'),'pat2.product_id','=','products.id')
						->leftJoin(DB::raw('product_attributes as pa1'), 'pa1.id','=','pat1.product_attribute_id')
						->leftJoin(DB::raw('product_attributes as pa2'), 'pa2.id','=','pat2.product_attribute_id')
						->leftJoin(DB::raw('product_pricing as pp'), 'pp.product_id','=','products.id')
						->where()
						->where();
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Returns the images associated with a project.
	 * 
	 * @static 	true
	 * @access	public
	 * @param	integer	$productID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getProductImages($productID) {
		//query to read media details
		$queryImages = $queryImages = DB::table('media_resized_new as mrn')
						->leftJoin('product_media_map as pmm','pmm.media_id','=','mrn.media_id')
						->where('pmm.product_id',$productID)
						->where('pmm.media_type','mobile')
						->select('mrn.file as image','mrn.image_type','pmm.product_id')
						->get();
		
		//echo $queryImages->toSql();
		
		//array to hold images
		$arrImage = array();
		
		if($queryImages) {
			foreach($queryImages as $row) {
				if(in_array($row->image_type, array('mobile_listing_android_experience','mobile_listing_ios_experience'))) {
					$arrImage[$row->image_type] = Config::get('constants.API_MOBILE_IMAGE_URL').$row->image;
				}
				if($row->image_type = 'gallery') {
					$arrImage['gallery'][] = Config::get('constants.API_MOBILE_IMAGE_URL').$row->image;
				}
			}
		}
		
		return $arrImage;
	} 
}
//end of class Experiences
//end of file Experiences.php