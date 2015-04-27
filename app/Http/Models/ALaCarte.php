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
						->join(DB::raw('vendor_location_attributes_text as vlat1'), 'vlat1.vendor_location_id', '=', 'vl.id')
						->join(DB::raw('vendor_location_attributes_text as vlat2'), 'vlat2.vendor_location_id', '=', 'vl.id')
						->join(DB::raw('vendor_location_attributes_text as vlat3'), 'vlat3.vendor_location_id', '=', 'vl.id')
						->join(DB::raw('vendor_location_attributes_text as vlat4'), 'vlat4.vendor_location_id', '=', 'vl.id')
						->join(DB::raw('vendor_attributes as va1'), 'va1.id', '=', 'vlat1.vendor_attribute_id')
						->join(DB::raw('vendor_attributes as va2'), 'va2.id', '=', 'vlat2.vendor_attribute_id')
						->join(DB::raw('vendor_attributes as va3'), 'va3.id', '=', 'vlat3.vendor_attribute_id')
						->leftJoin(DB::raw('vendor_attributes as va4'), 'va4.id', '=', 'vlat4.vendor_attribute_id')
						->leftjoin(DB::raw('vendor_curator_map as vcm'),'vcm.vendor_id','=','vendors.id')
						->leftjoin('curators', 'curators.id', '=', 'vcm.curator_id')
						->join(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','vl.id')
						->join(DB::raw('locations as loc1'),'loc1.id', '=' , 'vla.area_id')
						->join(DB::raw('locations as loc2'), 'loc2.id', '=', 'vla.city_id')
						->join(DB::raw('locations as loc3'), 'loc3.id', '=', 'vla.state_id')
						->join(DB::raw('locations as loc4'), 'loc4.id', '=', 'vla.country_id')
						->leftJoin(DB::raw('vendor_locations_media_map as vlmm'),'vlmm.vendor_location_id','=','vl.id')
						->leftJoin(DB::raw('media as m1'), 'm1.id', '=', 'vlmm.media_id')
						->leftJoin(DB::raw('media as m2'), 'm2.id', '=', 'curators.media_id')
						->leftJoin(DB::raw('vendor_location_attributes_integer as vlai'),'vlai.vendor_location_id','=','vl.id')
						->leftJoin(DB::raw('vendor_attributes as va5'),'va5.id','=','vlai.vendor_attribute_id')
						->leftJoin(DB::raw('vendor_location_attributes_text as vlat5'),'vlat5.vendor_location_id','=','vl.id')
						->leftJoin(DB::raw('vendor_attributes as va6'),'va6.id','=','vlat5.vendor_attribute_id')
						->where('vl.id',$aLaCarteID)
						->where('vl.a_la_carte','=',1)
						->where('va1.alias','restaurant_info')
						->where('va2.alias','short_description')
						->where('va3.alias','terms_and_conditions')
						->where('va4.alias','menu_picks')
						->where('vlmm.media_type','listing')
						->where('va5.alias','reward_points_per_reservation')
						->where('va6.alias','expert_tips')
						->groupBy('vl.id')
						->select(DB::raw('vl.id as vl_id'),'vl.vendor_id', 'vla.address','vla.pin_code', 'vla.latitude', 'vla.longitude',
												 DB::raw('vendors.name as title, vlat1.attribute_value as resturant_info, 
											  vlat2.attribute_value as short_description, vlat3.attribute_value as terms_conditions, vlat4.attribute_value as menu_picks,
											  m1.file as resturant_image, loc1.name as area, loc1.id as area_id, loc2.name as city, loc3.name as state_name,
											  loc4.name as country, curators.name as curator_name, curators.bio as curator_bio, 
											  m2.file as curator_image'),'vl.pricing_level','vlai.attribute_value as reward_point',
											  'vlat5.attribute_value as expert_tips')
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
						
			//formatting the array for the data
			$arrData['data'] = array(
									'type' => 'A-La-Carte Details',
									'id' => $queryResult->vl_id,
									'title' => $queryResult->title,
									'resturant_information' => $queryResult->resturant_info,
									'short_description' => $queryResult->short_description,
									'terms_and_condition' => $queryResult->terms_conditions,
									'pricing' => $queryResult->pricing_level,
									'image' => (!empty($queryResult->resturant_image)) ? Config::get('constants.IMAGE_URL').$queryResult->resturant_image:"",									
									'rating' => (array_key_exists($queryResult->vl_id, $arrReview)) ? $arrReview[$queryResult->vl_id]['averageRating']:0,
									'review_count' => (array_key_exists($queryResult->vl_id, $arrReview)) ? $arrReview[$queryResult->vl_id]['totalRating']:0,
									'cuisine' => (array_key_exists($queryResult->vl_id, $arrVendorCuisine)) ? $arrVendorCuisine[$queryResult->vl_id]:array(),
									'experience_available' => $experienceAvailable,
									'experience_url' => $experienceURL,
									'location_address' => array(
																"address_line" => $queryResult->address,
																"area" => $queryResult->area,
																"city" => $queryResult->city,
																"pincode" => $queryResult->pin_code,
																"state" => $queryResult->state_name,																
																"country" => $queryResult->country,
																"latitude" => $queryResult->latitude,
																"longitude" => $queryResult->longitude																
															),
									'curator_information' => array(
																'name' => (is_null($queryResult->curator_name)) ? "" : $queryResult->curator_name,
																'bio' => (is_null($queryResult->curator_bio)) ? "" : $queryResult->curator_bio,
																'image' => (!empty($queryResult->curator_image)) ? Config::get('constants.IMAGE_URL').$queryResult->curator_image:NULL,
															),
									'menu_pick' => (is_null($queryResult->menu_picks)) ? "" : $queryResult->menu_picks,
									'similar_option' => $arrSimilarVendor,
									'reward_point' => (is_null($queryResult->reward_point)) ? 0:$queryResult->reward_point,
									'expert_tips' => (is_null($queryResult->expert_tips)) ? "" : $queryResult->expert_tips									
								);
			
			//reading the review details
			$arrData['data']['review_detail'] = Review::getVendorLocationRatingDetails($queryResult->vendor_id);
			
			//reading the locations
			$arrData['data']['other_location'] = Locations::getVendorLocation($queryResult->vendor_id);
			
			//setting the value of status
			$arrData['status'] = Config::get('constants.API_SUCCESS');
		}
		else {
			$arrData['status'] = Config::get('constants.API_ERROR');
			$arrData['msg'] = 'No matching values found.'; 
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
									DB::raw('loc.name as location_name,vl.slug as vendor_slug','media.file as image'))
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
											'image' => (!empty($row->image)) ? Config::get('constants.IMAGE_URL').$row->image:NULL,
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
 }
//end of class LaCarte
//end of file WowTables\Http\Models\LaCarte.php