<?php namespace WowTables\Http\Models;

use WowTables\Http\Models\Review;
use WowTables\Http\Models\Locations;
use DB;
use URL;
use Config;

/**
 * Model class LaCarte.
 * 
 * @version	1.0.0
 * @since	1.0.0
 * @author	Parth Shukla <shuklaparth@hotmail.com>
 */
 class ALaCarte {
 	
	/**
	 * Reads the details of the LaCarte matching the
	 * passed name.
	 * 
	 * @access	public
	 * @param	string	$laCarte
	 * @return	array
	 * @since	1.0.0
	 * @version	1.0.0
	 */
	public static function getALaCarteDetails( $aLaCarteID ) {
		//array to store the matching result
		$arrData = array();
		
		//query to read the details of the A-La-Carte
		$queryResult = DB::table(DB::raw('vendor_locations as vl'))
						->join('vendors','vendors.id','=','vl.vendor_id')
						->join(DB::raw('vendor_location_attributes_text as vlat'), 'vlat.vendor_location_id', '=', 'vl.id')
						->join(DB::raw('vendor_attributes as va'), 'va.id', '=', 'vlat.vendor_attribute_id')
						->leftjoin(DB::raw('vendor_locations_curator_map as vlcm'),'vlcm.vendor_location_id','=','vl.id')
						->leftjoin('curators', 'curators.id', '=', 'vlcm.curator_id')
						->join(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','vl.id')
						->join(DB::raw('locations as loc1'),'loc1.id', '=' , 'vla.area_id')
						->join(DB::raw('locations as loc2'), 'loc2.id', '=', 'vla.city_id')
						->join(DB::raw('locations as loc3'), 'loc3.id', '=', 'vla.state_id')
						->join(DB::raw('locations as loc4'), 'loc4.id', '=', 'vla.country_id')
						->join('locations as loc5','loc5.id','=','vl.location_id')
						->leftJoin(DB::raw('media_resized_new as m2'), 'm2.id', '=', 'curators.media_id')
						->leftJoin(DB::raw('vendor_location_attributes_integer as vlai'),'vlai.vendor_location_id','=','vl.id')
						->leftJoin(DB::raw('vendor_attributes as va2'),'va2.id','=','vlai.vendor_attribute_id')
						->where('vl.id',$aLaCarteID)
						->where('vl.a_la_carte','=',1)
						->where('vl.status','Active')
						->where('va2.alias','reward_points_per_reservation')						
						->groupBy('vl.id')
						->select( 'vl.id as vl_id','vl.vendor_id', 'vla.address','vla.pin_code',
								'vla.latitude', 'vla.longitude', 'vendors.name as title', 
								DB::raw('MAX(IF(va.alias = "restaurant_info", vlat.attribute_value, "")) AS resturant_info'),
								DB::raw('MAX(IF(va.alias = "short_description", vlat.attribute_value, "")) AS short_description'),
								DB::raw('MAX(IF(va.alias = "terms_and_conditions", vlat.attribute_value, "")) AS terms_conditions'),
								DB::raw('MAX(IF(va.alias = "menu_picks", vlat.attribute_value, "")) AS menu_picks'),
								DB::raw('MAX(IF(va.alias = "expert_tips", vlat.attribute_value, "")) AS expert_tips'),
								'loc1.name as area', 'loc1.id as area_id', 'loc2.name as city', 'loc3.name as state_name',
								'loc4.name as country', 'loc5.name as locality', 'curators.name as curator_name', 'curators.bio as curator_bio',
								'curators.designation as designation','vl.pricing_level','vlai.attribute_value as reward_point', 
								'm2.file as curator_image','vl.location_id as vl_location_id','vlcm.curator_tips')						
						->first();
						
		if($queryResult) {
			//reading the review ratings
			$arrReview = Review::findRatingByVendorLocation(array($queryResult->vl_id));
			
			//reading vendor-cuisine
			$arrVendorCuisine = Self::getVendorLocationCuisine(array($queryResult->vl_id));
			
			//reading the similar vendors
			$arrSimilarVendor =  Self::getSimilarALaCarte(array('location_id' => $queryResult->area_id, 'pricing_level' => $queryResult->pricing_level));
			
			//initializing the values for experience
			if(Self::isExperienceAvailable($queryResult->vl_id)) {
				$experienceAvailable = 'true';
				$experienceURL = URL::to('/').'/experience/'.$aLaCarteID;
			}
			else {
				$experienceAvailable = 'false';
				$experienceURL = '';
			}

			//getting the images
			$arrImage = self::getVendorImages($queryResult->vl_id);
						
			//formatting the array for the data
			$arrData['data'] = array(
									'type' => 'A-La-Carte Details',
									'id' => $queryResult->vl_id,
									'title' => $queryResult->title,
									'resturant_information' => $queryResult->resturant_info,
									'short_description' => $queryResult->short_description,
									'terms_and_condition' => $queryResult->terms_conditions,
									'pricing' => $queryResult->pricing_level,
									'image' => $arrImage,									
									'rating' => (array_key_exists($queryResult->vl_id, $arrReview)) ? $arrReview[$queryResult->vl_id]['averageRating']:0,
									'review_count' => (array_key_exists($queryResult->vl_id, $arrReview)) ? $arrReview[$queryResult->vl_id]['totalRating']:0,
									'cuisine' => (array_key_exists($queryResult->vl_id, $arrVendorCuisine)) ? $arrVendorCuisine[$queryResult->vl_id]:array(),
									'experience_available' => $experienceAvailable,
									'experience_url' => $experienceURL,
									'location_address' => array(
																"address_line" => $queryResult->address,
																"locality" => $queryResult->locality,
																"area" => $queryResult->area,
																"city" => $queryResult->city,
																"pincode" => $queryResult->pin_code,
																"state" => $queryResult->state_name,																
																"country" => $queryResult->country,
																"latitude" => $queryResult->latitude,
																"longitude" => $queryResult->longitude																
															),
									'curator_information' => array(
																'name' => "Deepa Jain",//(is_null($queryResult->curator_name)) ? "" : $queryResult->curator_name,
																'bio' => "",//(is_null($queryResult->curator_bio)) ? "" : $queryResult->curator_bio,
																'image' => 'https://s3-eu-west-1.amazonaws.com/wowtables/uploads/deepa_jain.jpg',//(is_null($queryResult->curator_image)) ? "" : Config::get('constants.API_MOBILE_IMAGE_URL'). $queryResult->curator_image,
																'designation' => "has reviewed this"//(is_null($queryResult->designation)) ? "" : $queryResult->designation
															),
									'menu_pick' => (is_null($queryResult->menu_picks)) ? "" : $queryResult->menu_picks,
									'similar_option' => $arrSimilarVendor,
									'reward_point' => (is_null($queryResult->reward_point)) ? 0:$queryResult->reward_point,
									'expert_tips' => (is_null($queryResult->expert_tips)) ? "" : $queryResult->expert_tips,
									'curator_tips' => (is_null($queryResult->curator_tips)) ? "":$queryResult->curator_tips,									
								);
			
			//reading the review details
			$arrData['data']['review_detail'] = Review::getVendorLocationRatingDetails($queryResult->vl_id);
			
			//reading the locations
			$arrData['data']['other_location'] = self::getVendorLocation($queryResult->vendor_id, $queryResult->vl_location_id);
			
			//setting the value of status
			$arrData['status'] = Config::get('constants.API_SUCCESS');
		}
		else {
			$arrData['status'] = Config::get('constants.API_SUCCESS');
			$arrData['no_result_msg'] = 'No matching values found.';
			$arrData['data'] = array();
			$arrData['total_count'] = 0;
		}
		return $arrData;			
	}

	//-----------------------------------------------------------------
	
	/**
	 * Returns the aLacarte details matching the passed
	 * parameters.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array $arrData
	 * @since	1.0.0
	 */
	public static function getSimilarALaCarte($arrData) {
		$strQuery = DB::table(DB::raw('vendor_locations as vl'))
					->join('vendors','vendors.id','=','vl.vendor_id')
					->join(DB::raw('locations as loc'), 'loc.id', '=', 'vl.location_id')
					->join(DB::raw('vendor_media_map as vmm'), 'vmm.vendor_id', '=', 'vendors.id')
					->leftJoin('media','media.id','=', 'vmm.media_id')
					->where('vl.a_la_carte','=',1)
					->where('vmm.media_type','=','listing')
					->where('loc.id','=', $arrData['location_id']);
		
		//adding filter info			
		if($arrData['pricing_level'] == "High") {
			$strQuery->where('vl.pricing_level', 'High')
					->where('vl.pricing_level', 'Medium')
					->where('vl.pricing_level', 'Low');
		}
		elseif($arrData['pricing_level'] == "Medium") {
			$strQuery->where('vl.pricing_level', 'Medium')
							->where('vl.pricing_level', 'Low');
		}
		else {
			$strQuery->where('vl.pricing_level', 'Low');
		}
		
		#executing the query
		$queryResult = $strQuery->select('vl.id', 'vendors.name', 'vl.pricing_level',
									DB::raw('loc.name as location_name,vl.slug as vendor_slug')
									//DB::raw('media.file as image')
									)
									->get();
									
		//array to hold the vendors information
		$arrVendorInformation = array();		
		//array to hold the vendor ids
		$arrVendorId = array();		
		//array to hold vendor reviews
		$arrReview = array();		
		//array to hold vendor cuisines
		$arrVendorCuisine = array();
		
		if($queryResult) {
			foreach($queryResult as $row) {
				$arrVendorId[] = $row->id;
			}
			
			//getting vendors review details
			$arrReview = Review::findRatingByVendorLocation($arrVendorId);
			
			//getting vendors available cuisine
			$arrVendorCuisine = $this->getVendorLocationCuisine($arrVendorId);
			
			foreach($queryResult as $row) {
				$arrVendorInformation = array(
											'id' => $row->id,
											'title' => $row->title,
											'rating' => (array_key_exists($row->vl_id, $arrReview)) ? $arrReview[$row->vl_id]['averageRating']:0,
											'review_count' => (array_key_exists($row->vl_id, $arrReview)) ? $arrReview[$row->vl_id]['totalRating']:0,
											'pricing_level' => $row->pricing_level,
											'location' => $row->location_name,
											'url' => URL::to('/').$row->vendor_location_slug,
											'image' => "",//(!empty($row->image)) ? Config::get('constants.IMAGE_URL').$row->image:NULL,
											'cuisine' => (array_key_exists($row->id, $arrVendorCuisine)) ? $arrVendorCuisine[$row->id]:array()
										);
			}
		}
		
		return $arrVendorInformation;	
	}

	//-----------------------------------------------------------------
	
	/**
	 * Returns the cuisine details of the vendors
	 * based on their location id.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array 	$arrVendor
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getVendorLocationCuisine($arrVendorLocation) {
		$queryResult = DB::table(DB::raw('vendor_locations as vl'))
							->join(DB::raw('vendor_location_attributes_multiselect as vlam'),'vlam.vendor_location_id','=','vl.id')
							->join(DB::raw('vendor_attributes_select_options as vaso'), 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')
							->join(DB::raw('vendor_attributes as va'),'va.id', '=', 'vaso.vendor_attribute_id')
							->whereIn('vl.id', $arrVendorLocation)
							->where('va.name','cuisines')
							->orderBy('vl.id','desc')
							->select('vl.id','vaso.option')
							->get();
		
		//array to store vendor cuisine details
		$arrVendorCuisine = array();
		
		if($queryResult) {
			foreach($queryResult as $row) {
				if(!array_key_exists($row->id, $arrVendorCuisine)) {
					$arrVendorCuisine[$row->id] = array();
				}
				$arrVendorCuisine[$row->id][] = $row->option;
			}
		}
		
		
		return $arrVendorCuisine;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Checks if any experience is availabe at the passed
	 * vendor location id.
	 * 
	 * @access	public
	 * @param	integer	$locationId
	 * @return	boolean
	 * @since	1.0.0
	 */
	public static function isExperienceAvailable($locationId) {
		$queryResult = DB::table('product_vendor_locations')
							->where('vendor_location_id',$locationId)
							->where('status','active')
							->first();
		if($queryResult) {
			return TRUE;
		}
		
		return FALSE;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Returns the images for the passed vendor location id.
	 * 
	 * @static	true
	 * @access	public
	 * @since	1.0.0
	 * @version	1.0.0
	 */
	public static function getVendorImages($vendorLocationID) {
		//query to read media details
		$queryImages = DB::table('media_resized_new as mrn')
						->leftJoin('vendor_locations_media_map as vlmm','vlmm.media_id','=','mrn.media_id')
						->where('vlmm.vendor_location_id',$vendorLocationID)
						->where('vlmm.media_type','mobile')
						->select('mrn.id','mrn.file as image','mrn.image_type')
						->groupBy('mrn.id')
						->get();
		
		//array to hold images
		$arrImage = array();
		
		if($queryImages) {
			foreach($queryImages as $row) {
				if(in_array($row->image_type, array('mobile_listing_android_alacarte','mobile_listing_ios_alacarte'))) {
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
	 * Returns all the location of a Vendor matching
	 * the passed id.
	 * 
	 * @static	true
	 * @access	public
	 * @param	integer	$vendorID
	 * @return	array
	 * @since	v1.0.0
	 */
	public static function getVendorLocation($vendorID,$locationID=0) {
		//array to contain the list of locations
		$arrLocation = array();
		
		$queryResult = DB::table('vendor_locations as vl')
							->leftJoin('locations as loc', 'loc.id','=','vl.location_id')
							->where('vl.vendor_id','=',$vendorID)
							->where('vl.location_id','!=',$locationID)
							->select('loc.name','vl.slug')
							->get();
		
		foreach( $queryResult as $vendorLocation) {				
			$arrLocation[] = array(
									'name' => $vendorLocation->name,
									'slug' => $vendorLocation->slug 
								);
		}
		
		return $arrLocation;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Reads the details of the branches of the restaurants matching the
	 * passed vendorID.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer	$vendorID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getResturantBranchesInformation($vendorID) {
		
		//query to read the vendor details
		$queryResult = DB::table('vendors as v')
						->join('vendor_locations as vl', 'vl.vendor_id', '=', 'v.id')
						->leftJoin('vendor_location_attributes_varchar AS vlav', 'vl.id', '=', 'vlav.vendor_location_id')
						->leftJoin('vendor_attributes AS va', 'va.id', '=', 'vlav.vendor_attribute_id')
            			->leftJoin('vendor_location_attributes_multiselect AS vlam', 'vlam.vendor_location_id', '=', 'vl.id')
            			->leftJoin('vendor_attributes AS vamso', function($join){
               	 				$join->on('va.id', '=', 'vlav.vendor_attribute_id')
                    			->on('vamso.alias','=', DB::raw('"cuisines"'));
            				})
						->leftJoin('vendor_attributes_select_options AS vaso', 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')
						->leftJoin('vendor_location_reviews AS vlr', function($join){
                				$join->on('vlr.vendor_location_id', '=', 'vl.id')
                    				->on('vlr.status','=', DB::raw('"Approved"'));
            			})
						->leftJoin('locations as loc','loc.id','=','vl.location_id')
						->leftJoin('vendor_locations_flags_map as vlfm','vlfm.vendor_location_id','=','vl.id')
						->leftJoin('flags','flags.id','=','vlfm.flag_id')
						->leftJoin('vendor_locations_media_map as vlmm', 'vlmm.vendor_location_id','=', 'vl.id')
						->leftJoin('media_resized_new as mrn1', 'mrn1.media_id', '=', 'vlmm.media_id')
						->leftJoin('media_resized_new as mrn2', 'mrn2.media_id', '=', 'vlmm.media_id')            
						->where('vl.vendor_id',$vendorID)
						->where('v.status','Publish')
						->where('vl.status','Active')
						->where('mrn1.image_type','mobile_listing_ios_alacarte')
						->where('mrn2.image_type', 'mobile_listing_android_alacarte')
						->select('v.name', 'vl.pricing_level', 'vl.id as vl_id',
								DB::raw('GROUP_CONCAT(DISTINCT vaso.option separator ", ") as cuisine'),
								DB::raw(('COUNT(DISTINCT vlr.id) AS total_reviews')),
                				DB::raw('If(count(DISTINCT vlr.id) = 0, 0, ROUND(AVG(vlr.rating), 2)) AS rating'),
								'loc.name as location_name',
								DB::raw('IFNULL(flags.name,"") AS flag_name'),
								'mrn1.file as ios_image',
								'mrn2.file as android_image'
								)
						->groupBy('vl.id')
						->get();
		
		//array to store the information from the DB
		$data = array();
		if($queryResult) {
			foreach($queryResult as $row) {
				$data['data']['branch'][] = array(
												'vl_id' => $row->vl_id,
												'name' => $row->name,
												'cuisine' => $row->cuisine,
												'pricing_level' => $row->pricing_level,
												'total_reviews' => $row->total_reviews,
												'rating' => $row->rating,
												'location' => $row->location_name,
												'flag' => $row->flag_name,
												'images' => array(
																	'mobile_listing_ios_alacarte' => (empty($row->ios_image))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->ios_image,
																	'mobile_listing_android_alacarte' => (empty($row->android_image))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->android_image,
																 )
											);
			}
		}
		
		$data['data']['experience'] = self::readRestaurantsExperiences($vendorID);
		
		return $data;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Reads all the experiences avalilable at a particular
	 * restaurant.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer	$vendorID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function readRestaurantsExperiences($vendorID) {
		
		//query to read experiences available at the Restaurant
		$queryResult = DB::table('products as p')
							->join('product_vendor_locations as pvl','pvl.product_id','=','p.id')
							->join('vendor_locations as vl', 'vl.id','=', 'pvl.vendor_location_id')
							->leftJoin('product_attributes_text as pat','pat.product_id','=','p.id')
							->leftJoin('product_attributes as pa', 'pa.id', '=', 'pat.product_attribute_id')
							->leftJoin('product_pricing as pp', 'pp.product_id', '=', 'p.id')
							->leftJoin('product_reviews AS pr', function($join){
                														$join->on('p.id', '=', 'pr.product_id')
                    													->on('pr.status','=', DB::raw('"Approved"'));
                    												})
							->leftJoin('locations as loc','loc.id','=','vl.location_id')
							->leftJoin('product_flag_map as pfm','pfm.product_id', '=', 'p.id')
							->leftJoin('flags','flags.id', '=', 'pfm.flag_id')
							->leftJoin('product_media_map as pmm','pmm.product_id', '=', 'p.id')
							->leftJoin('media_resized_new as mrn1', 'mrn1.media_id', '=', 'pmm.media_id')
							->leftJoin('media_resized_new as mrn2', 'mrn2.media_id', '=', 'pmm.media_id')
							->where('vl.vendor_id', $vendorID)							
							->where('p.status', 'Publish')
							->where('mrn1.image_type','mobile_listing_ios_experience')
							->where('mrn2.image_type', 'mobile_listing_android_experience')						
							->select(
									'p.id as product_id','p.name', 'pvl.id as pvl_id',
									DB::raw(('COUNT(DISTINCT pr.id) AS total_reviews')),
									DB::raw('MAX(IF(pa.alias = "short_description", pat.attribute_value, "")) AS short_description'),
									DB::raw('If(count(DISTINCT pr.id) = 0, 0, ROUND(AVG(pr.rating), 2)) AS rating'),
									DB::raw('GROUP_CONCAT(DISTINCT loc.name separator ", ") as location_name'),
									'mrn1.file as ios_image','mrn2.file as android_image',
									'flags.name as flag_name',
									'pp.post_tax_price','pp.price'
									)
							->groupBy('p.id')
							->get();							
							
		//array to store the information from the DB
		$data = array();
		if($queryResult) {
			foreach($queryResult as $row) {
				$data[] = array(
											'prod_id' => $row->product_id,
											'pvl_id' => $row->pvl_id,
											'name' => $row->name,
											'total_reviews' => $row->total_reviews,
											'rating' => $row->rating,
											'price' => $row->price,
											'post_tax_price' => $row->post_tax_price,
											'location' => $row->location_name,
											'flag' => (empty($row->flag_name)) ? "" : $row->flag_name ,
											'short_description' => $row->short_description ,
											'images' => array(
																'mobile_listing_android_experience' => (empty($row->android_image))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->android_image,
																'mobile_listing_ios_experience' => (empty($row->ios_image)) ? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->ios_image,
															 )
										);
			}
		}
		return $data;							
	}
 }
//end of class LaCarte
//end of file WowTables\Http\Models\LaCarte.php