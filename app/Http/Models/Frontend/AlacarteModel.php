<?php namespace WowTables\Http\Models\Frontend;

use DB;
use URL;
use Config;

class AlacarteModel{

    public $filters = array(
                            'date' => array(),
                            'time' => array(),
                            'pricing_level' => array('Low','Medium','High'),
                            'cuisines' => array(),
                            'tags' => array(),
                            'areas' => array(),
                        );

    public $listing;

    public $total_count;

    public $total_pages;

    protected $filterOptions = ['date', 'time', 'tags', 'areas','cuisines','pricing_level'];

    public $sort_options = ['Latest','Popular'];
    
    public $arr_result;

    /**
     * Minimum Price for experiences in the given city.
     * 
     * @var   float
     * @access  protected
     * @since 1.0.0
     */
    protected $minPrice;
    
    /**
     * Maximum price for experiences in the given city.
     * 
     * @var   float
     * @access  protected
     * @since 1.0.0
     */
    protected $maxPrice;
    

    public function getAlaCarteSearchFilters()
    {
        return $this->filters;
    }

     public function getAlaCarteAreaCuisineByName($arrData = array())
    {
      
      $experienceQuery = 'select `products`.`id`, `locations`.`name` as `location_name`, 
                  `locations`.`id` as `location_id`, `vendors`.`name` as `vendor_name`, 
                  `vendors`.`id` as `vendor_id`, `paso`.`id` as `cuisine_id`, `paso`.`option` as `cuisine_name` 
                  from `products` 
                  inner join `product_vendor_locations` as `pvl` on `pvl`.`product_id` = `products`.`id` 
                  left join `vendor_location_address` as `vla` on `vla`.`vendor_location_id` = `pvl`.`vendor_location_id` 
                  left join `vendor_locations` as `vl` on `vl`.`id` = `pvl`.`vendor_location_id` 
                  left join `locations` on `locations`.`id` = `vl`.`location_id` 
                  left join `product_attributes_multiselect` as `pam` on `pam`.`product_id` = `products`.`id` 
                  left join `product_attributes_select_options` as `paso` on `paso`.`id` = `pam`.`product_attributes_select_option_id` 
                  inner join `vendors` on `vendors`.`id` = `vl`.`vendor_id` 
                  where `pvl`.`status` = "Active" and `vla`.`city_id` = "'.$arrData['city_id'].'" 
                  and `products`.`visible` = 1 and `products`.`type` in ("simple","complex") AND
                  (`paso`.`option` like  "%'.$arrData['term'].'%"  or `locations`.`name` like  "%'.$arrData['term'].'%" 
                   or `vendors`.`name` like  "%'.$arrData['term'].'%" )';

      //executing the query
      $experienceResult = DB::select($experienceQuery);

      //array to store the product ids
      $arrProduct = array();
      //array to store final result
      $arrData = array();

      #query executed successfully
      if($experienceResult) {
          $arrData = array();
          foreach($experienceResult as $row) {

                $arrData[] = $row->location_name.'~~~'.$row->location_id.'~~~location';
                $arrData[] = $row->vendor_name.'~~~'.$row->vendor_id.'~~~vendor';
                $arrData[] = $row->cuisine_name.'~~~'.$row->cuisine_id.'~~~cuisine';
          }
      }
     // echo '<pre>';print_r($arrData['data']);
      $arrDataNew =  array_unique( $arrData);
      return $arrDataNew;
    } 


    public function findMatchingAlacarte(array $filters)
    {
         $select = DB::table('vendor_locations AS vl')
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_types AS vt', 'vt.id', '=', 'v.vendor_type_id')
            ->join('vendor_location_address AS vladd', 'vl.id', '=', 'vladd.vendor_location_id')
            ->join('locations AS l', 'l.id', '=', 'vl.location_id')
            ->join('locations AS la', 'la.id', '=', 'vladd.area_id')
            ->join('locations AS lc', 'lc.id', '=', 'vladd.city_id')
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
                'vl.pricing_level',
                'vl.slug',
                'l.id as location_id',
                DB::raw('MAX(IF(va.alias = "short_description", vlav.attribute_value, null)) AS short_description'),
                DB::raw('MAX(vlbs.off_peak_schedule) AS off_peak_available'),
                DB::raw(('COUNT(DISTINCT vlr.id) AS total_reviews')),
                DB::raw('If(count(DISTINCT vlr.id) = 0, 0, ROUND(AVG(vlr.rating), 2)) AS rating'),
                DB::raw('IFNULL(flags.name,"") AS flag_name'),
                DB::raw('flags.color as flag_color'),
                DB::raw('GROUP_CONCAT(DISTINCT vaso.option separator ", ") as cuisine')
            )
            ->where('vt.type', DB::raw('"Restaurants"'))
            ->where('v.status', DB::raw('"Publish"'))
            ->where('v.publish_time', '<', DB::raw('NOW()'))
            ->where('lc.id', $filters['city_id'])
            ->where('vl.a_la_carte', 1)
            ->groupBy('vl.id');



