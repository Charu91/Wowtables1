<?php namespace WowTables\Http\Models;

use Carbon\Carbon;
use DB;
use Config;

class RestaurantLocations extends VendorLocations{

    public $filters = array(
        					'date' => array(),
        					'time' => array(),
        					'pricing_level' => array(
            										'options' => array('Low','Medium','High')
        										),
        					'cuisines' => array(
            									'options' => array(),
            									//'active' => array()
        									),
        					'tags' => array(
            								'options' => array(),
            								//'active' => array()
        								),
        					'areas' => array(
            							'options' => array(),
            							//'active' => array()
        							),
    					);

    public $listing;

    public $total_count;

    public $total_pages;

    protected $filterOptions = ['date', 'time', 'tags', 'areas','cuisines','pricing_level'];

    public $sort_options = ['Latest','Popular'];
	
	public $arr_result;

    public function fetchAll($data){
        $params = [$data['filters']];

        if(isset($data['items_per_page'])){
            $params[] = $data['items_per_page'];
        }

        if(isset($data['sort_by'])){
            $params[] = $data['sort_by'];
        }

        if(isset($data['pagenum'])){
            $params[] = $data['pagenum'];
        }

        call_user_func_array([$this, 'fetchListings'], $params);
    }

    public function fetchListings(array $filters, $items_per_page = 10, $sort_by = 'Latest', $pagenum = null ){
        if(!$pagenum) $pagenum = 1;

        $offset = ($pagenum - 1) * $items_per_page;
		if(!empty($_SERVER['HTTP_X_WOW_TOKEN'])){
			$access_token=$_SERVER['HTTP_X_WOW_TOKEN'];		
			$userId = UserDevices::getUserDetailsByAccessToken($access_token);
		}else{
			$userId = 0;
		}
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
            ->leftjoin('vendor_location_address as vlaa', 'vlaa.vendor_location_id', '=', 'vl.id')                          
            ->join('locations as loc1','loc1.id', '=' , 'vlaa.area_id')
            ->join('locations as loc2', 'loc2.id', '=', 'vlaa.city_id')
            ->join('locations as loc3', 'loc3.id', '=', 'vlaa.state_id')
            ->join('locations as loc4', 'loc4.id', '=', 'vlaa.country_id')
            ->join('locations as loc5','loc5.id','=','vl.location_id')
			->leftJoin('user_bookmarks as ub', function($join) use ($userId) {				
                $join->on('vl.id', '=', 'ub.vendor_location_id')
                    ->where('ub.user_id','=', $userId);
            })
            ->select(
                DB::raw('SQL_CALC_FOUND_ROWS vl.id'),
                'v.name AS restaurant',
                'l.name AS locality',
                'la.name AS area',
                'la.id as area_id',
                'vl.pricing_level',
                'vladd.latitude','vladd.longitude',
				'ub.id as bookmarked',
                //'mr.file AS image',
                //'m.alt AS image_alt',
                //'m.title AS image_title',                
                'vlaa.latitude','vlaa.longitude', 'vlaa.address', 'vlaa.pin_code',
                'loc1.name as area', 'loc1.id as area_id', 'loc2.name as city', 'loc3.name as state_name',
                'loc4.name as country', 'loc5.name as locality',
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
        	$select->whereIn('la.id', $filters['area']);
            //$this->filters['areas']['active'] = $filters['area'];
        }
        /*
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
        */
        /* setting up the pricing level filter */
        if(isset($filters['pricing_level'])) {
            $select->whereIn('vl.pricing_level',$filters['pricing_level']);
        }


        if(isset($filters['tag'])){
            $select->whereIn('vltm.tag_id', $filters['tag']);
            //$this->filters['tag']['active'] = $filters['tag'];
        }

        if(isset($filters['cuisine'])){
            $select->whereIn('vaso.id', $filters['cuisine']);
            //$this->filters['cuisine']['active'] = $filters['cuisine'];
        }

        if(isset($filters['date']) && isset($filters['time'])){
            $select->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']])
                ->where('ts.time', $filters['time']);
            $this->filters['tags']['date'] = $filters['date'];
            $this->filters['tags']['time'] = $filters['time'];
        }else if(isset($filters['date'])){
            $select->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']]);
            $this->filters['tags']['date'] = $filters['date'];
        }else if(isset($filters['time'])){
            $select->where('ts.time', $filters['time']);
            $this->filters['tags']['time'] = $filters['time'];
        }

        if($sort_by === 'Popular'){
            $select->orderBy('vl.id', 'asc');
        }else if($sort_by === 'Latest'){
            $select->orderBy('v.publish_time', 'desc');
        }else{
            $select->orderBy('v.publish_time', 'desc');
        }
		
		//echo $select->toSql();
        $this->listing = $select->get();
		
		$this->formatResultToArray($this->listing);

        $totalCountResult = DB::select('SELECT FOUND_ROWS() AS total_count');
        //dd($totalCountResult);
        if($totalCountResult){
            $this->total_count = $totalCountResult[0]->total_count;
            $this->total_pages = (int)ceil($this->total_count/$items_per_page);

          //  $this->fetchFilters($filters);
          //  $this->fetchMaxDate();
          //  $this->fetchTimings();
        }else{
            $this->total_count = 0;
            $this->total_pages = 0;
        }
    }

