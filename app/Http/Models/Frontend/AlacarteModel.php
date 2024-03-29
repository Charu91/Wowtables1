<?php namespace WowTables\Http\Models\Frontend;

use DB;
use URL;
use Config;
use WowTables\Http\Models\Eloquent\Reservations\ReservationDetails;

class AlacarteModel{

    protected $filters = array(
                            'date' => array(),
                            'time' => array(),
                            'pricing_level' => array('Low','Medium','High'),
                            'cuisines' => array(),
                            'tags' => array(),
                            'locations' => array(),
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
      }else{
          $arrData[] = 'No Data Found!~~~1~~~no_data';
      }
     // echo '<pre>';print_r($arrData['data']);
      $arrDataNew =  array_unique( $arrData);
      return $arrDataNew;
    }


    public function findMatchingAlacarte(array $filters)
    {   //echo "<pre>"; print_r($filters); die;
         $select = DB::table('vendor_locations AS vl')
            ->join('vendors AS v', 'v.id', '=', 'vl.vendor_id')
            ->join('vendor_types AS vt', 'vt.id', '=', 'v.vendor_type_id')
            ->join('vendor_location_address AS vladd', 'vl.id', '=', 'vladd.vendor_location_id')
            ->join('locations AS l', 'l.id', '=', 'vl.location_id')
            ->join('locations AS la', 'la.id', '=', 'vladd.area_id')
            ->join('locations AS lc', 'lc.id', '=', 'vladd.city_id')
            ->join('vendor_location_attributes_varchar AS vlav', 'vl.id', '=', 'vlav.vendor_location_id')
            ->join('vendor_location_attributes_text AS vlat', 'vl.id', '=', 'vlat.vendor_location_id')
            ->join('vendor_attributes AS va', 'va.id', '=', 'vlav.vendor_attribute_id')
            ->join('vendor_attributes AS va1', 'va1.id', '=', 'vlat.vendor_attribute_id')
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
                DB::raw('MAX(IF(va1.alias = "special_offer", vlat.attribute_value, null)) AS special_offer'),
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
            ->where('vladd.city_id', $filters['city_id'])
            ->where('vl.a_la_carte', 1)
            ->where('vl.status', 'active')
            ->groupBy('vl.id');



        if(isset($filters['location']) && !empty($filters['location'])) {
            $select->whereIn('l.id', $filters['location']);
            $this->filters['location']['active'] = $filters['location'];
        }

        if(isset($filters['pricing_level'])){
            //echo "<pre>"; print_r($filters['pricing_level']);
            $select->whereIn('vl.pricing_level', $filters['pricing_level']);
            //$this->filters['pricing_level']['active'] = $filters['pricing_level'];
        }
            //die;
        if(isset($filters['tags'])){
             $select->whereIn('vltm.tag_id', $filters['tags']);
             $this->filters['tags']['active'] = $filters['tags'];
         }

        if(isset($filters['cuisine'])){
             $select->whereIn('vaso.id', $filters['cuisine']);
             $this->filters['tags']['cuisine'] = $filters['cuisine'];
         }

         //adding filter for price if price has been selected
         if(isset($filters['vendor']) && isset($filters['vendor'])) {
             $select->whereIN('vl.vendor_id',$filters['vendor']);
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
             $select->orderBy('vl.order_status', 'asc');
         }else if(isset($filters['sort_by']) === 'Latest'){
             $select->orderBy('vl.order_status', 'asc');
         }else{
             $select->orderBy('vl.order_status', 'asc');
         }
        //echo "sad = ".$select;
        //echo $select->toSql();

        $queryResult = $select->get();
        //var_dump($queryResult);die;
        //print_r($queryResult);die;
        //echo "<pre>"; print_r($queryResult); die;


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
        //var_dump($row);die;
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
                                'special_offer' => $row->special_offer,
                                'image' => (array_key_exists($row->id, $arrImage)) ? $arrImage[$row->id] : ""
                            );

                    #setting up the value for the location filter
                    if( !in_array($row->location_id, $arrLocationId)) {
                        $arrLocationId[] = $row->location_id;
                        $this->filters['locations'][] = array(
                                                        "id" => $row->location_id,
                                                        "name" => $row->locality,
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

      }else{
          $arrData = array();
      }

         //echo '<pre>';print_r($arrData);
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
                        ->where('vlmm.media_type','gallery')
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
                    $arrImage['gallery'][] = Config::get('constants.API_GALLERY_IMAGE_URL').$row->image;
                }
            }
        }

        return $arrImage;

    }

    public  function getALaCarteDetails( $aLaCarteID ) {
        $arrData = array();
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
                        ->leftjoin('vendor_locations_curator_map as vlcm','vlcm.vendor_location_id','=','vl.id')
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
                        //->where('vl.status','Active')
                        ->where('va1.alias','restaurant_info')
                        ->where('va2.alias','short_description')
                        ->where('va3.alias','terms_and_conditions')
                        ->where('va4.alias','menu_picks')
                        //->where('vlmm.media_type','listing')
                        ->where('va5.alias','reward_points_per_reservation')
                        ->where('va6.alias','expert_tips')
                        ->groupBy('vl.id')
                        ->select('vl.id as vl_id','vl.slug as vl_slug','vl.vendor_id', 'vl.status as vl_status','vla.address','vla.pin_code',
                                    'vla.latitude', 'vla.longitude', 'vendors.name as title', 'vlat1.attribute_value as resturant_info',
                                    'vlat2.attribute_value as short_description', 'vlat3.attribute_value as terms_conditions',
                                    'vlat4.attribute_value as menu_picks', 'loc1.name as area', 'loc1.id as area_id', 'loc2.name as city',
                                    'loc3.name as state_name', 'loc4.name as country', 'curators.name as curator_name', 'curators.bio as curator_bio',
                                    'curators.designation as designation','vl.pricing_level','vlai.attribute_value as reward_point',
                                    'vlat5.attribute_value as expert_tips', 'm2.file as curator_image','vl.location_id as vl_location_id','vlcm.curator_tips')
                        ->first();

        //echo "<pre>"; print_r($queryResult); die;

        if($queryResult) {
            //reading the review ratings
            $arrReview = Self::findRatingByVendorLocation(array($queryResult->vl_id));

            //reading vendor-cuisine
            $arrVendorCuisine = Self::getVendorLocationCuisine(array($queryResult->vl_id));
            $arrVendorCuisineID = Self::getRestaurantCuisineID(array($queryResult->vl_id));

            //reading vendor-cuisine
            $seo_meta_description = DB::select("SELECT vat.attribute_value AS seo_meta_description
                                        FROM vendor_attributes_text AS vat
                                        LEFT JOIN vendor_attributes AS va ON va.id = vat.vendor_attribute_id
                                        WHERE vat.vendor_id = '$queryResult->vendor_id'
                                        AND va.alias = 'seo_meta_description'");

           //reading vendor-cuisine
            $seo_meta_keywords  = DB::select("SELECT vat.attribute_value AS seo_meta_keywords
                                  FROM vendor_attributes_text AS vat
                                  LEFT JOIN vendor_attributes AS va ON va.id = vat.vendor_attribute_id
                                  WHERE vat.vendor_id = '$queryResult->vendor_id'
                                  AND va.alias = 'seo_meta_keywords'");

            //reading vendor-cuisine
            $seo_title = DB::select("SELECT vat.attribute_value AS seo_title
                                  FROM vendor_attributes_text AS vat
                                  LEFT JOIN vendor_attributes AS va ON va.id = vat.vendor_attribute_id
                                  WHERE vat.vendor_id = '$queryResult->vendor_id'
                                  AND va.alias = 'seo_title'");
            //print_r($queryResult);die;
            $special_offer_title =  DB::select("SELECT vat.attribute_value AS special_offer_title
                                  FROM vendor_location_attributes_text AS vat
                                  LEFT JOIN vendor_attributes AS va ON va.id = vat.vendor_attribute_id
                                    WHERE vat.vendor_location_id = '$queryResult->vl_id'
                                  AND va.alias = 'special_offer_title'");



            $special_offer_desc =  DB::select("SELECT vat.attribute_value AS special_offer_desc
                                  FROM vendor_location_attributes_text AS vat
                                  LEFT JOIN vendor_attributes AS va ON va.id = vat.vendor_attribute_id
                                    WHERE vat.vendor_location_id = '$queryResult->vl_id'
                                  AND va.alias = 'special_offer_desc'");
            //print_r($special_offer_desc);die;

         /* $seoDetails = array('seo_meta_description' =>$seo_meta_description,
                               'seo_meta_keywords'=>$seo_meta_keywords,'seo_title'=>$seo_title);
          print_r($seo_title);
          echo $seo_meta_description['0']->seo_meta_description;*/

          if(empty($seo_meta_description))
          {
            $seoMetaDesc = '';
          }
          else
          {
              $seoMetaDesc = $seo_meta_description['0']->seo_meta_description;
          }

          if(empty($special_offer_title))
          {
            $specialOfferTitle = '';
          }
          else
          {
            $specialOfferTitle = $special_offer_title[0]->special_offer_title;
          }

            if(empty($special_offer_desc))
            {
                $specialOfferDesc = '';
            }
            else
            {
                $specialOfferDesc = $special_offer_desc[0]->special_offer_desc;
            }

          //print_r($specialOffer);die;

           if(empty($seo_meta_keywords))
          {
            $seoMetaKey = '';
          }
          else
          {
              $seoMetaKey = $seo_meta_keywords['0']->seo_meta_keywords;
          }

          if(empty($seo_title))
          {
            $seoTitle = '';
          }
          else
          {
              $seoTitle = $seo_title['0']->seo_title;
          }
          /*exit;*/

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
                                    'vl_status' => $queryResult->vl_status,
                                    'resturant_information' => $queryResult->resturant_info,
                                    'short_description' => $queryResult->short_description,
                                    'terms_and_condition' => $queryResult->terms_conditions,
                                    'seo_meta_description' => $seoMetaDesc,
                                    'seo_meta_keywords' => $seoMetaKey,
                                    'seo_title' => $seoTitle,
                                    'pricing' => $queryResult->pricing_level,
                                    'image' => $arrImage,
                                    'rating' => (array_key_exists($queryResult->vl_id, $arrReview)) ? $arrReview[$queryResult->vl_id]['averageRating']:0,
                                    'review_count' => (array_key_exists($queryResult->vl_id, $arrReview)) ? $arrReview[$queryResult->vl_id]['totalRating']:0,
                                    'cuisine' => (array_key_exists($queryResult->vl_id, $arrVendorCuisine)) ? $arrVendorCuisine[$queryResult->vl_id]:array(),
                                    'cuisineID' => (array_key_exists($queryResult->vl_id, $arrVendorCuisineID)) ? $arrVendorCuisineID[$queryResult->vl_id]:array(),
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
                                    'expert_tips' => (is_null($queryResult->expert_tips)) ? "" : $queryResult->expert_tips,
                                    'special_offer_title' => $specialOfferTitle,
                                    'special_offer_desc' => $specialOfferDesc,
                                    'curator_tips' => (is_null($queryResult->curator_tips)) ? "" : $queryResult->curator_tips
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

    public static function getRestaurantCuisineID($arrVendorLocation) {
        //query to read cuisines
        $queryResult = DB::table(DB::raw('vendor_locations as vl'))
            ->join(DB::raw('vendor_location_attributes_multiselect as vlam'),'vlam.vendor_location_id','=','vl.id')
            ->join(DB::raw('vendor_attributes_select_options as vaso'), 'vaso.id', '=', 'vlam.vendor_attributes_select_option_id')
            ->join(DB::raw('vendor_attributes as va'),'va.id', '=', 'vaso.vendor_attribute_id')
            ->whereIn('vl.id', $arrVendorLocation)
            ->where('va.name','cuisines')
            ->orderBy('vl.id','desc')
            ->select('vaso.id as option_id','vl.id','vaso.option')
            ->get();

        //array to store vendor cuisine details
        $arrVendorCuisine = array();

        if($queryResult) {
            foreach($queryResult as $row) {
                if(!array_key_exists($row->id, $arrVendorCuisine)) {
                    $arrVendorCuisine[$row->id] = array();
                }
                $arrVendorCuisine[$row->id][] = $row->option_id;
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

    public static function getAlacarteLimit($vendorLocationID) {
     $queryResult = DB::table(DB::raw('vendor_locations as vl'))
              ->leftJoin(DB::raw('vendor_location_address as vla'), 'vla.vendor_location_id', '=', 'vl.id')
              ->join('locations', 'locations.id', '=', 'vla.area_id')
              ->leftJoin('vendor_locations_limits as vll', 'vll.vendor_location_id', '=', 'vl.id')
              ->where('vl.id', $vendorLocationID)
              ->select('vl.id', 'locations.name as area', 'vla.latitude',
                    'vla.longitude', 'vll.min_people_per_reservation',
                    'vll.max_people_per_reservation', 'vll.min_people_increments')
              ->get();

      //array to read the locations and limits
      $arrLocLmt = array();

      foreach ($queryResult as $row) {
        $arrLocLmt[$row -> id] = array(
                  'vl_id' => $row -> id,
                  'area' => $row -> area,
                  'min_people' => (is_null($row -> min_people_per_reservation)) ? '' : $row -> min_people_per_reservation,
                  'max_people' => (is_null($row -> max_people_per_reservation)) ? '' : $row -> max_people_per_reservation,
                  'increment' => (is_null($row -> min_people_increments)) ? '' : $row -> min_people_increments,
                  'latitude' => $row -> latitude,
                  'longitude' => $row -> longitude,
                );
      }

      return $arrLocLmt;
  }



  public static function getAlacarteBlockDates($vendor_location_id)
  {
       //query to read all the block dates for the locations
      $queryResult = DB::table(DB::raw('vendor_location_blocked_schedules'))
              ->where('vendor_location_id',$vendor_location_id)
              ->select('id','vendor_location_id','block_date')
              ->get();
      //array to store the block dates
      $arrBlockedDate = array();

      foreach($queryResult as $row){
        $formatted_date = '';
        if(!empty($row->block_date))
        {
          //$formatted_date =  date('m-d-Y',strtotime($row->block_date));
            $formatted_date =  date('Y-m-d',strtotime($row->block_date));
        }

        if(!array_key_exists($row->vendor_location_id, $arrBlockedDate)) {
          $arrBlockedDate[$row->vendor_location_id] = array($formatted_date);
        }
        else {
          $arrBlockedDate[$row->vendor_location_id][] = $formatted_date;
        }
      }

      return $arrBlockedDate;

  }

  public static function getAlacarteLocationSchedule($vendorLocationID) {
    //initializing the value of day
    //$day = (is_null($day)) ? strtolower(date("D")) : strtolower($day);

     $schedules = DB::table('schedules')
            ->join(DB::raw('time_slots as ts'),'ts.id','=','schedules.time_slot_id')
            ->join(DB::raw('vendor_location_booking_schedules as vlbs'),'vlbs.schedule_id','=','schedules.id')
            ->where('vlbs.vendor_location_id', $vendorLocationID)
            ->select('vlbs.vendor_location_id','schedules.day_short','schedules.id','ts.time','ts.slot_type')
            ->get();

    #array to hold information
    $arrData = array();

    if($schedules) {
      foreach($schedules as $row) {

        $arrData[$row->vendor_location_id][$row->day_short][$row->slot_type][$row->id] = date('g:i A',strtotime($row->time));

      }
    }
    return $arrData;
  }

    public static function getAlacarteLocationScheduleDays($vendorLocationID) {
    //initializing the value of day
    //$day = (is_null($day)) ? strtolower(date("D")) : strtolower($day);

     $schedules = DB::table('schedules')
            ->join(DB::raw('time_slots as ts'),'ts.id','=','schedules.time_slot_id')
            ->join(DB::raw('vendor_location_booking_schedules as vlbs'),'vlbs.schedule_id','=','schedules.id')
            ->where('vlbs.vendor_location_id', $vendorLocationID)
            ->select('vlbs.vendor_location_id','schedules.day_short','schedules.day_numeric','schedules.id','ts.time','ts.slot_type')
            ->get();

    #array to hold information
    $arrData = array();

    if($schedules) {
      foreach($schedules as $row) {

        $arrData[$row->day_short] = $row->day_numeric;

      }
    }
    return $arrData;
  }

    /*
     * This function removes the blockdates and only returns available dates for next 2 months
     * */
    public function getAvailableDates($blockedDates,$scheduleDays) {
        //echo "<pre>"; print_r($blockedDates); print_r($scheduleDays); //die;
        $d1= date('Y-m-d');
        $d2= strtotime(date("Y-m-d").' +2 Months');
        $d2= date('Y-m-d', $d2);
        $begin = new \DateTime($d1);
        $end = new \DateTime($d2);
        $daterange = new \DatePeriod($begin, new \DateInterval('P1D'), $end);

        $dates = array();
        $arrAvailableDates = array();
        foreach($daterange as $date){
            $dates[] = $date->format("Y-m-d");
        }
        //echo "<pre>"; print_r($dates); die;
        if(!empty($blockedDates)){ //echo "null";
            foreach ($blockedDates as $key => $value) {
                //echo $key." , ".$value;
                if(!empty($value) && $value[0] != ""){
                    foreach($value as $blockDate){
                        $checkBlockDate = array_search($blockDate, $dates);

                        if($checkBlockDate!==false)
                            unset($dates[$checkBlockDate]);
                    }

                    $arrAvailableDates = $this->sortByDays($dates,$scheduleDays);
                }else{
                    $arrAvailableDates = $this->sortByDays($dates,$scheduleDays);
                }

            }
        } else { //echo "not null";
            $arrAvailableDates = $this->sortByDays($dates,$scheduleDays);
        }

        //echo "<pre>"; print_r($arrAvailableDates); die;
        return $arrAvailableDates;
    }

    protected function sortByDays($dates,$scheduleDays){
        $finalDates = array();
        //echo "<pre>as"; print_r($scheduleDays);
        foreach($dates as $date){
            if(in_array(date('N',strtotime($date)),$scheduleDays)){
                $finalDates[] = date('Y-n-j',strtotime($date));
            }
        }
        //echo "<pre>fd"; print_r($finalDates);//die;
        return $finalDates;
    }
    //function to get related experiences which are active and allocated to selected vendor_location_id
    public static function getRelatedExperiences($aLaCarteID,$city_id){

        $samebrand = DB::table('products as p')
            ->leftJoin('product_attributes_text AS pat','pat.product_id','=','p.id')
            ->leftJoin('product_attributes AS pa','pa.id','=','pat.product_attribute_id')
            ->leftJoin('product_pricing AS pp','pp.product_id','=','p.id')
            ->leftJoin('product_reviews AS pr','pr.product_id','=','p.id')
            ->leftJoin('price_types AS pt','pt.id','=','pp.price_type')
            ->leftJoin('product_media_map AS pmm','pmm.product_id','=','p.id')
            ->leftJoin('media_resized_new AS mrn','mrn.media_id','=','pmm.media_id')
            ->leftJoin('product_flag_map as pfm','pfm.product_id','=','p.id')
            ->leftJoin('flags as f','pfm.flag_id','=','f.id')
            ->leftJoin('product_vendor_locations as pvl','pvl.product_id','=','p.id')
            ->leftJoin('vendor_locations as vl','vl.id','=','pvl.vendor_location_id')
            ->leftJoin('vendor_location_address as vla','vla.vendor_location_id','=','pvl.vendor_location_id')
            ->leftJoin('locations as l','l.id','=','vla.city_id')
            ->where('pvl.vendor_location_id',$aLaCarteID)
            ->where('mrn.image_type','listing')
            ->where('vla.city_id',$city_id)
            ->where('pvl.status','Active')
            ->groupBy('p.id')
            ->limit(2)
            ->select('vl.vendor_id',
                'p.name AS productname',
                'p.slug AS slug',
                'pa.name as productattrname',
                'pp.price',
                'pt.type_name',
                'mrn.file',
                'f.name as flagname',
                'f.color',
                'l.name as cityname',
                'p.id as product_id',
                'pvl.vendor_location_id',
                DB::raw('MAX(IF(pa.alias = "short_description", pat.attribute_value, "")) AS short_description'),
                DB::raw('AVG(pr.rating) as avg_rating, COUNT(*) as total_ratings,pr.product_id as product_review_id')
            )
            ->orderByRaw("RAND()")
            //->select('vl.vendor_id','p.name AS productname','p.slug AS slug','pat.attribute_value','pa.name as productattrname','pp.price','pt.type_name','mrn.file','f.name as flagname','f.color','p.id','l.name as cityname')->max("(IF(pa.alias = 'short_description', pat.attribute_value, NULL)) AS short_description")
            ->get();
        $relatedExperiencesArray = array();
        if(!empty($samebrand)){
            foreach ($samebrand as $values) {

                $num_of_full_starts = round($values->avg_rating,1);// number of full stars
                $num_of_half_starts     = $num_of_full_starts-floor($num_of_full_starts); //number of half stars
                $number_of_blank_starts = 5-($values->avg_rating); //number of white stars

                if (count($relatedExperiencesArray) == 3) {
                    break;
                } else {
                    $relatedExperiencesArray[] = array('productname' => $values->productname,
                        'slug' => $values->slug,
                        'short_description' => $values->short_description,
                        'price' => $values->price,
                        'type_name' => $values->type_name,
                        'file' => $values->file,
                        'flagname' => $values->flagname,
                        'color' => $values->color,
                        'cityname' => $values->cityname,
                        'averageRating' => $values->avg_rating,
                        'totalRating' => $values->total_ratings,
                        'full_stars' => $num_of_full_starts,
                        'half_stars' => $num_of_half_starts,
                        'blank_stars' => $number_of_blank_starts,
                        'product_id' => $values->product_id,
                        'vendor_location_id' => $values->vendor_location_id,
                        'vendor_id' => $values->vendor_id,
                    );
                }
            }
        }

        return $relatedExperiencesArray;
    }

    //function to get related restaurants on detail page
    public static function getRelatedRestaurants($vendorLocationID,$city_id,$cuisineid) {
        //echo "vendorLocationID = ".$vendorLocationID." , city_id = ".$city_id." , cuisineid<pre>"; print_r($cuisineid); die;
        $query = DB::table('vendor_locations as vl')
            ->leftJoin('vendor_location_address as vla','vla.vendor_location_id','=','vl.id')
            ->where('vl.id',$vendorLocationID)
            ->where('vla.city_id',$city_id)
            //->where('pvl.status','Active')
            ->select('vl.id as vendor_location_id','vl.vendor_id','vl.pricing_level','vla.area_id')
            ->get();

        //echo "<pre>"; print_r($query); //die;

        //checking restaurants with cuisines,price, and area_id
        $condition1 = DB::table('vendors AS v')
            ->leftJoin('vendor_locations as vl','vl.vendor_id','=','v.id')
            ->leftJoin('vendor_location_attributes_multiselect AS vlams','vlams.vendor_location_id','=','vl.id')
            ->leftJoin('vendor_attributes_select_options AS vaso','vaso.id','=','vlams.vendor_attributes_select_option_id')
            ->leftJoin('vendor_attributes AS va','va.id','=','vaso.vendor_attribute_id')
            ->leftJoin('vendor_locations_media_map AS vlmm','vlmm.vendor_location_id','=','vl.id')
            ->leftJoin('media_resized_new AS mrn','mrn.media_id','=','vlmm.media_id')
            ->leftJoin('vendor_location_address as vla','vla.vendor_location_id','=','vl.id')
            ->leftJoin('locations as l','l.id','=','vla.city_id')
            ->leftJoin('locations as loc','loc.id','=','vla.area_id')
            ->where('vlams.vendor_attributes_select_option_id',$cuisineid[0])
            ->where('vl.pricing_level',$query[0]->pricing_level)
            ->where('vla.area_id',$query[0]->area_id)
            ->whereNotIn('vl.id',array($query[0]->vendor_location_id))
            ->where('mrn.image_type','listing')
            ->where('vla.city_id',$city_id)
            ->where('vl.status','Active')
            ->where('vl.a_la_carte','=',1)
            //->groupBy('p.id')
            ->limit(1)
            ->select('vl.vendor_id',
                'v.name AS restaurantName',
                'vl.slug AS slug',
                'vl.pricing_level',
                'mrn.file',
                'l.name as cityname',
                'vl.id as vendor_location_id',
                'loc.name as area_name',
                'vaso.option as cuisine'
            )
            ->get();
        //echo "<br/>----<pre>1"; print_r($condition1); die;
        $relatedRestaurantsArray = array();
        if(!empty($condition1)){

            $relatedRestaurantsArray[] = array('restaurantName' => $condition1[0]->restaurantName,
                'slug' => $condition1[0]->slug,
                'price' => $condition1[0]->pricing_level,
                'file' => $condition1[0]->file,
                'cityname' => $condition1[0]->cityname,
                'vendor_location_id' => $condition1[0]->vendor_location_id,
                'vendor_id' => $condition1[0]->vendor_id,
                'area_name' => $condition1[0]->area_name,
                'cuisine' => $condition1[0]->cuisine,
            );
        }
        //product_id, vendor_location_id and vendor_id should not be which exist in $relatedExperiencesArray
        $c2VLID = array($query[0]->vendor_location_id);
        $c2VID = array($query[0]->vendor_id);
        if(!empty($relatedRestaurantsArray)){
            foreach($relatedRestaurantsArray as $v1){
                array_push($c2VLID,$v1['vendor_location_id']);
                array_push($c2VID,$v1['vendor_id']);
                //echo "<pre>"; print_r($v1);
            }
        }
        //echo "<pre>c2asd"; print_r($c2VLID);print_r($c2VID); //die;
        //echo "<pre>c22"; print_r($c2ProductID);
        //checking restaurants with cuisines and price
        $condition2 = DB::table('vendors AS v')
            ->leftJoin('vendor_locations as vl','vl.vendor_id','=','v.id')
            ->leftJoin('vendor_location_attributes_multiselect AS vlams','vlams.vendor_location_id','=','vl.id')
            ->leftJoin('vendor_attributes_select_options AS vaso','vaso.id','=','vlams.vendor_attributes_select_option_id')
            ->leftJoin('vendor_attributes AS va','va.id','=','vaso.vendor_attribute_id')
            ->leftJoin('vendor_locations_media_map AS vlmm','vlmm.vendor_location_id','=','vl.id')
            ->leftJoin('media_resized_new AS mrn','mrn.media_id','=','vlmm.media_id')
            ->leftJoin('vendor_location_address as vla','vla.vendor_location_id','=','vl.id')
            ->leftJoin('locations as l','l.id','=','vla.city_id')
            ->leftJoin('locations as loc','loc.id','=','vla.area_id')
            ->where('vlams.vendor_attributes_select_option_id',$cuisineid[0])
            ->where('vl.pricing_level',$query[0]->pricing_level)
            //->where('vla.area_id',$query[0]->area_id)
            ->whereNotIn('vl.id',$c2VLID)
            ->whereNotIn('v.id',$c2VID)
            ->where('mrn.image_type','listing')
            ->where('vla.city_id',$city_id)
            ->where('vl.status','Active')
            ->where('vl.a_la_carte','=',1)
            //->groupBy('p.id')
            ->limit(1)
            ->select('vl.vendor_id',
                'v.name AS restaurantName',
                'vl.slug AS slug',
                'vl.pricing_level',
                'mrn.file',
                'l.name as cityname',
                'vl.id as vendor_location_id',
                'loc.name as area_name',
                'vaso.option as cuisine'
            )
            ->get();
        //echo "<br/>----<pre>2"; print_r($condition2); //die;
        if(!empty($condition2)){

            $relatedRestaurantsArray[] = array('restaurantName' => $condition2[0]->restaurantName,
                'slug' => $condition2[0]->slug,
                'price' => $condition2[0]->pricing_level,
                'file' => $condition2[0]->file,
                'cityname' => $condition2[0]->cityname,
                'vendor_location_id' => $condition2[0]->vendor_location_id,
                'vendor_id' => $condition2[0]->vendor_id,
                'area_name' => $condition2[0]->area_name,
                'cuisine' => $condition2[0]->cuisine,
            );
        }
        //echo "<pre>"; print_r($relatedRestaurantsArray); //die;
        //product_id, vendor_location_id and vendor_id should not be which exist in $relatedExperiencesArray

        $c3VLID = array($query[0]->vendor_location_id);
        $c3VID = array($query[0]->vendor_id);
        if(!empty($relatedRestaurantsArray)){
            foreach($relatedRestaurantsArray as $v1){
                array_push($c3VLID,$v1['vendor_location_id']);
                array_push($c3VID,$v1['vendor_id']);
            }
        }
        //echo "<pre>c3"; print_r($c3VLID);print_r($c3VID); //die;

        //checking restaurants with location_id
        $condition3 = DB::table('vendors AS v')
            ->leftJoin('vendor_locations as vl','vl.vendor_id','=','v.id')
            ->leftJoin('vendor_location_attributes_multiselect AS vlams','vlams.vendor_location_id','=','vl.id')
            ->leftJoin('vendor_attributes_select_options AS vaso','vaso.id','=','vlams.vendor_attributes_select_option_id')
            ->leftJoin('vendor_attributes AS va','va.id','=','vaso.vendor_attribute_id')
            ->leftJoin('vendor_locations_media_map AS vlmm','vlmm.vendor_location_id','=','vl.id')
            ->leftJoin('media_resized_new AS mrn','mrn.media_id','=','vlmm.media_id')
            ->leftJoin('vendor_location_address as vla','vla.vendor_location_id','=','vl.id')
            ->leftJoin('locations as l','l.id','=','vla.city_id')
            ->leftJoin('locations as loc','loc.id','=','vla.area_id')
            //->where('vlams.vendor_attributes_select_option_id',$cuisineid[0])
            //->where('vl.pricing_level',$query[0]->pricing_level)
            ->where('vla.area_id',$query[0]->area_id)
            ->whereNotIn('vl.id',$c2VLID)
            ->whereNotIn('v.id',$c2VID)
            ->where('mrn.image_type','listing')
            ->where('vla.city_id',$city_id)
            ->where('vl.status','Active')
            ->where('vl.a_la_carte','=',1)
            //->groupBy('p.id')
            //->limit(1)
            ->select('vl.vendor_id',
                'v.name AS restaurantName',
                'vl.slug AS slug',
                'vl.pricing_level',
                'mrn.file',
                'l.name as cityname',
                'vl.id as vendor_location_id',
                'loc.name as area_name',
                'vaso.option as cuisine'
            )
            ->orderByRaw("RAND()")
            ->get();
        //echo "<br/>----<pre>3"; print_r($condition3); //die;
        if(count($relatedRestaurantsArray) != 3) {
            if(!empty($condition3)){
                foreach ($condition3 as $values) {

                    if (count($relatedRestaurantsArray) == 3) {
                        break;
                    } else {
                        $relatedRestaurantsArray[] = array('restaurantName' => $values->restaurantName,
                            'slug' => $values->slug,
                            'price' => $values->pricing_level,
                            'file' => $values->file,
                            'cityname' => $values->cityname,
                            'vendor_location_id' => $values->vendor_location_id,
                            'vendor_id' => $values->vendor_id,
                            'area_name' => $values->area_name,
                            'cuisine' => $values->cuisine,
                        );
                    }
                }
            }
        }

        //product_id, vendor_location_id and vendor_id should not be which exist in $relatedExperiencesArray
        $c4VLID = array($query[0]->vendor_location_id);
        $c4VID = array($query[0]->vendor_id);
        if(!empty($relatedExperiencesArray)){
            foreach($relatedExperiencesArray as $v1){
                array_push($c4VLID,$v1['vendor_location_id']);
                array_push($c4VID,$v1['vendor_id']);
            }
        }
        //echo "<pre>c4"; print_r($c4ProductID);print_r($c4VLID);print_r($c4VID);
        //checking restaurants with cuisine or areaid or price just in case if the above all conditions return null results
        $condition4 = DB::table('vendors AS v')
            ->leftJoin('vendor_locations as vl','vl.vendor_id','=','v.id')
            ->leftJoin('vendor_location_attributes_multiselect AS vlams','vlams.vendor_location_id','=','vl.id')
            ->leftJoin('vendor_attributes_select_options AS vaso','vaso.id','=','vlams.vendor_attributes_select_option_id')
            ->leftJoin('vendor_attributes AS va','va.id','=','vaso.vendor_attribute_id')
            ->leftJoin('vendor_locations_media_map AS vlmm','vlmm.vendor_location_id','=','vl.id')
            ->leftJoin('media_resized_new AS mrn','mrn.media_id','=','vlmm.media_id')
            ->leftJoin('vendor_location_address as vla','vla.vendor_location_id','=','vl.id')
            ->leftJoin('locations as l','l.id','=','vla.city_id')
            ->leftJoin('locations as loc','loc.id','=','vla.area_id')
            ->whereNotIn('vl.id',$c2VLID)
            ->whereNotIn('v.id',$c2VID)
            ->where('mrn.image_type','listing')
            ->where('vla.city_id',$city_id)
            ->where('vl.status','Active')
            ->where('vl.a_la_carte','=',1)
            //->groupBy('p.id')
            //->limit(1)
            ->select('vl.vendor_id',
                'v.name AS restaurantName',
                'vl.slug AS slug',
                'vl.pricing_level',
                'mrn.file',
                'l.name as cityname',
                'vl.id as vendor_location_id',
                'loc.name as area_name',
                'vaso.option as cuisine'
            )
            ->orderByRaw("RAND()")
            ->get();
        //echo "<br/>----<pre>4"; print_r($condition4);
        if(count($relatedRestaurantsArray) != 3) {
            if(!empty($condition4)){
                foreach ($condition4 as $values) {

                    if (count($relatedRestaurantsArray) == 3) {
                        break;
                    } else {
                        $relatedRestaurantsArray[] = array('restaurantName' => $values->restaurantName,
                            'slug' => $values->slug,
                            'price' => $values->pricing_level,
                            'file' => $values->file,
                            'cityname' => $values->cityname,
                            'vendor_location_id' => $values->vendor_location_id,
                            'vendor_id' => $values->vendor_id,
                            'area_name' => $values->area_name,
                            'cuisine' => $values->cuisine,
                        );
                    }
                }
            }
        }
        //echo "<pre>ar"; print_r($relatedExperiencesArray); die;
        return $relatedRestaurantsArray;
    }


  public static function checkBookingTimeRangeLimits($arrData) {
     $queryResult = DB::table('vendor_location_booking_time_range_limits')
              ->where('vendor_location_id',$arrData['vendorLocationID'])
              ->where('day',$arrData['reservationDay'])
              ->orWhere('date',$arrData['reservationDate'])
              ->get();

    //array to save the details
    $arrData = array();

    foreach($queryResult as $row) {
       $arrData[] = array(
                'id' => $row->id,
                'product_vendor_location_id' => $row->product_vendor_location_id,
                'limit_by' => $row->limit_by,
                'day' => $row->day,
                'date' => $row->date,
                'start_time' => $row->start_time,
                'end_time' => $row->end_time,
                'max_covers_limit' => $row->max_covers_limit,
                'max_tables_limit' => $row->max_tables_limit
              );

    }
    return $arrData;
  }

  public static function getReservationCount($arrData) {
    $queryResult = DB::table('reservation_details')
              ->where('vendor_location_id',$arrData['vendorLocationID'])
            ->where('reservation_date',$arrData['reservationDate'])
            ->where('reservation_type',$arrData['reservationType'])
            ->whereIn('reservation_status',array('new','edited'))
            ->groupBy('vendor_location_id')
            ->select(\DB::raw('SUM(no_of_persons) as person_count'))
            ->first();

    if($queryResult) {
      return $queryResult->person_count;
    }
    return 0;
  }

  public static function validateReservationData($arrData) {
    //array to store response
    $arrResponse = array();

    //checking the availability for the booking
    $arrTimeRangeLimits = Self::checkBookingTimeRangeLimits($arrData);
    $existingReservationCount = Self::getReservationCount($arrData);

    //converting the reservation time
    $reservationTime = strtotime($arrData['reservationTime']);

    if (!empty($arrTimeRangeLimits)) {
      foreach ($arrTimeRangeLimits as $key => $value) {
        $maxCount = ($value['max_covers_limit'] == 0) ? $value['max_tables_limit'] : $value['max_covers_limit'];

        $startTime = strtotime($value['start_time']);
        $endTime = strtotime($value['end_time']);

        if ($startTime <= $reservationTime && $endTime >= $reservationTime) {
          if ($maxCount == $existingReservationCount) {
            $arrResponse['status'] = 'error';
            $arrResponse['error'] = 'Sorry. Currently the place is full. Please try another day.';
            return $arrResponse;
          } else if ($maxCount > $existingReservationCount) {
            if (($maxCount - ($existingReservationCount + $arrData['partySize'])) < 0) {
              $arrResponse['status'] = 'error';
              $arrResponse['error'] = "Sorry. We have only " . abs($maxCount - $existingReservationCount) . ' seats available.';
              return $arrResponse;
            }
          }
        }
      }
    }

    $arrResponse['status'] = 'success';
    return $arrResponse;
  }

  public static function addReservationDetails($arrData, $userID) {

    $reservation = array();

    //initializing the data
    $reservation['reservation_status'] = 'new';
    $reservation['reservation_date'] = $arrData['reservationDate'];
    $reservation['reservation_time'] = $arrData['reservationTime'];
    $reservation['no_of_persons'] = $arrData['partySize'];
    $reservation['guest_name'] = $arrData['guestName'];
    $reservation['guest_email'] = $arrData['guestEmail'];
    $reservation['guest_phone'] = $arrData['phone'];
    $reservation['reservation_type'] = $arrData['reservationType'];
    $reservation['order_amount'] = 0;
    $reservation['user_id'] = $userID;

    //setting up the variables that may be present
    if(isset($arrData['specialRequest'])) {
      $reservation['special_request'] = $arrData['specialRequest'];
    }

    if(isset($arrData['addedBy'])) {
      $reservation['added_by'] = $arrData['addedBy'];
    }

    if(isset($arrData['giftCardID'])) {
      $reservation['giftcard_id'] = $arrData['giftCardID'];
    }

    //reading the product detail
    $aLaCarteDetail = self::readVendorDetailByLocationID($arrData['vendorLocationID']);
    $reservation['points_awarded']             = isset($aLaCarteDetail['reward_point'])?$aLaCarteDetail['reward_point']:'0';
    $reservation['vendor_location_id']         = $arrData['vendorLocationID'];
    $reservation['product_vendor_location_id'] = 0;
    #saving the information into the DB
    $reservationId = DB::table('reservation_details')->insertGetId($reservation);

    if($reservationId) {

      $arrResponse['status'] = 'success';
      //$arrResponse['data']['name'] = isset($productDetail['name'])?$productDetail['name']:'';
      //$arrResponse['data']['url'] = URL::to('/').'/experiences/'.$productDetail['id'];
      $arrResponse['data']['reservationDate'] = $arrData['reservationDate'];
      $arrResponse['data']['reservationTime'] = $arrData['reservationTime'];
      $arrResponse['data']['partySize'] = $arrData['partySize'];
      $arrResponse['data']['reservationID'] = $reservationId;
      $arrResponse['data']['reservation_type'] = "A la carte";
      //$arrResponse['data']['reward_point'] = $productDetail['reward_point'];
      return $arrResponse;
    }

    return FALSE;
  }

   public static function readVendorDetailByLocationID($vendorLocationID) {
    //array to store the data
    $arrData = array();

    $queryResult = \DB::table('vendors')
            ->join('vendor_locations as vl','vl.vendor_id','=','vendors.id')
            ->leftJoin('vendor_location_attributes_integer as vai','vai.vendor_location_id','=','vl.id')
            ->join('vendor_attributes as va','va.id','=','vai.vendor_attribute_id')
            ->where('vl.id',$vendorLocationID)
            ->where('va.alias','reward_points_per_reservation')
            ->select('vendors.id','vendors.name','vai.attribute_value as reward_point')
            ->first();
    if($queryResult) {
      $arrData['id'] = $queryResult->id;
      $arrData['name'] = $queryResult->name;
      $arrData['reward_point'] = (empty($queryResult->reward_point))? 0.00 : $queryResult->reward_point;
    }

    return $arrData;
  }

    public function getOutlet($vendorLocationID){
        $queryResult = \DB::table('vendor_locations as vl')
            ->leftJoin('locations as l','vl.location_id','=','l.id')
            ->leftJoin('vendors as v','vl.vendor_id','=','v.id')
            ->where('vl.id',$vendorLocationID)
            ->select('l.name', 'v.name as vendor_name','vl.vendor_id as vendor_id')
            ->first();


        return $queryResult;

    }

    public function getLocationDetails($vendorLocationID){
        $queryResult = \DB::table('vendor_locations as vl')
            ->leftJoin('vendor_location_address as vla','vl.id','=','vla.vendor_location_id')
            ->where('vl.id',$vendorLocationID)
            ->select('vla.address','vla.latitude','vla.longitude','vla.city_id')
            ->first();


        return $queryResult;
    }

}
