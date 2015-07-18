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

        //Validation rule
        static $arrRules = array(
            'id' => 'required|exists:products,id'
        );

         static $arrRulesSlug = array(
            'id' => 'required|exists:products,slug'
        );
        //-------------------------------------------------------------
	
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
		
		if(!is_numeric($experienceID)) { 
			$query = DB::table('products')
							->where('slug', $experienceID)
							->select('id')
							->first();
			if($query){
				$experienceID = $query->id;
			}
			else { 
				$arrExpDetails['status'] = Config::get('constants.API_SUCCESS');
	            $arrExpDetails['no_result_msg'] = 'No matching values found.';
	            $arrExpDetails['data'] = array();
	            $arrExpDetails['total_count'] = 0; 
	            return $arrExpDetails;
			}

		}		

		//query to read product type
		$queryType = DB::table('products')
						->where('id', $experienceID)
						->select('name','type')
						->first();

        //print_r($queryType); die();

       	//query to read the experience detail
		$queryExperience = DB::table('products')
							->leftJoin('product_attributes_text as pat','pat.product_id','=','products.id')
							->leftJoin('product_attributes as pa', 'pa.id','=','pat.product_attribute_id')
							->leftJoin('product_pricing as pp', 'pp.product_id','=','products.id')
							->leftJoin('price_types as pt','pt.id','=','pp.price_type')
							->leftJoin('product_vendor_locations as pvl','pvl.product_id','=','products.id')
							->leftJoin('vendor_locations as vl','vl.id','=','pvl.vendor_location_id')
							->leftJoin('product_media_map as pmm', function($join){
								$join->on('pmm.product_id','=','products.id')
									->where('pmm.media_type','=','main');
							})
							->leftJoin('product_curator_map as pcm','pcm.product_id','=','products.id')
							->leftJoin('curators','curators.id','=','pcm.curator_id')
							->leftJoin('media','media.id','=','pmm.media_id')
							->leftJoin('media_resized_new as cm','cm.id','=','curators.media_id')
							->join('vendors','vendors.id','=','vl.vendor_id')
							->leftJoin('product_attributes_integer as pai', 'pai.product_id','=','products.id')
							->leftJoin('product_attributes as pa2','pa2.id','=','pai.product_attribute_id')
							->where('products.id',$experienceID)
							->where('pvl.status','Active');							
		
		//adding additional parameters in case of simple experience
		if($queryType && $queryType->type == 'simple') {			
			$queryExperience->leftJoin(DB::raw('product_attributes_text as pat3'),'pat3.product_id','=','products.id')
							->leftJoin(DB::raw('product_attributes as pa3'), 'pa3.id','=','pat3.product_attribute_id')
							->where('pa3.alias','menu')
							->select('products.id','products.name','products.type','pp.price','pp.tax',
                                    'pcm.curator_tips as tips', 'pp.taxes',
									'pt.type_name as price_type', 'pp.is_variable','pp.post_tax_price', 
									DB::raw('MAX(IF(pa.alias = "experience_info", pat.attribute_value, "")) AS experience_info'),
									DB::raw('MAX(IF(pa.alias = "short_description", pat.attribute_value, "")) AS short_description'),
									DB::raw('MAX(IF(pa.alias = "terms_and_conditions", pat.attribute_value, "")) AS terms_and_conditions'),
									DB::raw('MAX(IF(pa.alias = "menu", pat.attribute_value, "")) AS menu'),
									DB::raw('MAX(IF(pa.alias = "experience_includes", pat.attribute_value, "")) AS experience_includes'),
									DB::raw('MAX(IF(pa2.alias = "reward_points_per_reservation", pai.attribute_value,"")) as reward_points'),
									'media.file as experience_image', 'curators.name as curator_name', 
									'curators.bio as curator_bio', 'curators.designation',
									'cm.file as curator_image','pvl.id as product_vendor_location_id',
									'vendors.name as vendor_name');
							
		}
		else {
				$queryExperience->leftJoin(DB::raw('product_attributes_text as pat3'),'pat3.product_id','=','products.id')
							->leftJoin(DB::raw('product_attributes as pa3'), 'pa3.id','=','pat3.product_attribute_id')
							->where('pa3.alias','menu')
							->select('products.id','products.name','products.type','pp.price','pp.tax',
									'pt.type_name as price_type', 'pp.is_variable','pp.post_tax_price', 
									'pat1.attribute_value as experience_info','curators.name as curator_name', 
									'curators.bio as curator_bio','curators.designation','pat2.attribute_value as short_description', 
									'media.file as experience_image','cm.file as curator_image', 
									'pat4.attribute_value as terms_and_condition', 'pvl.id as product_vendor_location_id',
                                    'pcm.curator_tips as tips',
									'pat5.attribute_value as experience_includes','vendors.name as vendor_name', 'reward_points');
		}

		//running the query to get the results
		//echo $queryExperience->toSql();
		$expResult = $queryExperience->first();		
		
		//array to store the experience details
		$arrExpDetails = array();
		
		if($expResult) {
			
			//reading all the reviews for the particular experience
			$arrReviews = Review::readProductReviews($expResult->id);
			
			//reading other locations where same product is found
			$arrLocation = Self::getProductLocations($expResult->id, $expResult->product_vendor_location_id);
			
			//reading all the images of the product			 
			$arrImage = Self::getProductImages($experienceID);
			
			//reading all the addons associated with the product
			$arrAddOn = self::readExperienceAddOns($expResult->id);
			
						
			$arrExpDetails['data'] = array(
										'id' => $expResult->id,
										'name' => $expResult->name,
										'vendor_name' => $expResult->vendor_name,
										'experience_info' => $expResult->experience_info,
										'experience_includes' => $expResult->experience_includes,
										'short_description' => $expResult->short_description,
										'terms_and_condition' => $expResult->terms_and_conditions,
										'image' => $arrImage,
										'type' => $expResult->type,
										'reward_points' => $expResult->reward_points,
										'price' => $expResult->price ,
										'taxes' => $expResult->taxes,
										'pre_tax_price' => (is_null($expResult->price))? "" : $expResult->price,
										'post_tax_price' => (is_null($expResult->post_tax_price)) ? "" : $expResult->post_tax_price,
										'tax' => (is_null($expResult->tax)) ? "": $expResult->tax,
										'price_type' => (is_null($expResult->price_type)) ? "": $expResult->price_type,
                                        'curator_information' => array(
                                                                        'curator_name' => (is_null($expResult->curator_name)) ? "":$expResult->curator_name,
                                                                        'curator_bio' => (is_null($expResult->curator_bio)) ? "":$expResult->curator_bio,
                                                                        'curator_image' => (is_null($expResult->curator_image)) ? "" : Config::get('constants.API_MOBILE_IMAGE_URL').$expResult->curator_image,
                                                                        'curator_designation' => (is_null($expResult->designation)) ? "":$expResult->designation,
                                                                        'suggestions' => (is_null($expResult->tips)) ? "": $expResult->tips,
                                                                      ),
										'menu' => $expResult->menu,
										'rating' => (is_null($arrReviews['avg_rating'])) ? 0 : $arrReviews['avg_rating'],
										'total_reviews' => $arrReviews['total_rating'],
										'review_detail' => $arrReviews['reviews'],
										'location' => $arrLocation,
										'similar_option' => array(),
										'addons' => $arrAddOn,
									);

            $arrExpDetails['status'] = Config::get('constants.API_SUCCESS');
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
	public static function getProductLocations($productID, $pvlID) {
		if(array_key_exists('HTTP_X_WOW_CITY', $_SERVER)) {
			$cityID = $_SERVER['HTTP_X_WOW_CITY'];
		} 
		else {
			$cityID = 0;
		}
		
		$queryResult = 	DB::table('product_vendor_locations as pvl')	
							//->leftJoin(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','pvl.vendor_location_id')
							->leftJoin('vendor_locations as vl', 'vl.id','=','pvl.vendor_location_id')
							->leftJoin('vendor_location_address as vla', 'vla.vendor_location_id', '=', 'vl.id')
							->leftJoin('locations', 'locations.id','=','vl.location_id')							
							->select('locations.name as area','vla.latitude','vla.longitude')
							->where('pvl.product_id',$productID)
							//->where('vla.city_id', $cityID) Added on 2.7.15
							//->where('pvl.id','!=',$pvlID)
							->where('pvl.status','Active');
							//->get();
		if($cityID != 0) {
			$queryResult = $queryResult->where('vla.city_id', $cityID)->get();
		}
		else {
			$queryResult = $queryResult->get();
		}
		
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
//		$queryResult = DB::table('products')
//						->leftJoin(DB::raw('product_attributes_text as pat1'),'pat1.product_id','=','products.id')
//						->leftJoin(DB::raw('product_attributes_text as pat2'),'pat2.product_id','=','products.id')
//						->leftJoin(DB::raw('product_attributes as pa1'), 'pa1.id','=','pat1.product_attribute_id')
//						->leftJoin(DB::raw('product_attributes as pa2'), 'pa2.id','=','pat2.product_attribute_id')
//						->leftJoin(DB::raw('product_pricing as pp'), 'pp.product_id','=','products.id')
//						->where()
//						->where();
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
	
	//-----------------------------------------------------------------
	
	/**
	 * Reads the addons details from the database associated
	 * with a particular experience.
	 * 
	 * @access	public
	 * @param	integer 	$experienceID
	 * @since	1.0.0
	 */
	public static function readExperienceAddOns($experienceID) {
		//query to read addons details for a experience 
		$queryResult = DB::table('products as p')
						->leftJoin('product_attributes_text as pat1','pat1.product_id','=','p.id')
						->leftJoin('product_attributes_text as pat2','pat2.product_id','=','p.id')
						->leftJoin('product_attributes_text as pat3','pat3.product_id','=','p.id')
						->leftJoin('product_attributes as pa1','pa1.id','=','pat1.product_attribute_id')
						->leftJoin('product_attributes as pa2','pa2.id','=','pat2.product_attribute_id')
						->leftJoin('product_attributes as pa3', 'pa3.id','=','pat3.product_attribute_id')
						->leftJoin('product_pricing as pp', 'pp.product_id','=','p.id')
						->leftJoin('price_types as pt','pt.id','=','pp.price_type')
						->where('p.product_parent_id',$experienceID)
						->where('p.type','addon')
						->where('p.status','Publish')
						->where('pa1.alias','short_description')
						->where('pa2.alias','menu')
						->where('pa3.alias','reservation_title')
						->groupBy('p.id')
						->select(
								'p.id as product_id','p.name as product_name',
								'pat1.attribute_value as short_description',
								'pat2.attribute_value as menu', 
								'pat3.attribute_value as reservation_title',
								//DB::raw('MAX(IF(pa.alias = "short_description", pat.attribute_value, "")) AS short_description'),
								//DB::raw('MAX(IF(pa.alias = "menu", pat.attribute_value, "")) AS menu'),
								'pp.price','pp.tax','pp.post_tax_price','pp.is_variable','pp.taxes',
								'pt.type_name'
							)
						->get();
						
		//array to hold formatted result
		$arrData = array();
		if($queryResult) {
			foreach($queryResult as $row) {
				$arrData[] = array(
								'prod_id' => $row->product_id,
								'title' => $row->product_name,
								'short_description' => $row->short_description,
								'menu' => $row->menu,
								'price' => (is_null($row->price)) ? "" : $row->price,
								'tax' => (is_null($row->tax)) ? "" : $row->tax,
								'post_tax_price' => (is_null($row->post_tax_price)) ? "" : $row->post_tax_price,
								'is_variable' => (is_null($row->is_variable)) ? "" : $row->variable,
								'taxes' => (is_null($row->taxes)) ? "" : $row->taxes,
								'price_type' => (is_null($row->type_name)) ? "" : $row->type_name,
								'reservation_title' => (is_null($row->reservation_title)) ? "" : $row->reservation_title,
							);
			}
		}
		
		return $arrData;
	}
}
//end of class Experiences
//end of file Experiences.php