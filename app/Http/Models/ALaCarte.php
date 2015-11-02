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
 	
 	 	static $arrRules = array('vendorID' => 'exists:vendors,id');

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
		
		if(!is_numeric($aLaCarteID)) { 
			$query = DB::table('vendor_locations')
							->where('slug', $aLaCarteID)
							->select('id')
							->first();
			if($query){
				$aLaCarteID = $query->id;
			}
			else { 
				$arrData['status'] = Config::get('constants.API_SUCCESS');
				$arrData['no_result_msg'] = 'No matching values found.';
				$arrData['data'] = array();
				$arrData['total_count'] = 0;
	            return $arrDatas;
			}

		}

		//Checking the bookmark status of the product
		$data['access_token']=$_SERVER['HTTP_X_WOW_TOKEN'];		
		$userID = UserDevices::getUserDetailsByAccessToken($data['access_token']);		
		$bookmark = DB::table('user_bookmarks as ub')															
					  	->where('user_id', '=', $userID)
					   	->where('vendor_location_id','=',$aLaCarteID)
					   	->select('id','type')
						->first();

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
								DB::raw('MAX(IF(va.alias = "special_offer_title", vlat.attribute_value, "")) AS special_offer_title'),
								DB::raw('MAX(IF(va.alias = "special_offer_desc", vlat.attribute_value, "")) AS special_offer_desc'),
								'loc1.name as area', 'loc1.id as area_id', 'loc2.name as city', 'loc3.name as state_name',
								'loc4.name as country', 'loc5.name as locality', 'curators.name as curator_name', 'curators.bio as curator_bio',
								'curators.designation as designation','vl.pricing_level','vlai.attribute_value as reward_point', 
								'm2.file as curator_image','vl.location_id as vl_location_id','vlcm.curator_tips', 'vla.city_id','vl.slug')						
						->first();
						
		if($queryResult) {
			//reading the review ratings
			$arrReview = Review::findRatingByVendorLocation(array($queryResult->vl_id));
			
			//reading vendor-cuisine
			$arrVendorCuisine = Self::getVendorLocationCuisine(array($queryResult->vl_id));
			
			//reading the similar vendors
			$arrSimilarVendor =  Self::getSimilarALaCarte(array('location_id' => $queryResult->area_id, 
																'pricing_level' => $queryResult->pricing_level,
																'city_id' => $queryResult->city_id
															    ));
			$arrSimilarAlacarteFilters = $arrSimilarVendor; //print_r($filters); die("hmm");
			
			$arrResultAlacarte = Self::fetchListings($arrSimilarAlacarteFilters); //print_r($arrResult); die("hmm");
			
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
									'resturant_information' => (empty($queryResult->resturant_info)) ? "" : $queryResult->resturant_info,
									'short_description' => (empty($queryResult->short_description)) ? "" : $queryResult->short_description,
									'terms_and_condition' =>(empty($queryResult->terms_conditions)) ? "" : $queryResult->terms_conditions,
									'special_offer_title' => (empty($queryResult->special_offer_title)) ? "" : $queryResult->special_offer_title,
									'special_offer_desc' => (empty($queryResult->special_offer_desc)) ? "" : $queryResult->special_offer_desc,
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
																'curator_name' => (is_null($queryResult->curator_name)) ? "" : $queryResult->curator_name,
																'curator_bio' => (is_null($queryResult->curator_bio)) ? "" : $queryResult->curator_bio,
																'curator_image' => (is_null($queryResult->curator_image)) ? "" : Config::get('constants.API_MOBILE_IMAGE_URL'). $queryResult->curator_image,
																'curator_designation' => (is_null($queryResult->designation)) ? "" : $queryResult->designation,
																'suggestions' => (is_null($queryResult->curator_tips)) ? "":$queryResult->curator_tips,
															),
									'menu_pick' => (is_null($queryResult->menu_picks)) ? "" : $queryResult->menu_picks,
									'similar_option' => $arrSimilarAlacarteFilters, //$arrSimilarVendor,
									'similar_option' => $arrResultAlacarte, // Added on 4.6.15
									'reward_point' => (is_null($queryResult->reward_point)) ? 0:$queryResult->reward_point,
									'expert_tips' => (is_null($queryResult->expert_tips)) ? "" : $queryResult->expert_tips,
									'slug' => $queryResult->slug,
									'bookmark_status' => (is_null($bookmark)) ? 0 : 1,																	
								);
			
			//reading the review details
			$arrData['data']['review_detail'] = Review::getVendorLocationRatingDetails($queryResult->vl_id);
			
			//reading the locations
			$arrData['data']['other_location'] = self::getVendorLocation($queryResult->vendor_id, $queryResult->vl_location_id);
			
			//setting the value of status
			$arrData['status'] = Config::get('constants.API_SUCCESS');
		}
		else {
			$arrData['status'] = Config::get('constants.API_ERROR');
			$arrData['no_result_msg'] = 'No matching values found.';
			
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
		//print_r($arrData); die();		 
		$queryResult = DB::table('locations as l')
								->leftJoin('locations_tree as lt', 'l.id', '=', 'descendant')
								->where('lt.ancestor',$arrData['location_id'])
								->where('lt.length',1)
								->select('l.id as location_id','l.name', 'l.type')
								->get();
		$filters=array('area' => array());
		foreach ($queryResult as $row) {
					$filters['area'][] = $row->location_id;												
		}

		$filters['city_id'] = $arrData['city_id'];
		$filters['pricing_level'] = $arrData['pricing_level'];

		return $filters;			
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
							->join(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','vl.id')
							->join('locations as loc1','loc1.id', '=' , 'vla.area_id')
							->join('locations as loc2', 'loc2.id', '=', 'vla.city_id')
							->join('locations as loc3', 'loc3.id', '=', 'vla.state_id')
							->join('locations as loc4', 'loc4.id', '=', 'vla.country_id')
							->join('locations as loc5','loc5.id','=','vl.location_id')
							->where('vl.vendor_id','=',$vendorID)
							->where('vl.location_id','!=',$locationID)
							->select('loc.name','vl.slug',
									  'vla.latitude','vla.longitude', 'vla.address', 'vla.pin_code',
									  'loc1.name as area', 'loc1.id as area_id', 'loc2.name as city', 
									  'loc3.name as state_name','loc4.name as country', 'loc5.name as locality'
									  )
							->get();
		
		foreach( $queryResult as $vendorLocation) {			
			$arrLocation[] = array(
									'name' => $vendorLocation->name,
									'slug' => $vendorLocation->slug,									
									'location_address' => array(
																	'address_line' => $vendorLocation->address,
																	'locality' 		=> $vendorLocation->locality,
																	'area' 			=> $vendorLocation->area,
																	'city' 			=> $vendorLocation->city,
																	'pincode' 		=> $vendorLocation->pin_code,
																	'state' 		=> $vendorLocation->state_name,																
																	'country' 		=> $vendorLocation->country,
																	'latitude' 		=> $vendorLocation->latitude,
																	'longitude' 	=> $vendorLocation->longitude,																
																), 
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
						//->leftJoin('media_resized_new as mrn1', 'mrn1.media_id', '=', 'vlmm.media_id')
						//->leftJoin('media_resized_new as mrn2', 'mrn2.media_id', '=', 'vlmm.media_id')
						->leftJoin('media_resized_new as mrn1', function($join) {
												$join->on('mrn1.media_id', '=', 'vlmm.media_id')
													  ->where('mrn1.image_type', '=' , 'mobile_listing_ios_alacarte');
						})
						->leftJoin('media_resized_new as mrn2', function($join) {
												$join->on('mrn2.media_id', '=', 'vlmm.media_id')
													  ->where('mrn1.image_type', '=', 'mobile_listing_ios_alacarte');
						})            
						->where('vl.vendor_id',$vendorID)
						//->where('v.status','Publish')
						->where('vl.status','Active')
						->where('vl.a_la_carte','=', 1)
						//->where('mrn1.image_type','mobile_listing_ios_alacarte')
						//->where('mrn2.image_type', 'mobile_listing_android_alacarte')
						->select('v.name', 'vl.pricing_level', 'vl.id as vl_id',
								DB::raw('GROUP_CONCAT(DISTINCT vaso.option separator ", ") as cuisine'),
								DB::raw(('COUNT(DISTINCT vlr.id) AS total_reviews')),
                				DB::raw('If(count(DISTINCT vlr.id) = 0, 0, ROUND(AVG(vlr.rating), 2)) AS rating'),                				
								'loc.name as location_name',
								DB::raw('IFNULL(flags.name,"") AS flag_name'),
								'mrn1.file as ios_image',
								'mrn2.file as android_image'
								)
						->groupBy('vl.id');
						//->get();  
					
		//checking if city has been passed in 
		if(array_key_exists('HTTP_X_WOW_CITY', $_SERVER)) { 
			$queryResult = $queryResult->join('vendor_location_address as vla', 'vla.vendor_location_id', '=', 'vl.id')
									   ->where('vla.city_id','=', $_SERVER["HTTP_X_WOW_CITY"]);
		}
		
		//executing the query
		$queryResult = $queryResult->get();
		
		//array to store the information from the DB
		$data = array();
		$data['status']=Config::get('constants.API_SUCCESS');

		//reading the experiences
		$arrExperience = self::readRestaurantsExperiences($vendorID);

		if($queryResult) {
			foreach($queryResult as $row) {
				$data['data']['alacarte'][] = array(
												'vl_id' 		=> $row->vl_id,
												'name' 			=> $row->name,
												'cuisine' 		=> (empty($row->cuisine)) ? "" : $row->cuisine,
												'pricing_level' => (empty($row->pricing_level)) ? "" : $row->pricing_level,
												'total_reviews' => $row->total_reviews,
												'rating' 		=> $row->rating,
												'location' 		=> (empty($row->location_name)) ? "" : $row->location_name,
												'flag' 			=> (empty($row->flag_name)) ? "" : $row->flag_name,
												'image' => array(
																	'mobile_listing_ios_alacarte' => (empty($row->ios_image))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->ios_image,
																	'mobile_listing_android_alacarte' => (empty($row->android_image))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->android_image,
																 )
											);
			}
			if(array_key_exists('data', $data)) { 
				$data['alacarteCount'] = count($data['data']['alacarte']);
				$data['data']['experience'] = $arrExperience;
				$data['experienceCount']=count($arrExperience);
			} else {
				$data['data']['alacarte'] = array();
				$data['alacarteCount'] = 0;
				$data['data']['experience'] = $arrExperience;
				$data['experienceCount'] = count($arrExperience);
				$data['no_result_msg'] = 'No matching result found.';
			}
		}
		else {
				$data['data']['alacarte'] = array();
				$data['alacarteCount'] = 0;
				$data['data']['experience'] = $arrExperience;
				$data['experienceCount'] = count($arrExperience);
				$data['no_result_msg'] = 'No matching result found.';
		}
		
		
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
							->leftjoin('price_types as pt', 'pt.id','=','pp.price_type')
							->where('vl.vendor_id', $vendorID)							
							->where('p.status', 'Publish')
							->where('pvl.status','Active')
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
									'pp.post_tax_price','pp.price',
									'pp.taxes', 'pt.type_name as price_type'
									)
							->groupBy('p.id');
						//	->get();

		//checking if city has been passed in 
		if(array_key_exists('HTTP_X_WOW_CITY', $_SERVER)) { 
			$queryResult = $queryResult->join('vendor_location_address as vla', 'vla.vendor_location_id', '=', 'vl.id')
									   ->where('vla.city_id','=', $_SERVER["HTTP_X_WOW_CITY"]);
		}

		//executing the query
		$queryResult = $queryResult->get();
							
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
											'taxes' => $row->taxes,
											'price_type' => $row->price_type,
											'location' => $row->location_name,
											'flag' => (empty($row->flag_name)) ? "" : $row->flag_name ,
											'short_description' => $row->short_description ,
											'image' => array(
																'mobile_listing_android_experience' => (empty($row->android_image))? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->android_image,
																'mobile_listing_ios_experience' => (empty($row->ios_image)) ? "":Config::get('constants.API_MOBILE_IMAGE_URL').$row->ios_image,
															 )
										);
			}
		}		
		return $data;							
	}

	public static function fetchListings(array $filters, $items_per_page = 10, $sort_by = 'Latest', $pagenum = null ){
        if(!$pagenum) $pagenum = 1;

        $offset = ($pagenum - 1) * $items_per_page;

        $select = DB::table('vendor_locations AS vl')
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_types AS vt', 'vt.id', '=', 'v.vendor_type_id')
            ->join('vendor_location_address AS vladd', 'vl.id', '=', 'vladd.vendor_location_id')
            ->join('locations AS l', 'l.id', '=', 'vl.location_id')
            ->join('locations AS la', 'la.id', '=', 'vladd.area_id')
            ->join('locations AS lc', 'lc.id', '=', 'vladd.city_id')
            /*->join('vendor_locations_media_map AS vmm', function($join){
                $join->on('vmm.vendor_location_id', '=', 'vl.id')
                    ->on('vmm.order','=', DB::raw('0'))
                    ->on('vmm.media_type', '=', DB::raw('"gallery"'));
            })
            ->join('media_resized AS mr', function($join){
                $join->on('vmm.media_id', '=', 'mr.media_id')
                    ->on('mr.width','=', DB::raw('600'))
                    ->on('mr.height', '=', DB::raw('400'));
            })// Make the width and height dynamic
            ->join('media AS m', 'm.id', '=', 'mr.media_id') */
            ->join('vendor_location_attributes_varchar AS vlav', 'vl.id', '=', 'vlav.vendor_location_id')
            ->join('vendor_attributes AS va', 'va.id', '=', 'vlav.vendor_attribute_id')
            ->leftJoin('vendor_location_attributes_multiselect AS vlam', 'vl.id', '=', 'vlam.vendor_location_id')
            ->leftJoin('vendor_attributes AS vamso', function($join){
                $join->on('va.id', '=', 'vlav.vendor_attribute_id')
                    ->on('vamso.alias','=', DB::raw('"cuisines"'));
            })
            ->leftJoin('vendor_attributes_select_options AS vaso', 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')
            ->leftJoin('vendor_location_booking_schedules AS vlbs', 'vlbs.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_location_blocked_schedules AS vlbls', 'vlbls.vendor_location_id', '=', 'vl.id')
            ->leftJoin('schedules AS s', 'vlbs.schedule_id', '=', 's.id')
            ->leftJoin('time_slots AS ts', 's.time_slot_id', '=', 'ts.id')
            ->leftJoin('vendor_locations_tags_map AS vltm', 'vl.id', '=', 'vltm.vendor_location_id')
            ->leftJoin('vendor_location_reviews AS vlr', function($join){
                $join->on('vl.id', '=', 'vlr.vendor_location_id')
                    ->on('vlr.status','=', DB::raw('"Approved"'));
            })
			->leftJoin(DB::raw('vendor_locations_flags_map as vlfm'),'vlfm.vendor_location_id','=','vl.id')
			->leftJoin('flags','flags.id','=','vlfm.flag_id')
            ->select(
                DB::raw('SQL_CALC_FOUND_ROWS vl.id'),
                'v.name AS restaurant',
                'l.name AS locality',
                'la.name AS area',
                'la.id as area_id',
                'vl.pricing_level',
                //'mr.file AS image',
                //'m.alt AS image_alt',
                //'m.title AS image_title',
                'l.id as location_id',
                DB::raw('MAX(IF(va.alias = "short_description", vlav.attribute_value, null)) AS short_description'),
                DB::raw('MAX(vlbs.off_peak_schedule) AS off_peak_available'),
                DB::raw(('COUNT(DISTINCT vlr.id) AS total_reviews')),
                DB::raw('If(count(DISTINCT vlr.id) = 0, 0, ROUND(AVG(vlr.rating), 2)) AS rating'),
                DB::raw('IFNULL(flags.name,"") AS flag_name'),
                DB::raw('GROUP_CONCAT(DISTINCT vaso.option separator ", ") as cuisine')
            )
            ->where('vt.type', DB::raw('"Restaurants"'))
            ->where('v.status', DB::raw('"Publish"'))
            ->where('v.publish_time', '<', DB::raw('NOW()'))
            ->where('lc.id', $filters['city_id'])
            ->where('vl.a_la_carte', 1)
			->where('vl.status','Active')
            ->groupBy('vl.id');
           // ->skip($offset)->take($items_per_page);


        if(isset($filters['area'])){
        	$select->whereIn('l.id', $filters['area']);
            //$this->filters['areas']['active'] = $filters['area'];
        }

        if(isset($filters['pricing_level'])){
        	if(strtolower($filters['pricing_level']) == 'high') {
        		$select->whereIn('vl.pricing_level',array('Low','Medium','High'));
        	}
			elseif(strtolower($filters['pricing_level']) == "medium") {
				$select->whereIn('vl.pricing_level',array('Low','Medium'));
			}
			elseif(strtolower($filters['pricing_level']) == "low") {
				$select->whereIn('vl.pricing_level',array('Low'));
			}
            
            //$this->filters['pricing_level']['active'] = $filters['pricing_level'];
        }
               

       
		//echo $select->toSql();
        $listing = $select->take(5)->get();

        //return response()->json($listing,200);
        $result = array('data' => array());
        foreach ($listing as $row) {
        	$result['data'][] = array( 'id' => $row->id,
        								'restaurant' => $row->restaurant,
        								'locality' => $row->locality,
        								'area' => $row->area,
        								'area_id' => $row->area_id,
        								'pricing_level' => $row->pricing_level,
        								'location_id' => $row->location_id,
        								'short_description' => $row->short_description,
        								'off_peak_available' => $row->off_peak_available,
        								'total_reviews' => $row->total_reviews,
        								'rating' => $row->rating,
        								'flag' => $row->flag_name,
        								'cuisine' => $row->cuisine
        							  );
        }
        
        return $result['data']; 
    }
    
 }
//end of class AlaCarte
//end of file WowTables\Http\Models\LaCarte.php