    public function fetchFilters($filters){
        $tags = DB::table('vendor_locations_tags_map AS vltm')
            ->join('tags AS t', 't.id', '=', 'vltm.tag_id')
            ->join('vendor_locations AS vl', 'vl.id', '=', 'vltm.vendor_location_id')
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_types AS vt', 'vt.id', '=', 'v.vendor_type_id')
            ->join('vendor_location_address AS vladd', 'vl.id', '=', 'vladd.vendor_location_id')
            ->join('locations AS la', 'la.id', '=', 'vladd.area_id')
            ->join('locations AS lc', 'lc.id', '=', 'vladd.city_id')
            ->leftJoin('vendor_location_attributes_multiselect AS vlam', 'vl.id', '=', 'vlam.vendor_location_id')
            ->leftJoin('vendor_attributes_select_options AS vaso', 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')
            ->leftJoin('vendor_attributes AS vamso', function($join){
                $join->on('vamso.id', '=', 'vaso.vendor_attribute_id')
                    ->on('vamso.alias', '=', DB::raw('"cuisines"'));
            })
            ->join('vendor_location_booking_schedules AS vlbs', 'vlbs.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_location_blocked_schedules AS vlbls', 'vlbls.vendor_location_id', '=', 'vl.id')
            ->join('schedules AS s', 'vlbs.schedule_id', '=', 's.id')
            ->join('time_slots AS ts', 's.time_slot_id', '=', 'ts.id')
            ->select(
                DB::raw('DISTINCT vltm.tag_id AS filter_id'),
                't.name AS filter_value',
                DB::raw('"tags" AS filter_type')
            )
            ->where('vt.type', '=', DB::raw('"Restaurants"'))
            ->where('v.status', '=', DB::raw('"Publish"'))
            ->where('v.publish_time', '<', DB::raw('NOW()'))
            ->where('lc.id', $filters['city_id'])
            ->where('vl.a_la_carte', 1)
            ->groupBy('vltm.tag_id');

        if(isset($filters['areas'])){
            $tags->whereIn('la.id', $filters['areas']);
        }

        if(isset($filters['pricing_level'])){
            $tags->where('vl.pricing_level', $filters['pricing_level']);
        }

        if(isset($filters['tags'])){
            $tags->whereIn('vltm.tag_id', $filters['tags']);
        }

        if(isset($filters['cuisines'])){
            $tags->whereIn('vaso.id', $filters['cuisines']);
        }

        if(isset($filters['date']) && isset($filters['time'])){
            $tags->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']])
                ->where('ts.time', $filters['time']);
        }else if(isset($filters['date'])){
            $tags->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']]);
        }else if(isset($filters['time'])){
            $tags->where('ts.time', $filters['time']);
        }

        $areas = DB::table('vendor_location_address AS vladd')
            ->join('vendor_locations AS vl', 'vl.id', '=', 'vladd.vendor_location_id')
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_types AS vt', 'vt.id', '=', 'v.vendor_type_id')
            ->join('locations AS la', 'la.id', '=', 'vladd.area_id')
            ->join('locations AS lc', 'lc.id', '=', 'vladd.city_id')
            ->leftJoin('vendor_location_attributes_multiselect AS vlam', 'vl.id', '=', 'vlam.vendor_location_id')
            ->leftJoin('vendor_attributes_select_options AS vaso', 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')
            ->leftJoin('vendor_attributes AS vamso', function($join){
                $join->on('vamso.id', '=', 'vaso.vendor_attribute_id')
                    ->on('vamso.alias', '=', DB::raw('"cuisines"'));
            })
            ->join('vendor_location_booking_schedules AS vlbs', 'vlbs.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_location_blocked_schedules AS vlbls', 'vlbls.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_locations_tags_map AS vltm', 'vl.id', '=', 'vltm.vendor_location_id')
            ->join('schedules AS s', 'vlbs.schedule_id', '=', 's.id')
            ->join('time_slots AS ts', 's.time_slot_id', '=', 'ts.id')
            ->select(
                DB::raw('DISTINCT vladd.area_id AS filter_id'),
                'la.name AS filter_value',
                DB::raw('"areas" AS filter_type')
            )
            ->where('vt.type', '=', DB::raw('"Restaurants"'))
            ->where('v.status', '=', DB::raw('"Publish"'))
            ->where('v.publish_time', '<', DB::raw('NOW()'))
            ->where('lc.id', $filters['city_id'])
            ->where('vl.a_la_carte', 1)
            ->groupBy('vladd.area_id');

        if(isset($filters['pricing_level'])){
            $areas->where('vl.pricing_level', $filters['pricing_level']);
        }

        if(isset($filters['cuisines'])) {
            $areas->whereIn('vaso.id', $filters['cuisines']);
        }

        if(isset($filters['tags'])){
            $areas->whereIn('vltm.tag_id', $filters['tags']);
        }

        if(isset($filters['date']) && isset($filters['time'])){
            $areas->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']])
                ->where('ts.time', $filters['time']);
        }else if(isset($filters['date'])){
            $areas->where('s.day', '=', DB::raw('DAYNAME("'.$filters['date'].'")'))
                ->whereNotIn('vlbls.block_date', [$filters['date']]);
        }else if(isset($filters['time'])){
            $areas->where('ts.time', $filters['time']);
        }

        $cuisines = DB::table('vendor_location_attributes_multiselect AS vlam')
            ->join('vendor_locations AS vl', 'vl.id', '=', 'vlam.vendor_location_id')
            ->leftJoin('vendor_attributes_select_options AS vaso', 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')            
            ->leftJoin('vendor_attributes AS vamso', function($join){
                $join->on('vamso.id', '=', 'vaso.vendor_attribute_id')
                    ->on('vamso.alias', '=', DB::raw('"cuisines"'));
            })
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_types AS vt', 'vt.id', '=', 'v.vendor_type_id')
            ->join('vendor_location_address AS vladd', 'vl.id', '=', 'vladd.vendor_location_id')
            ->join('locations AS la', 'la.id', '=', 'vladd.area_id')
            ->join('locations AS lc', 'lc.id', '=', 'vladd.city_id')
            ->join('vendor_location_booking_schedules AS vlbs', 'vlbs.vendor_location_id', '=', 'vl.id')
            ->leftJoin('vendor_location_blocked_schedules AS vlbls', 'vlbls.vendor_location_id', '=', 'vl.id')
            ->join('schedules AS s', 'vlbs.schedule_id', '=', 's.id')
            ->join('time_slots AS ts', 's.time_slot_id', '=', 'ts.id')
            ->leftJoin('vendor_locations_tags_map AS vltm', 'vl.id', '=', 'vltm.vendor_location_id')
            ->select(
                DB::raw('DISTINCT vlam.vendor_attributes_select_option_id AS filter_id'),
                'vaso.option AS filter_value',
                DB::raw('"cuisines" AS filter_type')
            )
            ->where('vt.type', '=', DB::raw('"Restaurants"'))
            ->where('v.status', '=', DB::raw('"Publish"'))
            ->where('v.publish_time', '<', DB::raw('NOW()'))
            ->where('lc.id', $filters['city_id'])
            ->where('vl.a_la_carte', 1)
            ->groupBy('vlam.vendor_attributes_select_option_id');

        if(isset($filters['areas'])){
            $cuisines->whereIn('la.id', $filters['areas']);
        }

        if(isset($filters['pricing_level'])){
            $cuisines->where('vl.pricing_level', $filters['pricing_level']);
        }

        if(isset($filters['tags'])){
            $cuisines->whereIn('vltm.tag_id', $filters['tags']);
        }

        if(isset($filters['cuisines'])){
            $cuisines->whereIn('vaso.id', $filters['cuisines']);
        }

        $filters = $tags->unionAll($areas)->unionAll($cuisines)->get();

        foreach($filters as $filter){
            if($filter->filter_type === 'areas'){
                $this->filters['areas']['options'][] = ['id' => $filter->filter_id, 'value' => $filter->filter_value];
            }else if($filter->filter_type === 'tags'){
                $this->filters['tags']['options'][] = ['id' => $filter->filter_id, 'value' => $filter->filter_value];
            }else if($filter->filter_type === 'cuisines'){
                $this->filters['cuisines']['options'][] = ['id' => $filter->filter_id, 'value' => $filter->filter_value];
            }
        }
    }

    public function fetchMaxDate()
    {
        $this->filters['date']['max_date'] = Carbon::today()->addDays(45)->toDateString();
    }

    public function fetchTimings(){
        $this->filters['time']['slots'] = DB::table('time_slots')->get(['time', 'slot_type']);
    }

	//-----------------------------------------------------------------
	
	/**
	 * Formats the the query result in an array.
	 * 
	 * @access	public
	 * @param	array	$queryResult
	 * @return	array 
	 * @since	1.0.0
	 */
	public function formatResultToArray($queryResult) {
		
		//array to hold all the alacarte ids
		$arrAlaCarte = array();
		
		//array to store location IDs
		$arrLocationId = array();
		
		#creating an array of alacarte id
		foreach($queryResult as $row) {
			$arrAlaCarte[] = $row->id;
		}
		
		$arrImage = $this->getVendorImages($arrAlaCarte);
		$this->initializeRestaurantFilters($arrAlaCarte);
		
		foreach($queryResult as $row) {
			$this->arr_result[] = array(
										'id' => $row->id,
										'restaurant' => $row->restaurant,
										'locality' => $row->locality,
										'area' => $row->area,
										'pricing_level' => $row->pricing_level,
										'bookmarked' => (is_null($row->bookmarked)) ? 0:1,
										'short_description' => (is_null($row->short_description)) ? "": $row->short_description,
										'off_peak_available' => (is_null($row->off_peak_available)) ? "": $row->off_peak_available,
										'total_reviews' => $row->total_reviews,
										'rating' => $row->rating,
										'flag' => $row->flag_name,
										'cuisine' =>  $row->cuisine,
                                        'coordinates' => array(
                                                                            'latitude' => (is_null($row->latitude)) ? "" : $row->latitude,
                                                                            'longitude' => (is_null($row->longitude)) ? "" : $row->longitude
                                                                           ),
										'image' => (array_key_exists($row->id, $arrImage)) ? $arrImage[$row->id] : "",
                                        'location_address' => array([
                                                                        "address_line" => $row->address,
                                                                        "locality" => $row->locality,
                                                                        "area" => $row->area,
                                                                        "city" => $row->city,
                                                                        "pincode" => $row->pin_code,
                                                                        "state" => $row->state_name,                                                                
                                                                        "country" => $row->country,
                                                                        "latitude" => $row->latitude,
                                                                        "longitude" => $row->longitude                                                              
                                                                    ]),
									);
									
			#setting up the value for the location filter
			if( !in_array($row->area_id, $arrLocationId)) {
				$arrLocationId[] = $row->area_id;
				$this->filters['areas']['options'][] = array(
													"id" => $row->area_id,
													"name" => $row->area,
													"count" => 1
												);
					}
					else {
						foreach($this->filters['areas']['options'] as $key => $value) {
							if($value['id'] == $row->area_id) {
								$this->filters['areas']['options'][$key]['count']++;
							}
						}
					}					
				}
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
	public function getVendorImages($arrVendorLocation) {
		//query to read media details
		$queryImages = DB::table('vendor_locations_media_map as vlmm')
						->leftJoin('media_resized_new as mrn','mrn.media_id' ,'=', 'vlmm.media_id')
						->whereIn('vlmm.vendor_location_id',$arrVendorLocation)
						->where('vlmm.media_type','mobile')
						->select('mrn.id','mrn.file as image','mrn.image_type','vlmm.vendor_location_id')
						->groupBy('mrn.id')
						->get();
		
		//array to hold images
		$arrImage = array();
		
		if($queryImages) {
			foreach($queryImages as $row) {
				if(!array_key_exists($row->vendor_location_id, $arrImage)) {
					$arrImage[$row->vendor_location_id] = array();
				}
				if(in_array($row->image_type, array('mobile_listing_android_alacarte','mobile_listing_ios_alacarte'))) {
					$arrImage[$row->vendor_location_id][$row->image_type] = Config::get('constants.API_MOBILE_IMAGE_URL').$row->image;
				}							
			}
		}		
		return $arrImage;		
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * 
	 */
	public function initializeRestaurantFilters($arrVendorLocation) {
		//query to read cuisines
		$queryCuisine = DB::table('vendor_attributes_select_options as vaso')
								->join('vendor_attributes as va','va.id','=','vaso.vendor_attribute_id')
								->join('vendor_location_attributes_multiselect as vlam','vlam.vendor_attributes_select_option_id','=','vaso.id')
								->where('va.alias','cuisines')
								->whereIn('vlam.vendor_location_id',$arrVendorLocation)
								->select('vaso.id','vaso.option')
								->get();
		
		#setting up the cuisines filter information
		$arrCuisineProduct = array();
		if($queryCuisine) {
			foreach ($queryCuisine as $row) {
				if( ! in_array($row->id, $arrCuisineProduct)) {
					$arrCuisineProduct[] = $row->id; 
					$this->filters['cuisines']['options'][] = array(
																"id" => $row->id,
																"name" => $row->option,
																"count" => 1
															);
				}
				else {
					foreach($this->filters['cuisines']['options'] as $key => $value) {
						if($value['id'] == $row->id) {
								$this->filters['cuisines']['options'][$key]['count']++;
							}
						}
				}
			}
		}

		//query to initialize the tags
		$queryTag = DB::table('tags')
						->join('vendor_locations_tags_map as vltm','vltm.tag_id','=','tags.id')
						->whereIn('vltm.vendor_location_id', $arrVendorLocation)
						->select('tags.name', 'tags.id')
						->get();
		
		#setting up the tag filter information
		$arrTagProduct = array();
		if($queryTag) {
			foreach ($queryTag as $row) {
				if( ! in_array($row->id, $arrTagProduct)) {
					$arrTagProduct[] = $row->id; 
					$this->filters['tags']['options'][] = array(
															"id" => $row->id,
															"name" => $row->name,
															"count" => 1
														);
				}
				else {
					foreach($this->filters['tags']['options'] as $key => $value) {
						if($value['id'] == $row->id) {
								$this->filters['tags']['options'][$key]['count']++;
							}
						}
				}
			}
		}
		
	} 
}
//end of class RestaurantLocations.php