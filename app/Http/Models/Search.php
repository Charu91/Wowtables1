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
		 * Minimum Price for experiences in the given city.
		 * 
		 * @var		float
		 * @access	protected
		 * @since	1.0.0
		 */
		protected $minPrice;
		
		/**
		 * Maximum price for experiences in the given city.
		 * 
		 * @var		float
		 * @access	protected
		 * @since	1.0.0
		 */
		protected $maxPrice;
		
		/**
		 * Filters to be sent for search results.
		 * 
		 * @var		array
		 * @access	protected
		 * @since	1.0.0
		 */
		protected $filters = array(
									'locations' => array(),
									'cuisines' => array(),
									'tags' => array(),
									'price_range' => array(),
								);
		
		//-------------------------------------------------------------
		
		/**
		 * Getter method for filters.
		 * 
		 * @access	public
		 * @return	array
		 * @since	1.0.0
		 */
		public function getExperienceSearchFilters() {
			return $this->filters;
		}
		
		//-------------------------------------------------------------

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
		public static function find( $arrData ){

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
								->leftJoin('product_media_map as pmm', 'pmm.product_id','=','products.id')
								->leftJoin('media','media.id','=','pmm.media_id')
								->leftJoin('product_pricing as pp','pp.product_id','=','products.id')
								->leftJoin('price_types as pt', 'pt.id','=','pp.price_type')
								->join(DB::raw('product_vendor_locations as pvl'),'pvl.product_id','=','products.id')
								->leftJoin(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','pvl.vendor_location_id')
								->leftJoin(DB::raw('product_flag_map as pfm'),'pfm.product_id','=','products.id')
								->leftJoin('flags', 'flags.id', '=', 'pfm.flag_id')
								->leftJoin('vendor_locations as vl','vl.id','=','pvl.vendor_location_id')
								->leftJoin('locations','locations.id','=','vl.location_id')
								//->leftJoin(DB::raw('product_tag_map as ptm'),'ptm.product_id','=','products.id')
								//->leftJoin('tags','tags.id','=','ptm.tag_id')
								->where('pvl.status','Active')
								//->where('pa1.alias','short_description')
								//->orWhere('pa2.alias','experience_info')
								//->orWhere('pa3.alias','cuisines')
								->where('vla.city_id',$arrData['city_id'])
								->where('products.visible',1)
								->whereIN('products.type',array('simple','complex'))
								->groupBy('products.id')
								->select('products.id',DB::raw('product_attributes_varchar.attribute_value as title, product_attributes_text.attribute_value as description,
												pp.price, pt.type_name as price_type, pp.is_variable, pp.tax, pp.post_tax_price, media.file as image,
												products.type as product_type, flags.name as flag_name, locations.id as location_id,
												locations.name as location_name'));


			//adding filter for cuisines if cuisines are present
			if(isset($arrData['cuisine'])) {
				$experienceQuery->join(DB::raw('product_attributes_multiselect as pam'),'pam.product_id','=','products.id')
						->join(DB::raw('product_attributes_select_options as paso'),'paso.id','=','pam.product_attributes_select_option_id')
						->whereIn('paso.option',$arrData['cuisine']);
			}

			//adding filter for locations if locations are present
			if(isset($arrData['location'])) {
				$experienceQuery->//join(DB::raw('product_vendor_locations as pvl'),'pvl.product_id','=','products.id')
								//->join(DB::raw('vendor_locations as vl'),'vl.id','=','pvl.vendor_location_id')
								//->join('locations','locations.id','=','vl.location_id')
								whereIn('locations.name',$arrData['location']);
								//->where('pvl.status','Active');
			}

			//adding filter for tags if tags are present
			if(isset($arrData['tag'])) {
				$experienceQuery->leftJoin(DB::raw('product_tag_map as ptm'),'ptm.product_id','=','products.id')
					->leftJoin('tags','tags.id','=','ptm.tag_id')
					->whereIn('tags.name',$arrData['tag']);
			}

			//adding filter for price if price has been selected
			if(isset($arrData['minPrice']) && isset($arrData['maxPrice'])) {
				$experienceQuery->whereBetween('pp.price',array($arrData['minPrice'], $arrData['maxPrice']));
			}

			//executing the query
			$experienceResult = $experienceQuery->get();

			//array to store the product ids
			$arrProduct = array();
			//array to store final result
			$arrData = array();

		

			#query executed successfully
			if($experienceResult) {
					$arrData['resultCount'] = 0;
					$arrData['experiences'] = array();
				#initializing the total number of matching rows returned
				$arrData['resultCount'] = count($experienceResult);

				#initializing the array of products
				foreach($experienceResult as $row) {
					$arrProduct[] = $row->id;
				}
				#reading the product ratings detail
				$arrRatings = $this->findRatingByProduct($arrProduct);
				
				$arrImage = $this->getExperienceImages($arrProduct);
				
				//array to store location IDs
				$arrLocationId = array();
				
				foreach($experienceResult as $row) {
					$this->minPrice = ($this->minPrice > $row->price || $this->minPrice == 0) ? $row->price : $this->minPrice;
					$this->maxPrice = ($this->maxPrice < $row->price || $this->maxPrice == 0) ? $row->price : $this->maxPrice;
					$arrData['data'][] = array(
													'id' => $row->id,
													'type' => $row->product_type,
													'name' => $row->title,
													'description' => $row->description,
													'price' => (is_null($row->post_tax_price))? $row->price:$row->post_tax_price,
													'taxes' => (is_null($row->post_tax_price))? 'exclusive':'inclusive',
													'pre_tax_price' => (is_null($row->price)) ? "" : $row->price,
													'post_tax_price' => (is_null($row->post_tax_price)) ? "" : $row->post_tax_price,
													'tax' => (is_null($row->tax)) ? "": $row->tax,
													'price_type' => (is_null($row->price_type)) ? "" : $row->price_type,
													'variable' => (is_null($row->is_variable)) ? "" : $row->is_variable,
													'image' => (array_key_exists($row->id, $arrImage))? $arrImage[$row->id] : "",
													'rating' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['averageRating']:0,
													'total_reviews' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['totalRating']:0,
													"flag" => (is_null($row->flag_name)) ? "":$row->flag_name,
												);
												
					#setting up the value for the location filter
					if( !in_array($row->location_id, $arrLocationId)) {
						$arrLocationId[] = $row->location_id;
						$this->filters['locations'][] = array(
																"id" => $row->location_id,
																"name" => $row->location_name,
																"count" => 1
															);
					}
					else {
						foreach($this->filters['locations'] as $key => $value) {
							if($value['id'] == $row->location_id) {
								$this->filters['locations'][$key]['count']++;
							}
						}
					}					
				}
				#setting up remaining filters
				$this->initializeExperienceFilters($arrProduct);
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
												'totalRating' => $row->total_ratings
												);
			}
			return $arrRating;
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
		 * Returns the images associated with matching experiences
		 * in the passed array.
		 * 
		 * @access	public
		 * @param	array 	$arrExperience
		 * @return	array
		 * @since	1.0.0
		 */
		public function getExperienceImages($arrExperience) {
			//query to read media details
			$queryImages = DB::table('media_resized_new as mrn')
						->leftJoin('product_media_map as pmm','pmm.media_id','=','mrn.media_id')
						->whereIn('pmm.product_id',$arrExperience)
						->where('pmm.media_type','mobile')
						->select('mrn.file as image','mrn.image_type','pmm.product_id')
						->get();
			//array to store images
			$arrImage = array();
			if($queryImages) {
				foreach($queryImages as $row) {
					if(!array_key_exists($row->product_id, $arrImage)) {
						$arrImage[$row->product_id] = array();
					}
					if(in_array($row->image_type, array('mobile_listing_android_experience','mobile_listing_ios_experience'))) {
						$arrImage[$row->product_id][$row->image_type] = Config::get('constants.API_MOBILE_IMAGE_URL').$row->image;
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
	 * Initializes the value for the filters to be sent
	 * with search results.
	 * 
	 * @access	public
	 * @param	array 	$arrProduct
	 * @since	1.0.0
	 */
	public function initializeExperienceFilters($arrProduct) {
		//query to read cuisines
		$queryCuisine = DB::table('product_attributes_select_options as paso')
								->join('product_attributes as pa','pa.id','=','paso.product_attribute_id')
								->join('product_attributes_multiselect as pam','pam.product_attributes_select_option_id','=','paso.id')
								->where('pa.alias','cuisines')
								->whereIn('pam.product_id',$arrProduct)
								->select('paso.id','paso.option','pam.product_id')
								->get();
		
		#setting up the cuisines filter information
		$arrCuisineProduct = array();
		if($queryCuisine) {
			foreach ($queryCuisine as $row) {
				if( ! in_array($row->id, $arrCuisineProduct)) {
					$arrCuisineProduct[] = $row->id; 
					$this->filters['cuisines'][] = array(
													"id" => $row->id,
													"name" => $row->option,
													"count" => 1
												);
				}
				else {
					foreach($this->filters['cuisines'] as $key => $value) {
						if($value['id'] == $row->id) {
								$this->filters['cuisines'][$key]['count']++;
							}
						}
				}
			}
		}

		//query to initialize the tags
		$queryTag = DB::table('tags')
						->join('product_tag_map as ptm','ptm.tag_id','=','tags.id')
						->whereIn('ptm.product_id', $arrProduct)
						->select('tags.name', 'tags.id')
						->get();
		
		#setting up the tag filter information
		$arrTagProduct = array();
		if($queryTag) {
			foreach ($queryTag as $row) {
				if( ! in_array($row->id, $arrTagProduct)) {
					$arrTagProduct[] = $row->id; 
					$this->filters['tags'][] = array(
													"id" => $row->id,
													"name" => $row->name,
													"count" => 1
												);
				}
				else {
					foreach($this->filters['tags'] as $key => $value) {
						if($value['id'] == $row->id) {
								$this->filters['tags'][$key]['count']++;
							}
						}
				}
			}
		}
		
		#setting the value of min and max price
		$this->filters['price_range'] = array(
											"name" => 'Price Range',
											"type" => 'single',
											"options" => array(
															"min" => $this->minPrice,
															"max" => $this->maxPrice,
														)
										);
	}
}
//end of class Search
//end of file WowTables\Http\Models\E