       if(isset($filters['areas'])){
            $select->whereIn('la.id', $filters['areas']);
            $this->filters['areas']['active'] = $filters['areas'];
        }

        if(isset($filters['pricing_level'])){
            $select->where('vl.pricing_level', $filters['pricing_level']);
            $this->filters['pricing_level']['active'] = $filters['pricing_level'];
        }

        if(isset($filters['tags'])){
            $select->whereIn('vltm.tag_id', $filters['tags']);
            $this->filters['tags']['active'] = $filters['tags'];
        }

        if(isset($filters['cuisines'])){
            $select->whereIn('vaso.id', $filters['cuisines']);
            $this->filters['tags']['cuisines'] = $filters['cuisines'];
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

        if(isset($filters['sort_by']) === 'Popular'){
            $select->orderBy('vl.id', 'asc');
        }else if(isset($filters['sort_by']) === 'Latest'){
            $select->orderBy('v.publish_time', 'desc');
        }else{
            $select->orderBy('v.publish_time', 'desc');
        }
        
        //echo $select->toSql();
        $queryResult = $select->get();

      
        //array to hold all the alacarte ids
        $arrAlaCarte    = array();
        
        //array to store location IDs
        $arrLocationId  = array();
        
      #query executed successfully
      if($queryResult) {
          $arrData['resultCount'] = 0;
          $arrData['alacarte'] = array();
        #initializing the total number of matching rows returned
        $arrData['resultCount'] = count($queryResult);

         #creating an array of alacarte id
        foreach($queryResult as $row) {
            $arrAlaCarte[] = $row->id;
        }

        $arrImage = $this->getVendorImages($arrAlaCarte);
        $this->initializeAlacarteFilters($arrAlaCarte);
        
        //array to store location IDs
        $arrLocationId = array();
        
        foreach($queryResult as $row) {
         // $this->minPrice = ($this->minPrice > $row->price || $this->minPrice == 0) ? $row->price : $this->minPrice;
         // $this->maxPrice = ($this->maxPrice < $row->price || $this->maxPrice == 0) ? $row->price : $this->maxPrice;
        $num_of_full_starts = round($row->rating,1);// number of full stars
        $num_of_half_starts     = $num_of_full_starts-floor($num_of_full_starts); //number of half stars
        $number_of_blank_starts = 5-($row->rating); //number of white stars

        $arrData['data'][] = array(
                                'id' => $row->id,
                                'restaurant' => $row->restaurant,
                                'locality' => $row->locality,
                                'area' => $row->area,
                                'slug'=>$row->slug,
                                'pricing_level' => $row->pricing_level,
                                'short_description' => (is_null($row->short_description)) ? "": $row->short_description,
                                'off_peak_available' => (is_null($row->off_peak_available)) ? "": $row->off_peak_available,
                                'total_reviews' => $row->total_reviews,
                                'rating' => $row->rating,
                                'full_stars' => $num_of_full_starts,
                                'half_stars' => $num_of_half_starts,
                                'blank_stars' => $number_of_blank_starts,
                                "flag_name" => (is_null($row->flag_name)) ? "":$row->flag_name,
                                "color" => (is_null($row->flag_color)) ? "#fff":$row->flag_color,
                                'cuisine' =>  $row->cuisine,
                                'image' => (array_key_exists($row->id, $arrImage)) ? $arrImage[$row->id] : "" 
                            );
                            
                    #setting up the value for the location filter
                    if( !in_array($row->location_id, $arrLocationId)) {
                        $arrLocationId[] = $row->location_id;
                        $this->filters['areas'][] = array(
                                                        "id" => $row->location_id,
                                                        "name" => $row->locality,
                                                        "count" => 1
                                                    );
                        }
                        else {
                            foreach($this->filters['areas'] as $key => $value) {
                                if($value['id'] == $row->location_id) {
                                    $this->filters['areas'][$key]['count']++;
                                }
                            }
                        }    
        }

      }
     
        // echo '<pre>';print_r($arrData['data']);
        return $arrData;
    }

