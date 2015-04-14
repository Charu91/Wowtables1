<?php namespace WowTables\Http\Models;

use DB;
use Config;
use WowTables\Http\Models\Eloquent\Tag;
use WowTables\Http\Models\Eloquent\Location; 


/**
 * Model class Search.
 * 
 * @since		1.0.0
 * @version		1.0.0
 * @author		Parth Shukla <shuklaparth@hotmail.com>
 */
class Search {
	
	/**
	 * Reads the details of the vendors matching the passed
	 * parameters.
	 * 
	 * @access	public
	 * @static	true
	 * @param	array $arrData	
	 * @return	array
	 * @since	1.0.0
	 */
	public static function find($arrData){
		$vendors = DB::table('vendors')
						->join('vendor_location','vendor_location.vendor_id','=','vendors.id')
						->join('locations','locations.id','=', 'vendor_location.location_id')
						->join('vendor_tag_map','vendor_tag_map.vendor_id','=','vendor.id')
						->join('tags','tags.id','=','vendor_tag_map.tag_id')
						->join(DB::raw('vendor_attributes_text AS vat'),'vat.vendor_id','=','vendors.id')
						->join(DB::raw('vendor_attributes_varchar AS vavr'),'vavr.vendor_id','=','vendors.id')
						->join(DB::raw('vendor_attributes AS va1'),'va1.id','=','vat.vendor_attribute_id')
						->join(DB::raw('vendor_attributes AS va2'),'va2.id','=','vavr.vendor_attribute_id')
						->join(DB::raw('vendor_location_booking_schedules as vlbs'), 'vlbs.vender_location_id','=','vendor_location.id')
						->join('schedules', 'schedules.id','=','vlbs.schedule_id')
						->join('time_slots','time_slots.id','=','schedules.time_slot_id')
						->whereIn('locations.name',$arrData['arrLocation'])
						->whereIn('tags.name',$arrData['arrTags'])
						->where('vat.alias','=','resturant_info')
						->where('vavr.alias','=','short_description')
						->where('schedules.day_short','=',strtolower($arrData['bookingDay']))
						->where('time_slots.time','=',$arrData['bookingTime'])
						->select(DB::raw('vendors.id, vavr.attribute_value as title, vat.attribute_value as description'))
						->get();
						
		//array to store the vendor ids
		$arrVendor = array();
		//array to store final result
		$arrData = array();
		
		if($vendors) {
			foreach($vendors as $row) {
				$arrVendor[] = $row->id;
			}
			#reading the vendor ratings detail
			$arrRatings = $this->findRatingByVendors($arrVendor);
			foreach($vendors as $row) {
				$arrData[] = array(
									'id' => $row->id,
									'title' => $row->title,
									'description' => substr(strip_tags($row->description),0,20),
									'averageRating' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['averageRating']:0,
									'totalRating' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['totalRating']:0,
								);
			}
		}	
	}
	
	//-----------------------------------------------------------------------------
	
