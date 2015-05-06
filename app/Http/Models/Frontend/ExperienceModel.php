<?php namespace WowTables\Http\Models\Frontend;
use DB;
use Config;
/**
 * Class User
 * @package WowTables\Http\Models
 */
class ExperienceModel {
 
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
    
    /**
     * Filters to be sent for search results.
     * 
     * @var   array
     * @access  protected
     * @since 1.0.0
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
     * @access  public
     * @return  array
     * @since 1.0.0
     */
    public function getExperienceSearchFilters() {
      return $this->filters;
    }
    
    //-------------------------------------------------------------

    
    /**
     * Reads the ratings of the vendors matching the vendors
     * in the passed array.
     *
     * @access  public
     * @static  true
     * @param array   $arrVendor
     * @return  array
     * @since 1.0.0
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
     * @access  public
     * @param $arrData
     * @since 1.0.0
     * @version 1.0.0
     */
    public function findMatchingExperience( $arrData ) {
      $experienceQuery = DB::table('products')
                ->join('product_attributes_varchar','product_attributes_varchar.product_id','=','products.id')
                ->join('product_attributes_text','product_attributes_text.product_id','=','products.id')
                ->leftJoin(DB::raw('product_media_map as pmm'), 'pmm.product_id','=','products.id')
                ->leftJoin('media','media.id','=','pmm.media_id')
                ->leftJoin(DB::raw('product_pricing as pp'),'pp.product_id','=','products.id')
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
                ->select('products.id',DB::raw('product_attributes_varchar.attribute_value as title, product_attributes_varchar.attribute_value as description,
                        pp.price, pp.price_type, pp.is_variable, pp.tax, pp.post_tax_price, media.file as image,
                        products.type as product_type, flags.name as flag_name,flags.color as flag_color, locations.id as location_id,
                        locations.name as location_name,products.slug'));


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
                          'slug' => $row->slug,
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
                          'full_stars' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['full_stars']:0,
                          'half_stars' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['half_stars']:0,
                          'blank_stars' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['blank_stars']:0,
                          "flag" => (is_null($row->flag_name)) ? "":$row->flag_name,
                          "color" => (is_null($row->flag_color)) ? "#fff":$row->flag_color,
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
     // echo '<pre>';print_r($arrData['data']);
      return $arrData;
    }

    //-----------------------------------------------------------------

    /**
     * Reads the ratings of the product matching the vendors
     * in the passed array.
     *
     * @access  public
     * @static  true
     * @param array   $arrProduct
     * @return  array
     * @since 1.0.0
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

        $num_of_full_starts = round($row->avg_rating,1);// number of full stars
        $num_of_half_starts     = $num_of_full_starts-floor($num_of_full_starts); //number of half stars
        $number_of_blank_starts = 5-($row->avg_rating); //number of white stars

        $arrRating[$row->product_id] = array(
                        'averageRating' => $row->avg_rating,
                        'totalRating' => $row->total_ratings,
                        'full_stars' => $num_of_full_starts,
                        'half_stars' => $num_of_half_starts,
                        'blank_stars' => $number_of_blank_starts
                        );
      }

     


      return $arrRating;
    }
    
    //-----------------------------------------------------------------

    /**
     * Method to validate the data submitted by
     * the user.
     *
     * @static  true
     * @access  public
     * @param array   $arrData
     * @return  array
     * @since 1.0.0
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
     * @access  public
     * @param array   $arrExperience
     * @return  array
     * @since 1.0.0
     */
    public function getExperienceImages($arrExperience) {
      //query to read media details
      $queryImages = DB::table('media_resized_new as mrn')
            ->leftJoin('product_media_map as pmm','pmm.media_id','=','mrn.media_id')
            ->whereIn('pmm.product_id',$arrExperience)
            ->where('pmm.media_type','listing')
            ->select('mrn.file as image','mrn.image_type','pmm.product_id')
            ->get();
      //array to store images
      $arrImage = array();
      if($queryImages) {
        foreach($queryImages as $row) {
          if(!array_key_exists($row->product_id, $arrImage)) {
            $arrImage[$row->product_id] = array();
          }
          if(in_array($row->image_type, array('listing'))) {
            $arrImage[$row->product_id][$row->image_type] = Config::get('constants.API_LISTING_IMAGE_URL').$row->image;
          }
          if($row->image_type = 'gallery') {
            $arrImage['gallery'][] = Config::get('constants.API_GALLERY_IMAGE_URL').$row->image;
          }
        }
      }
    
      return $arrImage;
    }

    public static function getProductImages($productID) {
    //query to read media details
    $queryImages = $queryImages = DB::table('media_resized_new as mrn')
            ->leftJoin('product_media_map as pmm','pmm.media_id','=','mrn.media_id')
            ->where('pmm.product_id',$productID)
            ->where('pmm.media_type','listing')
            ->select('mrn.file as image','mrn.image_type','pmm.product_id')
            ->get();
    
    //echo $queryImages->toSql();
    
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
  
  //-----------------------------------------------------------------
  
  /**
   * Initializes the value for the filters to be sent
   * with search results.
   * 
   * @access  public
   * @param array   $arrProduct
   * @since 1.0.0
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
    
    #setting up the cuisines filter information
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

  public function find($experienceID) {
    //query to read product type
    $queryType = DB::table('products')
            ->where('id',$experienceID)
            ->select('name','type')
            ->first();
    
    //query to read the experience detail
    $queryExperience = DB::table('products')
              ->leftJoin('product_attributes_text as pat1','pat1.product_id','=','products.id')
              ->leftJoin('product_attributes_text as pat2','pat2.product_id','=','products.id')
              ->leftJoin('product_attributes_text as pat4','pat4.product_id','=','products.id')
              ->leftJoin('product_attributes as pa1', 'pa1.id','=','pat1.product_attribute_id')
              ->leftJoin('product_attributes as pa2', 'pa2.id','=','pat2.product_attribute_id')
              ->leftJoin('product_attributes as pa4', 'pa4.id','=','pat4.product_attribute_id')
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
              ->where('products.id',$experienceID)
              ->where('pa1.alias','experience_info')
              ->where('pa2.alias','short_description')
              ->where('pa4.alias','terms_and_conditions');
    
    //adding additional parameters in case of simple experience
    if($queryType && $queryType->type == 'simple') {      
      $queryExperience->leftJoin(DB::raw('product_attributes_text as pat3'),'pat3.product_id','=','products.id')
              ->leftJoin(DB::raw('product_attributes as pa3'), 'pa3.id','=','pat3.product_attribute_id')
              ->where('pa3.alias','menu')
              ->select('products.id','products.name','products.slug','products.type','pp.price','pp.tax',
                  'pt.type_name as price_type', 'pp.is_variable','pp.post_tax_price', 
                  'pat1.attribute_value as experience_info', 'pat2.attribute_value as short_description', 
                  'media.file as experience_image', 'curators.name as curator_name', 
                  'curators.bio as curator_bio', 'curators.designation',
                  'pat3.attribute_value as menu','cm.file as curator_image',
                  'pat4.attribute_value as terms_and_condition',
                  'pvl.id as product_vendor_location_id');
              
    }
    else {
        $queryExperience->leftJoin(DB::raw('product_attributes_text as pat3'),'pat3.product_id','=','products.id')
              ->leftJoin(DB::raw('product_attributes as pa3'), 'pa3.id','=','pat3.product_attribute_id')
              ->where('pa3.alias','menu')
              ->select('products.id','products.name','products.slug','products.type','pp.price','pp.tax',
                  'pt.type_name as price_type', 'pp.is_variable','pp.post_tax_price', 
                  'pat1.attribute_value as experience_info','curators.name as curator_name', 
                  'curators.bio as curator_bio','curators.designation','pat2.attribute_value as short_description', 
                  'media.file as experience_image','cm.file as curator_image', 
                  'pat4.attribute_value as terms_and_condition', 'pvl.id as product_vendor_location_id');
    }

    //running the query to get the results
    //echo $queryExperience->toSql();
    $expResult = $queryExperience->first();   
    
    //array to store the experience details
    $arrExpDetails = array();
    
    if($expResult) {
      //getting the reviews for the particular experience
        $arrReviews = Self::readProductReviews($expResult->id);
        $arrCuisines = Self::getExperienceCuisine($expResult->id);
        $arrLocation = Self::getProductLocations($expResult->id, $expResult->product_vendor_location_id);
        $arrImage = Self::getProductImages($experienceID);      
        $arrExpDetails['data'] = array(
                    'id' => $expResult->id,
                    'name' => $expResult->name,
                    'slug'  => $expResult->slug,
                    'experience_info' => $expResult->experience_info,
                    'short_description' => $expResult->short_description,
                    'terms_and_condition' => $expResult->terms_and_condition,
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
                    'cuisine' => (array_key_exists($expResult->id, $arrCuisines)) ? $arrCuisines[$expResult->id]:array(),
                    'location' => $arrLocation,

                    'similar_option' => array(),
                  );
        
        
    }
    return $arrExpDetails;        
  }

  public static function getExperienceCuisine($exp_id) {
        //query to read cuisines
    $queryCuisine = DB::table('product_attributes_select_options as paso')
                ->join('product_attributes as pa','pa.id','=','paso.product_attribute_id')
                ->join('product_attributes_multiselect as pam','pam.product_attributes_select_option_id','=','paso.id')
                ->where('pa.alias','cuisines')
                ->where('pam.product_id',$exp_id)
                ->select('paso.id','paso.option','pam.product_id')
                ->get();
      
      $arrCuisines = array();

      if($queryCuisine) {
            foreach($queryCuisine as $row) {
                if(!array_key_exists($row->product_id, $arrCuisines)) {
                    $arrCuisines[$row->product_id] = array();
                }
                $arrCuisines[$row->product_id][] = $row->option;
            }
        }
        
        return $arrCuisines;
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
   * @static  true
   * @access  public
   * @param integer $productID
   * @return  array
   * @since 1.0.0
   */
  public static function getProductLocations($productID) {
    $queryResult =    DB::table('product_vendor_locations as pvl')  
              ->leftJoin(DB::raw('vendor_location_address as vla'),'vla.vendor_location_id','=','pvl.vendor_location_id')
              ->leftJoin('locations as l1', 'l1.id','=','vla.area_id')
              ->leftJoin('locations as l2', 'l2.id','=','vla.city_id')
              ->leftJoin('locations as l3', 'l3.id','=','vla.state_id')
              ->select('l1.name as area','l2.name as city','l3.name as state_name','vla.address','vla.pin_code','vla.latitude','vla.longitude')
              ->where('pvl.product_id',$productID)
              ->get();
    
    //array to hold location details
    $arrLocation = array();
    if($queryResult) {
      foreach($queryResult as $row) {

        $arrLocation[] = array(
                  "address_line" => $row->address,
                  "area" => $row->area,
                  "city" => $row->city,
                  "pincode" => $row->pin_code,
                  "state" => $row->state_name,                                                                
                  //"country" => $row->country,
                  "latitude" => $row->latitude,
                  "longitude" => $row->longitude 

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

  public static function readProductReviews($productID) {
    //query to read product reviews
    $strQuery = DB::table(DB::raw('product_reviews as pr'))
            ->join('users','users.id','=', 'pr.user_id')
            ->where('pr.product_id',$productID)
            ->where('pr.status','approved')
            ->select('users.id','users.full_name','pr.review','pr.rating','pr.created_at')
            ->get();
    
    $ratingCount = 0;
    $avgRating = 0;
    
    //array to store the result
    $arrReviewDetail = array();
    $arrReviewDetail['reviews'] = array();
    
    //initializing the results
    if($strQuery) {
      foreach($strQuery as $row) {
        if(!is_null($row->id)) {
          $ratingCount++;
          $avgRating += $row->rating;
          $arrReviewDetail['reviews'][] = array(
                          'id' => $row->id,
                          'name' => $row->full_name,
                          'image' => "",
                          'review' => $row->review,
                          'rating' => $row->rating,
                          'created_at' => $row->created_at
                        );
        }       
      }
      $arrReviewDetail['total_rating'] = $ratingCount;
      $arrReviewDetail['avg_rating'] = ($ratingCount == 0) ? $avgRating : number_format(($avgRating/$ratingCount),2,'.','');
    }
    else {
        $arrReviewDetail['avg_rating'] = 0.00;
        $arrReviewDetail['total_rating'] = 0.00;
        $arrReviewDetail['reviews'] = array();        
    }
    return $arrReviewDetail;    
  }

}