     public function initializeAlacarteFilters($arrVendorLocation) {
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
        
    } 

    //-----------------------------------------------------------------
    
    /**
     * Returns the images for the passed vendor location id.
     * 
     * @static  true
     * @access  public
     * @since   1.0.0
     * @version 1.0.0
     */
    public function getVendorImages($arrVendorLocation) {
        //query to read media details
        $queryImages = DB::table('media_resized_new as mrn')
                        ->leftJoin('vendor_locations_media_map as vlmm','vlmm.media_id','=','mrn.media_id')
                        ->whereIn('vlmm.vendor_location_id',$arrVendorLocation)
                        ->where('vlmm.media_type','listing')
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
                if(in_array($row->image_type, array('listing'))) {
                    $arrImage[$row->vendor_location_id][$row->image_type] = Config::get('constants.API_LISTING_IMAGE_URL').$row->image;
                }                           
            }
        }       
        return $arrImage;       
    }
    

    public static function getVendorImagesDetails($vendorLocationID) {
        //query to read media details
        $queryImages = DB::table('media_resized_new as mrn')
                        ->leftJoin('vendor_locations_media_map as vlmm','vlmm.media_id','=','mrn.media_id')
                        ->where('vlmm.vendor_location_id',$vendorLocationID)
                        ->where('vlmm.media_type','listing')
                        ->select('mrn.id','mrn.file as image','mrn.image_type')
                        ->groupBy('mrn.id')
                        ->get();
        
        //array to hold images
        $arrImage = array();
        
        if($queryImages) {
            foreach($queryImages as $row) {
                if(in_array($row->image_type, array('listing'))) {
                    $arrImage[$row->image_type] = Config::get('constants.API_LISTING_IMAGE_URL').$row->image;
                }
                if($row->image_type = 'gallery') {
                    $arrImage['gallery'][] = Config::get('constants.API_LISTING_IMAGE_URL').$row->image;
                }
            }
        }
        
        return $arrImage;
            
    }

    public  function getALaCarteDetails( $aLaCarteID ) {
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
                        ->leftjoin(DB::raw('vendor_locations_curator_map as vlcm'),'vlcm.vendor_location_id','=','vl.id')
                        ->leftjoin('curators', 'curators.id', '=', 'vlcm.curator_id')
                        ->join(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','vl.id')
                        ->join(DB::raw('locations as loc1'),'loc1.id', '=' , 'vla.area_id')
                        ->join(DB::raw('locations as loc2'), 'loc2.id', '=', 'vla.city_id')
                        ->join(DB::raw('locations as loc3'), 'loc3.id', '=', 'vla.state_id')
                        ->join(DB::raw('locations as loc4'), 'loc4.id', '=', 'vla.country_id')
                        //->leftJoin(DB::raw('vendor_locations_media_map as vlmm'),'vlmm.vendor_location_id','=','vl.id')
                        //->leftJoin(DB::raw('media as m1'), 'm1.id', '=', 'vlmm.media_id')
                        ->leftJoin(DB::raw('media_resized_new as m2'), 'm2.id', '=', 'curators.media_id')
                        ->leftJoin(DB::raw('vendor_location_attributes_integer as vlai'),'vlai.vendor_location_id','=','vl.id')
                        ->leftJoin(DB::raw('vendor_attributes as va5'),'va5.id','=','vlai.vendor_attribute_id')
                        ->leftJoin(DB::raw('vendor_location_attributes_text as vlat5'),'vlat5.vendor_location_id','=','vl.id')
                        ->leftJoin(DB::raw('vendor_attributes as va6'),'va6.id','=','vlat5.vendor_attribute_id')
                        ->where('vl.id',$aLaCarteID)
                        ->where('vl.a_la_carte','=',1)
                        ->where('vl.status','Active')
                        ->where('va1.alias','restaurant_info')
                        ->where('va2.alias','short_description')
                        ->where('va3.alias','terms_and_conditions')
                        ->where('va4.alias','menu_picks')
                        //->where('vlmm.media_type','listing')
                        ->where('va5.alias','reward_points_per_reservation')
                        ->where('va6.alias','expert_tips')
                        ->groupBy('vl.id')
                        ->select('vl.id as vl_id','vl.slug as vl_slug','vl.vendor_id', 'vla.address','vla.pin_code', 
                                    'vla.latitude', 'vla.longitude', 'vendors.name as title', 'vlat1.attribute_value as resturant_info', 
                                    'vlat2.attribute_value as short_description', 'vlat3.attribute_value as terms_conditions', 
                                    'vlat4.attribute_value as menu_picks', 'loc1.name as area', 'loc1.id as area_id', 'loc2.name as city', 
                                    'loc3.name as state_name', 'loc4.name as country', 'curators.name as curator_name', 'curators.bio as curator_bio',
                                    'curators.designation as designation','vl.pricing_level','vlai.attribute_value as reward_point',
                                    'vlat5.attribute_value as expert_tips', 'm2.file as curator_image','vl.location_id as vl_location_id')
                        ->first();
                        
        if($queryResult) {
            //reading the review ratings
            $arrReview = Self::findRatingByVendorLocation(array($queryResult->vl_id));
            
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
            $arrImage = $this->getVendorImagesDetails($queryResult->vl_id);
                        
            //formatting the array for the data
            $arrData['data'] = array(
                                    'type' => 'A-La-Carte Details',
                                    'slug'  => $queryResult->vl_slug,
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
                                                                'image' => (is_null($queryResult->curator_image)) ? "" : Config::get('constants.API_LISTING_IMAGE_URL'). $queryResult->curator_image,
                                                                'designation' => (is_null($queryResult->designation)) ? "" : $queryResult->designation
                                                            ),
                                    'menu_pick' => (is_null($queryResult->menu_picks)) ? "" : $queryResult->menu_picks,
                                    'similar_option' => $arrSimilarVendor,
                                    'reward_point' => (is_null($queryResult->reward_point)) ? 0:$queryResult->reward_point,
                                    'expert_tips' => (is_null($queryResult->expert_tips)) ? "" : $queryResult->expert_tips                                  
                                );
            
            //reading the review details
            $arrData['data']['review_detail'] = $this->getVendorLocationRatingDetails($queryResult->vl_id);
            
            //reading the locations
            $arrData['data']['other_location'] = $this->getVendorLocation($queryResult->vendor_id, $queryResult->vl_location_id);
            
            
        }
        return $arrData;                
    }

    public static function findRatingByVendorLocation($arrVendorLocation) {
        $queryResult = DB::table('vendor_location_reviews')
                        ->whereIN('vendor_location_id',$arrVendorLocation)
                        ->groupBy('vendor_location_id')
                        ->where('status','Approved')
                        ->select(DB::raw('AVG(rating) as avg_rating, COUNT(*) as total_ratings,vendor_location_id'))
                        ->get();
        
        //array to store the result
        $arrRating = array();
        
        //reading the results
        foreach($queryResult as $row) {
            $arrRating[$row->vendor_location_id] = array(
                                                        'averageRating' => $row->avg_rating,
                                                        'totalRating' => $row->total_ratings
                                                    );
        }       
        return $arrRating;
    }

    //-----------------------------------------------------------------
    
    /**
     * Returns the aLacarte details matching the passed
     * parameters.
     * 
     * @static  true
     * @access  public
     * @param   array $arrData
     * @since   1.0.0
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
            $arrReview = Self::findRatingByVendorLocation($arrVendorId);
            
            //getting vendors available cuisine
            $arrVendorCuisine = Self::getVendorLocationCuisine($arrVendorId);
            
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
     * @static  true
     * @access  public
     * @param   array   $arrVendor
     * @return  array
     * @since   1.0.0
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
     * @access  public
     * @param   integer $locationId
     * @return  boolean
     * @since   1.0.0
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
    
    public static function getVendorLocationRatingDetails($vendorLocationID, $start = NULL, $limit = NULL) {
        $strQuery = DB::table(DB::raw('vendor_location_reviews as vlr'))
                        ->join('users','users.id','=', 'vlr.user_id')
                        ->where('vlr.vendor_location_id',$vendorLocationID)
                        ->where('status','Approved')
                        ->select('users.id','users.full_name','vlr.review','vlr.rating','vlr.created_at');
        if(!empty($start) && !empty($limit)) {
            $strQuery = $strQuery->skip($start)->take($limit);
        }
        
        //executing the query
        $queryResult = $strQuery->get();        
        
        //array to store the result
        $arrReviewDetail = array();
        
        //initializing the results
        if($queryResult) {
            foreach($queryResult as $row) {
                $arrReviewDetail[] = array(
                                        'id' => $row->id,
                                        'name' => $row->full_name,
                                        'image' => "",
                                        'review' => $row->review,
                                        'rating' => $row->rating,
                                        'created_at' => $row->created_at
                                    );
            }
        }
        return $arrReviewDetail;
    }
    
    
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
    
}