	/**
	 * Reads the ratings of the vendors matching the vendors
	 * in the passed array.
	 * 
	 * @access	public
	 * @static	true
	 * @param	array 	$arrVendor
	 * @return	array
	 * @since	1.0.0
	 */
	private function findRatingByVendors($arrVendor){
		$queryResult = DB::table('vendors')
					->whereIN('vendor_id',$arrVendor)
					->groupBy('vendor_id')
					->select(DB::raw('AVG(rating) as avg_rating, COUNT(*) as total_ratings,vendor_id'))
					->get();
		
		//array to store the result
		$arrRating = array();
		
		//reading the results
		foreach($queryResult as $row) {
			$arrRating[$row->vendor_id] = array(
											'averageRating' => $row->avg_rating,
											'totalRating' => $total_ratings
											);
		}		
		return $arrRating;					
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Reads the detail of the experience matching passed criteria.
	 * 
	 * @access	public
	 * @param	$arrData
	 * @since	1.0.0
	 * @version	1.0.0
	 */
	public function findMatchingExperience( $arrData ) {
		$experienceQuery = DB::table('products')
							->join('product_attributes_varchar','product_attributes_varchar.product_id','=','products.id')
							->join('product_attributes_text','product_attributes_text.product_id','=','products.id')
							//->join(DB::raw('product_attributes_multiselect as pam'),'pam.product_id','=','products.id')
							//->join(DB::raw('product_attributes_select_options as paso'),'paso.id','=','pam.product_attributes_select_option_id')
							//->join(DB::raw('product_attributes as pa1'),'pa1.id','=','product_attributes_varchar.product_attribute_id')
							//->join(DB::raw('product_attributes as pa2'),'pa2.id','=','product_attributes_text.product_attribute_id')
							//->join(DB::raw('product_attributes as pa3'),'pa3.id','=','paso.product_attribute_id')							
							->leftJoin(DB::raw('product_media_map as pmm'), 'pmm.product_id','=','products.id')
							->leftJoin('media','media.id','=','pmm.media_id')
							->leftJoin(DB::raw('product_pricing as pp'),'pp.product_id','=','products.id')
							//->join(DB::raw('product_vendor_locations as pvl'),'pvl.product_id','=','products.id')
							//->join(DB::raw('vendor_locations as vl'),'vl.id','=','pvl.vendor_location_id')
							//->join('locations','locations.id','=','vl.location_id')
							->leftJoin(DB::raw('product_venue_address as pva'),'pva.product_id','=','products.id')
							->leftJoin(DB::raw('product_flag_map as pfm'),'pfm.product_id','=','products.id')
							->leftJoin('flags', 'flags.id', '=', 'pfm.flag_id')
							//->leftJoin(DB::raw('product_tag_map as ptm'),'ptm.product_id','=','products.id')
							//->leftJoin('tags','tags.id','=','ptm.tag_id')
							//->where('pvl.status','Active')
							//->where('pa1.alias','short_description')
							//->orWhere('pa2.alias','experience_info')
							//->orWhere('pa3.alias','cuisines')
							->where('pva.city_id',$arrData['city_id'])
							->where('products.visible',1)
							->whereIN('products.type',array('simple','complex'))							
							->groupBy('products.id')
							->select('products.id',DB::raw('product_attributes_varchar.attribute_value as title, product_attributes_text.attribute_value as description,
											pp.price, pp.price_type, pp.is_variable, pp.tax, pp.post_tax_price, media.file as image,
											products.type as product_type, flags.name as flag_name'));
							
		
		//adding filter for cuisines if cuisines are present
		if(isset($arrData['arrCuisine'])) {
			$experienceQuery->whereIn('paso.option',$arrData['arrCuisine']);
		}
		
		//adding filter for locations if locations are present
		if(isset($arrData['arrLocation'])) {
			$experienceQuery->whereIn('locations.name',$arrData['arrLocation']);
		}

		//adding filter for tags if tags are present
		if(isset($arrData['tags'])) {
			$experienceQuery->whereIn('tags.name',$arrData['arrTags']);
		}
		
		//adding filter for price if price has been selected
		if(isset($arrData['']) && isset($arrData)) {
			$experienceQuery->whereBetween('pp.price',array($arrData['minPrice'], $arrData['maxPrice']));
		}
		
		//executing the query
		$experienceResult = $experienceQuery->get();
		
		//array to store the product ids
		$arrProduct = array();
		//array to store final result
		$arrData = array();
		
		$arrData['resultCount'] = 0;
		$arrData['experiences'] = array();
		
		#query executed successfully
		if($experienceResult) {
			#initializing the total number of matching rows returned
			$arrData['resultCount'] = count($experienceResult);
			
			#initializing the array of products
			foreach($experienceResult as $row) {
				$arrProduct[] = $row->id;
			}
			#reading the product ratings detail
			$arrRatings = $this->findRatingByProduct($arrProduct);
			
			foreach($experienceResult as $row) {
				$arrData['data'][] = array(
												'id' => $row->id,
												'type' => $row->product_type,
												'name' => $row->title,
												'description' => $row->description,
												'price' => (is_null($row->post_tax_price))? $row->price:$row->post_tax_price,
												'taxes' => (is_null($row->post_tax_price))? 'exclusive':'inclusive',
												'price_type' => $row->price_type,
												'variable' => $row->is_variable,
												'image' => (is_null($row->image))? '':Config::get('constants.IMAGE_URL').$row->image,
												'rating' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['averageRating']:0,
												'total_reviews' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['totalRating']:0,
												"flag" => (is_null($row->flag_name)) ? "":$row->flag_name,
											);
			}
		}
		
		return $arrData;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Reads the ratings of the product matching the vendors
	 * in the passed array.
	 * 
	 * @access	public
	 * @static	true
	 * @param	array 	$arrProduct
	 * @return	array
	 * @since	1.0.0
	 */
	private function findRatingByProduct($arrProduct){
		$queryResult = DB::table('product_reviews')
					->whereIN('product_id',$arrProduct)
					->groupBy('product_id')
					->select(DB::raw('AVG(rating) as avg_rating, COUNT(*) as total_ratings,product_id'))
					->get();
		//array to store the result
		$arrRating = array();
		
		//reading the results
		foreach($queryResult as $row) {
			$arrRating[$row->product_id] = array(
											'averageRating' => $row->avg_rating,
											'totalRating' => $total_ratings
											);
		}		
		return $arrRating;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Initializes the value for filters to be used 
	 * in the experience search filter.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array 	$arrSubmittedData
	 * @since	1.0.0
	 */
	public static function getExperienceSearchFilter($arrSubmittedData) {
		//array to store filter information	
		$arrFilters['filters'] = array();
		
		#setting up the location filter information
		if(isset($arrSubmittedData['location'])) {
			$arrFilters['filters']['locations'] = Location::formatLocationFilters($arrSubmittedData['location']);
		}		
		
		#setting up the tag filter information
		if(isset($arrSubmittedData['tags'])) {
			$arrFilters['filters']['tags'] = Tag::formatTagFilters($arrSubmittedData['tags']);
		}
		
		
		#setting up the date filter information
		$arrFilters['filters']['date'] = array(
												"name" => 'Date',
												"type" => 'single'
											);
		
		#setting up the time filter information
		$arrFilters['filters']['time'] = array(
												"name" => 'Time',
												"type" => 'single'
											);
		
		#setting up the price filter
		$arrFilters['filters']['price_range'] = array(
													"name" => "Price Range",
													"type" => "single",
													"options" => array(
																	"min" => $arrSubmittedData['minPrice'],
																	"max" => $arrSubmittedData['maxPrice']
																)
												);
		return $arrFilters;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Method to validate the data submitted by
	 * the user.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array 	$arrData
	 * @return	array
	 * @since	1.0.0
	 */
	public static function validateExperienceSearchData($arrData) {
		//array to store response to be sent back to client
		$arrResponse = array();
		if(!array_key_exists('city_id', $arrData) || empty($arrData['city_id'])) {
			$arrResponse['status'] = Config::get('constants.API_ERROR');
			$arrResponse['msg'] = "City id is required.";
		}		
		else {
			$arrResponse['status'] = 100;
		}
		
		return $arrResponse;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * 
	 */
	public function getExperienceSearchFiltersTest($cityID) {
		#getting all the locations
		$queryLocations = DB::table('locations')
							->join(DB::raw('locations_tree as lt'),'lt.descendant','=','locations.id')
							->where('lt.ancestor',$cityID)
							->where('lt.length','>',1)
							->where('locations.visible',1)
							->select('locations.id','locations.name')
							->get();
		//array of filters
		$arrFilters = array();
		
		#setting up the location filter information
		if($queryLocations) {
			foreach($queryLocations as $row) {
				$arrFilters['filters']['locations'][] = array(
														"id" => $row->id,
														"name" => $row->name
													);
			}			
		}
		
		#getting all the available cuisines
		$queryCuisines = DB::table(DB::raw('product_attributes_select_options as paso'))
							->join(DB::raw('product_attributes as pa'), 'pa.id','=','paso.product_attribute_id')
							->leftJoin(DB::raw('product_attributes_singleselect as pass'),'pass.product_attributes_select_option_id','=','paso.id')
							->where('pa.alias','cuisines')
							->select('paso.id','paso.option')
							->get();
							
		#setting up the cuisines filter information
		if($queryCuisines) {
			foreach ($queryCuisines as $row) {
				$arrFilters['filters']['cuisines'][] = array(
																"id" => $row->id,
																"name" => $row->option
															);
			}
		}
		
		#getting all the tags from the database
		$queryTags = DB::table('tags')->select('id','name')->get();
		
		#setting up the tags filter information
		foreach ($queryTags as $row) {
			$arrFilters['filters']['tags'][] = array(
														"id" => $row->id,
														"name" => $row->name
													);
		}
							
		
		return $arrFilters;
		
	}
}
//end of class Search
//end of file WowTables\Http\Models\E