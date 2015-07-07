<?php namespace WowTables\Http\Controllers\Site;

use WowTables\Core\Repositories\Experiences\ExperiencesRepository;
use WowTables\Http\Controllers\Controller;
use App;
use Cookie;
use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\Page;
use Session;
use Config;
use Response;
use WowTables\Http\Models\Eloquent\Location;
use WowTables\Http\Models\Eloquent\Products\Product;
use WowTables\Http\Models\Frontend\CommonModel;
use WowTables\Http\Models\Frontend\ExperienceModel;
use Input;
use Validator;
use Hash;
use DB;
use Auth;
use Redirect;
use Mail;
use Mailchimp;
use WowTables\Http\Models\Profile;

class ExperienceController extends Controller {

    protected $listId = '986c01a26a';

	function __construct(Request $request, ExperienceModel $experiences_model, ExperiencesRepository $repository, Mailchimp $mailchimp){
        $this->request = $request;
        $this->experiences_model = $experiences_model;
        $this->repository = $repository;
        $this->mailchimp = $mailchimp;
    }

    function index(){
    	return "hello experience";
    }

     function details($city='',$expslug = ''){
        DB::connection()->enableQueryLog();

        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $data['cities'] = $cities;

        //$cities = DB::table('locations')->where('Type','City')->select('name','id','visible')->get();


        $check_userid = '`order`,id desc';
        $data['user']   = Auth::user();
        $city_name      = 'mumbai';
       
        if(!empty($data['user']))
        {
            $users_city     = $data['user']->location_id;
            $user_id        =  $data['user']->id;
            $city_name      = Location::where(['Type' => 'City', 'id' => $users_city])->pluck('name');
            if(empty($city_name))
            {
                $city_name = 'mumbai';
            }

            $check_userid = "if(bookmark_userid = ".$user_id.", 0, if(bookmark_userid != ".$user_id.", 1, 1)),`order` asc";
        }
        $city_id    = Location::where(['Type' => 'City', 'name' => $city])->first()->id;
        
        $id = DB::table('products')->where('slug',$expslug)->first()->id;

        $data['allow_guest']            ='Yes'; 
        $data['current_city']           = strtolower($city);
        $data['current_city_id']        = $city_id;

        //$arrSubmittedData['city_id']    = $city_id;
        $arrExperience                  = $this->experiences_model->find($id,$city_id);
        //echo "<pre>"; print_r($arrExperience);
        $data['arrExperience']          = $arrExperience;
        $data['reserveData']            = $this->experiences_model->getExperienceLimit($id);
        $data['block_dates']            = $this->experiences_model->getExperienceBlockDates($id);
        $data['schedule']               = $this->experiences_model->getExperienceLocationSchedule($id);
        //echo "city id = ".$city_id; die;

       /*echo '<pre>';
       //print_r( $time_range);
       print_r( $data['arrExperience']);
       //print_r(DB::getQueryLog());
       print_r( $data['reserveData']);
       print_r( $data['block_dates']);
       print_r( $data['schedule']);
       echo '</pre>';
      //  exit;*/

        $commonmodel = new CommonModel();
        $data['allCuisines']  = $commonmodel->getAllCuisines();
        $data['allAreas']  =   $commonmodel->getAllAreas();
        $data['allPrices']  = $commonmodel->getAllPrices();

        $data['dropdowns_opt']  = 1; //1 for disp

        $seo_title = $data['arrExperience']['data']['seo_title'];
        $meta_desc = $data['arrExperience']['data']['seo_meta_desciption'];
        $meta_keywords = $data['arrExperience']['data']['seo_meta_keywords'];
        if($seo_title=='')
        {
          $seoTitleDetails = 'WowTables : '.$data['arrExperience']['data']['name'];
        }
        else
        {
          $seoTitleDetails = $seo_title;
        }

        if($meta_desc=='')
        {
          $metaDescDetails = 'Reserve : '.$data['arrExperience']['data']['name']. 
                 'Exclusive curated set menus for fine dining. Find information, address,
                  maps, photos, menu and reviews';
        }
        else
        {
          $metaDescDetails = $meta_desc;
        }

        
        $meta_information = array('seo_title'      => $seoTitleDetails,
                                   'meta_desc'     => $metaDescDetails, 
                                   'meta_keywords' => $meta_keywords);

        $vendor_id = $arrExperience['data']['vendor_id'];

        $jump_to_ala_query = DB::select("SELECT vl.vendor_id, vl.location_id, vl.slug,l.name as area
                              FROM `vendor_locations` as vl
                              left join locations as l on l.id = vl.location_id
                              WHERE vl.vendor_id = '$vendor_id'
                              AND vl.a_la_carte = '1'
                              AND vl.status = 'Active'");

        $alacarte_jump = array();
        if(!empty($jump_to_ala_query))
        {
            foreach($jump_to_ala_query as $row)
            {
              $alacarte_jump[] = array("vendor_id"   =>$row->vendor_id,
                                       "location_id" =>$row->location_id,
                                       "slug"        =>$row->slug,
                                       "area"        =>$row->area);
            }
        }

        /*echo"<pre>";print_r($arrExperience['data']['location']);


        echo"<pre>";print_r($alacarte_jump);

       

        exit;*/
        /*echo"<pre>";print_r($alacarte_jump);
        echo"<pre>";print_r($data);
        exit;*/
        return view('frontend.pages.experiencedetails',$data)
                        ->with('meta_information', $meta_information)
                        ->with('alacarteJumpDetails', $alacarte_jump);
    }


    function lists($city='',$start_from=0,$areas='',$cousines='',$prices=''){
    	
    	//DB::connection()->enableQueryLog();
        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $data['cities'] = $cities;

        //$cities = DB::table('locations')->where('Type','City')->select('name','id','visible')->get();


        $check_userid = '`order`,id desc';
        $data['user']   = Auth::user();
        $city_name      = 'mumbai';
       
        if(!empty($data['user']))
        {
            $users_city     = $data['user']->location_id;
            $user_id        =  $data['user']->id;
            $city_name      = Location::where(['Type' => 'City', 'id' => $users_city])->pluck('name');
            if(empty($city_name))
            {
                $city_name = 'mumbai';
            }

            $check_userid = "if(bookmark_userid = ".$user_id.", 0, if(bookmark_userid != ".$user_id.", 1, 1)),`order` asc";
        }
       
        $areas_footer = $areas; 

        if($city == '')
        {
            if (Input::has('signup'))
            {
               if(!empty($data['user'])){
                     $redirect_url = '/'.strtolower($city_name).'/?signup=true';
                } else {
                    $redirect_url = '/mumbai/?signup=true';
                }
            } 
            else if(!empty($data['user']))
            {
                $redirect_url = '/'.strtolower($city_name);                
            } 
            else 
            {
               $redirect_url = "/mumbai";
            }  
            return redirect()->route('experience.lists',[$redirect_url]);
        }


        $city_id    = Location::where(['Type' => 'City', 'name' => $city])->first()->id;
        
        $arrSubmittedData['city_id'] = $city_id;

        $searchResult = $this->experiences_model->findMatchingExperience($arrSubmittedData);       
                
        if(!empty($searchResult)) {
            //setting up the array to be formatted as json
            $data['resultCount'] = $searchResult['resultCount'];
            $data['data'] = (array_key_exists('data', $searchResult)) ? $searchResult['data']:array();
            $data['filters'] = $this->experiences_model->getExperienceSearchFilters();
        }
        else {
            $data['resultCount'] = 0;
        }

        $data['ListpageSidebars']     = DB::select('SELECT ls.*,mrn.file as imagename FROM listpage_sidebar as ls LEFT JOIN media_resized_new as mrn ON ls.media_id = mrn.media_id WHERE city_id = '.$city_id.' AND show_in_experience = 1');
        $data['listpage_sidebar_url'] = Config::get('constants.LISTPAGE_SIDEBAR_WEB_URL');
        //echo "url = ".$data['listpage_sidebar_url'];
        //echo "<br/><pre>"; print_r($data['ListpageSidebars']); die;
        $data['allow_guest']='Yes'; 
        $data['current_city']  = strtolower($city);
        $data['current_city_id']  = $city_id;
       

        

        $commonmodel = new CommonModel();
        $data['allCuisines']  = $commonmodel->getAllCuisines();
        $data['allAreas']  =   $commonmodel->getAllAreas();
        $data['allPrices']  = $commonmodel->getAllPrices();

        $data['dropdowns_opt']  = 1; //1 for disp

        if(Input::get('ref')){
            $refid = Input::get('ref'); 
        } else {
            $refid = Cookie::get('referral');
        }    
        if (!empty($refid)) {
            //$data['referral'] = $this->partners_model->get_row_by_refid($refid);
        }


        return response()->view('frontend.pages.experiencelist',$data);
    }

   /**
     * Mumbai jain collection
     *
     * @param $city
     * @return view
     */
    public function collection($collection="")
    {

        //this code is start in header and footer page.
        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $arrResponse['cities'] = $cities;
        $arrResponse['user']   = Auth::user(); 
        $city_id    = Input::get('city');        
        $city_name      = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
        if(empty($city_name))
        {
            $city_name = 'mumbai';
        }

        $arrResponse['allow_guest']            ='Yes'; 
        $arrResponse['current_city']           = strtolower($city_name);
        $arrResponse['current_city_id']        = $city_id;

        $commonmodel = new CommonModel();
        $arrResponse['allCuisines']  = $commonmodel->getAllCuisines();
        $arrResponse['allAreas']  =   $commonmodel->getAllAreas();
        $arrResponse['allPrices']  = $commonmodel->getAllPrices();

        $arrResponse['dropdowns_opt']  = 1; //1 for disp
        //this code is start in header and footer page.
        $collectionQuery ="SELECT tags.`id`, tags.`name` , tags.`slug` , tags.`collection` , tags.`description` , tags.`seo_title` , tags.`seo_meta_description` , tags.`seo_meta_keywords` , 
                            media_resized_new.`file`, media_resized_new.`height`, media_resized_new.`width`, media_resized_new.`image_type`
                            FROM tags
                            INNER JOIN media_resized_new ON tags.`web_media_id` = media_resized_new.`media_id`
                            WHERE tags.`slug` = '$collection'
                            AND tags.`status` = 'available'";
        
        $collectionResult = DB::select($collectionQuery);
        //exclusiveexperiences query
        $exclusiveExperiences = DB::select("SELECT t.name, t.slug,p.name AS productname,p.slug AS slug, pat.attribute_value, 
                                            pa.name as productattrname, pp.price, pt.type_name, mrn.file,f.name as flagname,f.color,p.id,
                                            l.name as cityname,MAX(IF(pa.alias = 'short_description', pat.attribute_value, '')) AS short_description
                                            FROM tags AS t
                                            LEFT JOIN product_tag_map AS ptm ON t.id = ptm.tag_id
                                            LEFT JOIN products AS p ON p.id = ptm.product_id
                                            LEFT JOIN product_attributes_text AS pat ON pat.product_id = p.id
                                            LEFT JOIN product_attributes AS pa ON pa.id = pat.product_attribute_id
                                            LEFT JOIN product_pricing AS pp ON pp.product_id = p.id
                                            LEFT JOIN price_types AS pt ON pt.id = pp.price_type
                                            LEFT JOIN product_media_map AS pmm ON pmm.product_id = p.id
                                            LEFT JOIN media_resized_new AS mrn ON mrn.media_id = pmm.media_id
                                            LEFT JOIN product_flag_map as pfm on pfm.product_id = p.id
                                            LEFT JOIN flags as f on pfm.flag_id = f.id
                                            LEFT JOIN product_vendor_locations as pvl on pvl.product_id = p.id
                                            LEFT JOIN vendor_location_address as vla on vla.vendor_location_id= pvl.vendor_location_id
                                            LEFT JOIN locations as l on l.id = vla.city_id
                                            WHERE t.slug = '$collection'
                                            AND mrn.image_type = 'listing'
                                            AND t.status = 'available'
                                            group by p.id");
            
         // print_r($exclusiveExperiences);
          //exit;
            $arrData = array();
            //close code by product review and rating.
            $arrProduct = array('59','63','62');
             $arrRatings = $this->findRatingByProduct($arrProduct);
             foreach($exclusiveExperiences as $row) {
                
                $arrData['data'][]=array(
                          'name'=>$row->name,
                          'productname'=>$row->productname,
                          'slug'=>$row->slug,
                          'attribute_value'=>$row->attribute_value,
                          'short_description'=>$row->short_description,
                          'productattrname'=>$row->productattrname,
                          'price'=>$row->price,
                          'type_name'=>$row->type_name,
                          'file'=>$row->file,
                          'flagname'=>$row->flagname,
                          'color'=>$row->color,
                          'id'=>$row->id,
                          'cityname'=>strtolower($row->cityname),
                          'rating' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['averageRating']:0,
                          'total_reviews' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['totalRating']:0,
                          'full_stars' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['full_stars']:0,
                          'half_stars' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['half_stars']:0,
                          'blank_stars' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['blank_stars']:0,
                    );
             }

            
             //end exclusiveexperiences query
             $alaCartaArData = array();
             
             //start query a lart cart query
              $alacartQuery = DB::select("SELECT t.name AS tagsname, t.slug AS tagsslug, vl.slug AS vendorlocationslug,
                                         v.name AS vendorlocations,v.id, l.name AS city,l.slug, mrn.file AS imagename, 
                                         vl.pricing_level, vlat.attribute_value,f.name as flagname,
                                         f.color,la.name as locationarea,vaso.option
                                            FROM tags AS t
                                            INNER JOIN vendor_locations_tags_map AS vltm ON t.id = vltm.tag_id
                                            INNER JOIN vendor_locations AS vl ON vl.id = vltm.vendor_location_id
                                            INNER JOIN vendors AS v ON v.id = vl.vendor_id
                                            INNER JOIN vendor_location_address AS vla ON vla.vendor_location_id = vl.id
                                            INNER JOIN locations AS l ON l.id = vla.city_id
                                            INNER JOIN vendor_locations_media_map AS vlmm ON vlmm.vendor_location_id = vl.id
                                            INNER JOIN media_resized_new AS mrn ON mrn.media_id = vlmm.media_id
                                            INNER JOIN vendor_location_attributes_text AS vlat ON vlat.vendor_location_id = vl.id
                                            INNER JOIN vendor_attributes AS va ON va.id = vlat.vendor_attribute_id
                                            INNER JOIN vendor_locations_flags_map AS vlfm ON vlfm.vendor_location_id = vl.id
                                            INNER JOIN flags AS f ON f.id = vlfm.flag_id
                                            INNER JOIN locations AS la ON la.id = vla.area_id
                                            INNER JOIN vendor_location_attributes_multiselect AS vlam ON vlam.vendor_location_id = vl.id
                                            INNER JOIN vendor_attributes_select_options AS vaso ON vaso.id = vlam.vendor_attributes_select_option_id
                                            WHERE t.slug = '$collection'
                                            AND t.status = 'available'
                                            AND mrn.image_type = 'listing'
                                            AND va.alias = 'short_description'");
             /*print_r($arrData);
             foreach ($arrData['data'] as $data)
             {
                echo $data['slug'];
             }*/
            // exit;
             foreach($alacartQuery as $row2) 
             {
                 $alaCartaArData['data'][]=array(
                          'tagsname'=>$row2->tagsname,
                          'tagsslug'=>$row2->tagsslug,
                          'vendorlocationslug'=>$row2->vendorlocationslug,
                          'id'=>$row2->id,
                          'vendorlocationsName'=>$row2->vendorlocations,
                          'city'=>$row2->city,
                          'imagename'=>$row2->imagename,
                          'pricing_level'=>$row2->pricing_level,
                          'attribute_value'=>$row2->attribute_value,
                          'flagname'=>$row2->flagname,
                          'color'=>$row2->color,
                          'locationarea'=>$row2->locationarea,
                          'option'=>$row2->option,
                          'review_detail' => $this->getVendorLocationRatingDetails($row2->id)
                          /*'id'=>$row->id,
                          'cityname'=>strtolower($row->cityname),
                          'rating' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['averageRating']:0,
                          'total_reviews' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['totalRating']:0,
                          'full_stars' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['full_stars']:0,
                          'half_stars' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['half_stars']:0,
                          'blank_stars' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['blank_stars']:0,*/
                    );
             }
             /*print_r($alaCartaArData);
             exit;*/
             
         return view('frontend.pages.collection',$arrResponse)
                    ->with('collectionResult', $collectionResult)
                    ->with('exclusiveExperiences',$exclusiveExperiences)
                    ->with('arrData',$arrData)
                    ->with('alaCartaArData',$alaCartaArData);
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

    public function findRatingByProduct($arrProduct){
        
            $queryResult = DB::table('product_reviews')
                ->whereIN('product_id',$arrProduct)
                ->groupBy('product_id')
                ->select(DB::raw('AVG(rating) as avg_rating, COUNT(*) as total_ratings,product_id'))
                ->get();
          //array to store the result
          $arrRating = array();

          //reading the results
          foreach($queryResult as $row) 
          {

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
            //print_r($arrRating);

    }

    public function sorting(){
        $city_id    = Input::get('city');
        $sortby     = Input::get('sortby');
        
        $city       = Location::where(['Type' => 'City', 'id' => $city_id])->first()->name;

        if($sortby == "popular"){
            $set_order = 'products.created_at';//order by if(flag_name like "popular", 0, if(flag_name not like "popular", 1, 2))';
            $set_order_type = 'ASC';
        } else if($sortby == "new"){
            $set_order = 'products.created_at';
            $set_order_type = 'DESC';
        }

        $data['current_city'] = $city;
        $data['sort_selected'] = $sortby;
        
        $arrSubmittedData['city_id'] = $city_id;
        $arrSubmittedData['orderby'] = $set_order;
        $arrSubmittedData['ordertype'] = $set_order_type;

        $searchResult = $this->experiences_model->findMatchingExperience($arrSubmittedData);     
        if(!empty($searchResult)) {
            //setting up the array to be formatted as json
            $data['resultCount'] = $searchResult['resultCount'];
            $data['data'] = (array_key_exists('data', $searchResult)) ? $searchResult['data']:array();
        }
        else {
            $data['resultCount'] = 0;
        }


        $restaurant_data_values = view('frontend.pages.experiencelistajax',$data)->render();
        $restaurant_data = str_replace(array('\r', '\n', '\t'),"",$restaurant_data_values);

        return Response::json(array('restaurant_data'=> $restaurant_data), 200);

    }

    public function search_filter()
    {
        //DB::connection()->enableQueryLog();
        $restaurant_value = Input::get('restaurant_val');
        $format_date_value = (Input::get('date_value') ? Input::get('date_value') : "");
        $time_value = Input::get('time_value');
        $price_start_range = Input::get('start_price');
        $price_end_with = Input::get('end_price');
        $arrAreasList = Input::get('area_values');
        $arrCuisineList = Input::get('cuisine_values');   
        $arrTagsList = Input::get('tags_values');   
        $arrVendorList = Input::get('vendor_value');   
             
        $search_city = Input::get('city');
       
        $city       = Location::where(['Type' => 'City', 'id' => $search_city])->first()->name;

        if(isset($format_date_value) && $format_date_value != "") {
            $day = strtolower(date('N',strtotime($format_date_value)));
            $explode_before_time = explode("/",$format_date_value);
            $date_value = $explode_before_time[2]."-".$explode_before_time[0]."-".$explode_before_time[1];

            $set_start_time = "11:00:00_".$day;
            $set_end_time = "23:59:00_".$day;
        }else{
            $format_date_value = '';
            $date_value = '';
            $day = '';
        }

        if($time_value != "") {
            
            if($time_value == "lunch") {
                $set_start_time = "11:00:00";
                $set_end_time = "14:00:00";
            } else if($time_value == "dinner"){
                $set_start_time = "18:00:00";
                $set_end_time = "23:59:00";
            } else {
                $change_start_time = strtotime("-30 minutes", strtotime($time_value));
                $set_start_time = date('H:i', $change_start_time).":00";
                $change_end_time = strtotime("+30 minutes", strtotime($time_value));
                $set_end_time = date('H:i', $change_end_time).":00";
            }

        } else {
            
            $set_start_time = "";
            $set_end_time = "";

        }

        $data['current_city'] = $city;
        $arrSubmittedData['city_id'] = $search_city;
        if(!empty($arrAreasList))
        {
            $arrSubmittedData['location'] = explode(',',$arrAreasList);
        }

        if(!empty($arrCuisineList))
        {
            $arrSubmittedData['cuisine']  = explode(',',$arrCuisineList);
        }

        if(!empty($arrTagsList))
        {
            $arrSubmittedData['tag']  = explode(',',$arrTagsList);
        }

        if(!empty($arrVendorList))
        {
            $arrSubmittedData['vendor']  = explode(',',$arrVendorList);
        }

        $arrSubmittedData['minPrice']       = $price_start_range; 

        if(!empty($price_end_with))
        {
            $arrSubmittedData['maxPrice']  = $price_end_with;
        }
        
        $searchResult = $this->experiences_model->findMatchingExperience($arrSubmittedData);       
                
        if(!empty($searchResult)) {
            //setting up the array to be formatted as json
            $data['resultCount'] = $searchResult['resultCount'];
            $data['data'] = (array_key_exists('data', $searchResult)) ? $searchResult['data']:array();
            $data['filters'] = $this->experiences_model->getExperienceSearchFilters();
        }
        else {
            $data['resultCount'] = 0;
            $data['filters']['locations']  = array();
            $data['filters']['cuisines']  = array();
            $data['filters']['tags']  = array();
        }

        $restaurant_data_values = view('frontend.pages.experiencelistajax',$data)->render();
        $restaurant_data = str_replace(array('\r', '\n', '\t'),"",$restaurant_data_values);

        return Response::json(array('restaurant_data'=> $restaurant_data,'area_count' => $data['filters']['locations'], 'cuisine_count' => $data['filters']['cuisines'], 'tags_count' => $data['filters']['tags']), 200);
        
    }

    public function new_custom_search()
    {
        //DB::connection()->enableQueryLog();

        $term_str   = Input::get('term');

        $term = strip_tags($term_str);
        $city = Input::get('city');
        
        $arrSubmittedData['city_id'] = $city;
        $arrSubmittedData['term']    = $term_str;

        $arrExpData = $this->experiences_model->getExperienceAreaCuisineByName($arrSubmittedData);
        echo json_encode($arrExpData);
    }

    public function exporder()
    {
        //echo "<pre>"; print_r(Input::all());
        $dataPost['reservationDate'] = Input::get('booking_date');
        $dataPost['reservationDay'] =  date("D", strtotime($dataPost['reservationDate']));//
        $dataPost['reservationTime'] = Input::get('booking_time');
        $dataPost['partySize'] = Input::get('qty');
        $dataPost['vendorLocationID'] = Input::get('address');
        $dataPost['guestName'] = Input::get('fullname');
        $dataPost['guestEmail'] = Input::get('email');
        $dataPost['phone'] = Input::get('phone');
        $dataPost['reservationType'] = (isset($dataPost['prepaid']) && $dataPost['prepaid'] == 1 ? 'experience' : 'event');
        $dataPost['specialRequest'] = Input::get('special');

        $dataPost['addon']          = Input::get('add_ons');
        $dataPost['giftCardID']     = Input::get('giftcard_id');
        $dataPost['status']     = (Input::get('prepaid') == 1 ? "inactive" : "new");
        $dataPost['prepaid']     = Input::get('prepaid');
        //echo "<pre>"; print_r($dataPost);
        $count = $dataPost['addon'];
        if($count==""){  $dataPost['addon'] =array();}

        $addonsText = '';
        $addonsPostTaxTotal = '';
        foreach($dataPost['addon'] as $prod_id => $qty) {
            if($qty > 0){
                //echo "prod id = ".$prod_id." , qty = ".$qty;
                $addonsDetails = DB::select("SELECT attribute_value from product_attributes_text where product_id = $prod_id and product_attribute_id = 17");
                $addonsPricing = DB::select("SELECT post_tax_price from product_pricing where product_id = $prod_id");
                $addonsPostTaxTotal += $qty * $addonsPricing[0]->post_tax_price;
                //echo "<pre>"; print_r($addonsDetails);
                $addonsText .= $addonsDetails[0]->attribute_value." (".$qty.") , ";
                $dataPost['addon_'.$prod_id] = $qty;
            }

        }
        $dataPost['total_amount'] = Input::get('post_amount') + $addonsPostTaxTotal;
        //echo "grand total addons = ".$addonsPostTaxTotal." , grand total = ".$dataPost['total_amount'];die;
        $finalAddontext = isset($addonsText) && $addonsText != "" ? "Addons: ".$addonsText : " ";
        $special_request = isset($dataPost['specialRequest']) && $dataPost['specialRequest'] != "" ? "Spl Req: ".$dataPost['specialRequest'] : "";
        $dataPost['addons_special_request'] = $finalAddontext." ".$special_request;



        //echo $finalSpecialRequest;
        //die;
        $userID = Session::get('id');
        $userData = Profile::getUserProfileWeb($userID);
        //echo "<pre>"; print_r($userData); die;

        //$dataPost['access_token'] = Session::get('id');
        //echo "<pre>"; print_r($dataPost); die;
        $locationDetails = $this->experiences_model->getLocationDetails($dataPost['vendorLocationID']);
        //echo "<pre>"; print_r($locationDetails); //die;
        $outlet = $this->experiences_model->getOutlet($dataPost['vendorLocationID']);
        //echo "<pre>"; print_r($outlet);

        //die;
        $productDetails = $this->repository->getByExperienceId($outlet->product_id);
        $dataPost['product_id'] = (isset($outlet->product_id) && $outlet->product_id != 0 ? $outlet->product_id : 0);
        $dataPost['vendor_location_id'] = (isset($outlet->vendor_location_id) && $outlet->vendor_location_id != 0 ? $outlet->vendor_location_id : 0);
        //echo "<pre>"; print_r($productDetails); die;

        $arrRules = array(
                            'reservationDate' => 'required|date',
                            'reservationDay' => 'required',
                            'reservationTime' => 'required',
                            'partySize' => 'required|integer',
                            'vendorLocationID' => 'required|not_in:0',
                            'guestName' => 'required|max:255',
                            'guestEmail' => 'required|email|max:255',
                            'phone' => 'required',
                            'reservationType' => 'required|in:experience,alacarte,event',
                            'specialRequest' => 'max:512'//,
                            //'reservationID' => 'sometimes|required|exists:reservation_details,id'
                        ) ;

        $validator = Validator::make($dataPost,$arrRules);

        if($validator->fails()) {
            $message = $validator->messages();
            $errorMessage = "";
            foreach($data as $key => $value) {
                if($message->has($key)) {
                    $errorMessage .= $message->first($key).'\n ';
                }
            }

           return redirect()->back()->withErrors($validator);
        } else {

            //End MailChimp

            if($userID > 0) {
                //validating the information submitted by users
                $arrResponse = $this->experiences_model->validateReservationData($dataPost);
                echo "checking validation";
                if($arrResponse['status'] == 'success') {
                        /*$getUsersDetails = $this->user->fetchDetails($userID);
                        echo "<pre>"; print_r($getUsersDetails); die;*/
                    echo "status is success";
                        $reservationResponse = $this->experiences_model->addReservationDetails($dataPost,$userID);

                        if(isset($dataPost['prepaid']) && $dataPost['prepaid'] == 1){
                                echo "prepaid is true";
                            if(isset($_COOKIE['email_cookie'])){
                                unset($_COOKIE['email_cookie']);
                            }
                            $cookiearray = array(
                                'reservationDate' => $dataPost['reservationDate'],
                                'reservationDay' => $dataPost['reservationDay'],
                                'reservationTime' => $dataPost['reservationTime'],
                                'partySize' => $dataPost['partySize'],
                                'vendorLocationID' => $dataPost['vendorLocationID'],
                                'guestName' => $dataPost['guestName'],
                                'guestEmail' => $dataPost['guestEmail'],
                                'phone' => $dataPost['phone'],
                                'reservationType' => $dataPost['reservationType'],
                                'specialRequest' => $dataPost['specialRequest'],
                                'giftCardID' => (isset($dataPost['giftCardID']) && $dataPost['giftCardID'] != "" ? $dataPost['giftCardID'] : ""),
                                'status' => $dataPost['status'],
                                'prepaid' => $dataPost['prepaid'],
                                'addons_special_request' => $dataPost['addons_special_request'],
                                'product_id' => $dataPost['product_id'],
                                'vendor_location_id' => $dataPost['vendor_location_id'],
                                'total_amount' => $dataPost['total_amount'],
                                'order_id' => $reservationResponse['data']['reservationID'],
                                'user_id' => $userID,
                                'reward_points' => $productDetails['attributes']['reward_points_per_reservation'],
                                'bookingsMade' => $userData['data']['bookings_made'] + 1,
                                'restaurant_name' => $outlet->vendor_name,
                                'descriptive_title' => $outlet->descriptive_title,
                                'membership_number' => $userData['data']['membership_number'],
                                'outlet_name' => $outlet->name,
                                'experience_title' => $outlet->product_name,
                                'experience_description' => $productDetails['attributes']['short_description'],
                                'order_id_digits' => sprintf("%06d",$reservationResponse['data']['reservationID']),
                                'experience_includes' => $productDetails['attributes']['experience_includes'],
                                'terms_and_conditions' => $productDetails['attributes']['terms_and_conditions'],
                                'address' => $locationDetails->address,
                                'lat' => $locationDetails->latitude,
                                'long' => $locationDetails->longitude,
                                'slug' => $outlet->slug,
                            );


                            foreach($dataPost['addon'] as $prod_id => $qty) {
                                if($qty > 0){
                                    //echo "prod id = ".$prod_id." , qty = ".$qty;
                                    $addonsDetails = DB::select("SELECT attribute_value from product_attributes_text where product_id = $prod_id and product_attribute_id = 17");
                                    $cookiearray['addon_'.$prod_id] = $qty;
                                }

                            }


                            $city_id    = Input::get('city');
                            $city_name      = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
                            if(empty($city_name))
                            {
                                $city_name = 'mumbai';
                            }

                            $cookiearray['allow_guest']            ='Yes';
                            $cookiearray['current_city']           = strtolower($city_name);

                            $cookiearray['current_city_id']        = $city_id;

                            foreach($cookiearray as $key => $val)
                            {
                                echo "key  = ".$key." , val = ".$val." <br/>";
                                $name = "email_cookie[".$key."]";
                                $time = time()+ 86500;

                                setcookie($name, $val, $time, "/");
                            }


                            //echo "<pre>sad = "; print_r($_COOKIE['email_cookie']); die;
                            return view('site.pages.payment',['cookie_array'=>$cookiearray]);
                            //

                        } else {
                            $rewardsPoints = $productDetails['attributes']['reward_points_per_reservation'];
                            $bookingsMade = $userData['data']['bookings_made'] + 1;
                            $type = "new";
                            $reservationType = "experience";
                            $lastOrderId = $reservationResponse['data']['reservationID'];
                            //echo "rewardsPoints = ".$rewardsPoints." , bookingsMade = ".$bookingsMade." , type = ".$type." , reservationType = ".$reservationType; die;
                            Profile::updateReservationInUsers($rewardsPoints,$type,$bookingsMade,$reservationType,$userID,$lastOrderId);
                            DB::table('users')
                                ->where('id', $userID)
                                ->update(array('full_name' => $dataPost['guestName'],'phone_number'=>$dataPost['phone']));

                            //echo "<pre>"; print_r($reservationResponse); die;
                            $zoho_data = array(
                                'Name' => $dataPost['guestName'],
                                'Email_ids' => $dataPost['guestEmail'],
                                'Contact' => $dataPost['phone'],
                                'Experience_Title' => $outlet->vendor_name.' - '.$outlet->descriptive_title,
                                'No_of_People' => $dataPost['partySize'],
                                'Date_of_Visit' => date('d-M-Y', strtotime($dataPost['reservationDate'])),
                                'Time' => date("g:i A", strtotime($dataPost['reservationTime'])),
                                'Alternate_ID' =>  'E'.sprintf("%06d",$reservationResponse['data']['reservationID']),
                                'Occasion' => $dataPost['addons_special_request'],
                                'Type' => "Experience",
                                'API_added' => 'Yes',
                                'GIU_Membership_ID' => $userData['data']['membership_number'],
                                'Outlet' => $outlet->name,
                                //'Points_Notes'=>'test',
                                'AR_Confirmation_ID'=>'0',
                                'Auto_Reservation'=>'Not available',
                                //'telecampaign' => $campaign_id,
                                //'total_no_of_reservations'=> '1',
                                'Calling_option' => 'No',
                                'gift_card_id_from_reservation' => $dataPost['giftCardID']
                            );
                            //echo "<pre>"; print_r($zoho_data);
                            $zoho_res = $this->zoho_add_booking($zoho_data);
                            $zoho_success = $zoho_res->result->form->add->status;
                            //echo "<pre>"; print_r($zoho_success); die;
                            if($zoho_success[0] != "Success"){
                                //$this->email->from('concierge@wowtables.com', 'WowTables by GourmetItUp');
                                //$list = array('concierge@wowtables.com', 'kunal@wowtables.com', 'deepa@wowtables.com');
                                //$this->email->to($list);
                                //$this->email->subject('Urgent: Zoho reservation posting error');
                                $mailbody = 'E'.sprintf("%06d",$reservationResponse['data']['reservationID']).' reservation has not been posted to zoho. Please fix manually.<br><br>';
                                $mailbody .= 'Reservation Details<br>';
                                foreach($zoho_data as $key => $val){
                                    $name = str_replace('_',' ',$key);
                                    $mailbody .= $name.' '.$val.'<br>';
                                }

                                Mail::send('site.pages.zoho_posting_error',[
                                    'zoho_data'=> $mailbody,
                                ], function($message) use ($zoho_data)
                                {
                                    $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                                    $message->to('concierge@wowtables.com')->subject('Urgent: Zoho reservation posting error');
                                    $message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com');
                                });
                            }

                            $mergeReservationsArray = array('order_id'=> sprintf("%06d",$reservationResponse['data']['reservationID']),
                                'reservation_date'=> date('d-F-Y',strtotime($dataPost['reservationDate'])),
                                'reservation_time'=> date('g:i a',strtotime($dataPost['reservationTime'])),
                                'venue' => $outlet->vendor_name,
                                'username' => $dataPost['guestName']
                            );

                            Mail::send('site.pages.experience_reservation',[
                                'location_details'=> $locationDetails,
                                'outlet'=> $outlet,
                                'post_data'=>$dataPost,
                                'productDetails'=>$productDetails,
                                'reservationResponse'=>$reservationResponse,
                            ], function($message) use ($mergeReservationsArray){
                                $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                                $message->to(Input::get('email'))->subject('Your WowTables Reservation at '.$mergeReservationsArray['venue']);
                                //$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
                            });
                            $dataPost['admin']  = "yes";
                            Mail::send('site.pages.experience_reservation',[
                                'location_details'=> $locationDetails,
                                'outlet'=> $outlet,
                                'post_data'=>$dataPost,
                                'productDetails'=>$productDetails,
                                'reservationResponse'=>$reservationResponse,
                            ], function($message) use ($mergeReservationsArray){
                                $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                                $message->to('concierge@wowtables.com')->subject('NR - #E'.$mergeReservationsArray['order_id'].' | '.$mergeReservationsArray['reservation_date'].' , '.$mergeReservationsArray['reservation_time'].' | '.$mergeReservationsArray['venue'].' | '.$mergeReservationsArray['username']);
                                $message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com');
                            });

                            //echo "userid == ".$userID;
                            $getUsersDetails = $this->experiences_model->fetchDetails($userID);

                            //Start MailChimp
                            if(!empty($getUsersDetails)){

                                $merge_vars = array(
                                    'MERGE1'=>$dataPost['guestName'],
                                    'MERGE10'=>date('m/d/Y'),
                                    'MERGE11'=>$userData['data']['bookings_made'] + 1,
                                    'MERGE13'=>$dataPost['phone'],
                                    'MERGE27'=>date("m/d/Y",strtotime($dataPost['reservationDate']))
                                );
                                $this->mailchimp->lists->subscribe($this->listId, ["email"=>$dataPost['guestEmail']],$merge_vars,"html",false,true );
                                //$this->mc_api->listSubscribe($list_id, $_POST['email'], $merge_vars,"html",true,true );
                            }

                            $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
                            $arrResponse['cities'] = $cities;

                            $city_id    = Input::get('city');
                            $city_name      = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
                            if(empty($city_name))
                            {
                                $city_name = 'mumbai';
                            }

                            $arrResponse['allow_guest']            ='Yes';
                            $arrResponse['current_city']           = strtolower($city_name);

                            $arrResponse['current_city_id']        = $city_id;

                            $arrResponse['restaurant_name'] = $outlet->vendor_name;
                            $arrResponse['experience_title'] = $outlet->product_name;
                            $arrResponse['experience_description'] = $productDetails['attributes']['short_description'];
                            $arrResponse['reservation_date'] = $dataPost['reservationDate'];
                            $arrResponse['reservation_time'] = $dataPost['reservationTime'];
                            $arrResponse['order_id'] = $mergeReservationsArray['order_id'];
                            $arrResponse['guests'] = $dataPost['partySize'];
                            $arrResponse['experience_includes'] = $productDetails['attributes']['experience_includes'];
                            $arrResponse['terms_and_conditions'] = $productDetails['attributes']['terms_and_conditions'];
                            $arrResponse['address'] = $locationDetails->address;
                            $arrResponse['lat'] = $locationDetails->latitude;
                            $arrResponse['long'] = $locationDetails->longitude;
                            $arrResponse['city'] = $arrResponse['current_city'];
                            $arrResponse['slug'] = $outlet->slug;

                            return Redirect::to('/experiences/thankyou/E'.$mergeReservationsArray['order_id'])->with('response' , $arrResponse);
                        }

                }
            } else {
                return redirect()->back()->withErrors($validator);
            }
        }


    }

    public function process_response(){
        //echo "sad <br/>";
        $requestarray = Input::all();

        $fetch_cookie = $_COOKIE['email_cookie'];

        //echo "<pre> sads = "; print_r($fetch_cookie);
        //echo "<pre> sads = "; print_r($requestarray);
        //echo " asf = ".$requestarray['status'];
        //echo "asd = ".$fetch_cookie['addons_special_request'];
        //die;
        if($requestarray['status'] == "success"){
            $details = '<table width="600" cellpadding="2" cellspacing="2" border="0">
        <tr>
            <th colspan="2">Transaction Details</th>
        </tr>';
            foreach( $requestarray as $key => $value) {
                $details .= '<tr>
                <td class="fieldName" width="50%">'. $key.'</td>
                <td class="fieldName" align="left" width="50%">'. $value.'</td>
            </tr>';
            }
            $details .= '</table>';

            $transaction['user_id']=$fetch_cookie['user_id'];
            $transaction['response_code']=$requestarray['unmappedstatus'];
            $transaction['response_message']=$requestarray['status'];
            $transaction['transaction_date']=date('Y-m-d H:i:s');
            $transaction['reservation_id']=$requestarray['txnid'];
            $transaction['amount_paid']=$requestarray['amount'];
            $transaction['transaction_number']=$requestarray['mihpayid'];
            $transaction['transaction_details']=$details."~~".$requestarray['status'];

            $lastTransactionID = DB::table('transactions_details')->insertGetId($transaction);

            $bookingsMade = DB::table('reservation_details')
                ->where('id', $requestarray['txnid'])
                ->update(array('reservation_status' => 'new','transaction_id' => $lastTransactionID));

            $locationDetails = $this->experiences_model->getLocationDetails($fetch_cookie['vendorLocationID']);
            $outlet = $this->experiences_model->getOutlet($fetch_cookie['vendorLocationID']);
            $productDetails = $this->repository->getByExperienceId($outlet->product_id);

            $userID = $fetch_cookie['user_id'];
            $rewardsPoints = $fetch_cookie['reward_points'];
            $bookingsMade = $fetch_cookie['bookingsMade'];
            $type = "new";
            $reservationType = "experience";
            $lastOrderId = $fetch_cookie['order_id'];
            //echo "rewardsPoints = ".$rewardsPoints." , bookingsMade = ".$bookingsMade." , type = ".$type." , reservationType = ".$reservationType; die;
            Profile::updateReservationInUsers($rewardsPoints,$type,$bookingsMade,$reservationType,$userID,$lastOrderId);
            DB::table('users')
                ->where('id', $userID)
                ->update(array('full_name' => $fetch_cookie['guestName'],'phone_number'=>$fetch_cookie['phone']));

            //echo "<pre>"; print_r($reservationResponse); die;
            $zoho_data = array(
                'Name' => $fetch_cookie['guestName'],
                'Email_ids' => $fetch_cookie['guestEmail'],
                'Contact' => $fetch_cookie['phone'],
                'Experience_Title' => $fetch_cookie['restaurant_name'].' - '.$fetch_cookie['descriptive_title'],
                'No_of_People' => $fetch_cookie['partySize'],
                'Date_of_Visit' => date('d-M-Y', strtotime($fetch_cookie['reservationDate'])),
                'Time' => date("g:i A", strtotime($fetch_cookie['reservationTime'])),
                'Alternate_ID' =>  'E'.sprintf("%06d",$fetch_cookie['order_id']),
                'Occasion' => $fetch_cookie['addons_special_request'],
                'Type' => "Experience",
                'API_added' => 'Yes',
                'GIU_Membership_ID' => $fetch_cookie['membership_number'],
                'Outlet' => $fetch_cookie['outlet_name'],
                //'Points_Notes'=>'test',
                'AR_Confirmation_ID'=>'0',
                'Auto_Reservation'=>'Not available',
                //'telecampaign' => $campaign_id,
                //'total_no_of_reservations'=> '1',
                'Calling_option' => 'No',
                'gift_card_id_from_reservation' => (isset($fetch_cookie['giftCardID']) && $fetch_cookie['giftCardID'] != "" ? $fetch_cookie['giftCardID'] : "")
            );
            //echo "<pre>"; print_r($zoho_data);
            $zoho_res = $this->zoho_add_booking($zoho_data);
            $zoho_success = $zoho_res->result->form->add->status;
            //echo "<pre>"; print_r($zoho_success); die;
            if($zoho_success[0] != "Success"){
                //$this->email->from('concierge@wowtables.com', 'WowTables by GourmetItUp');
                //$list = array('concierge@wowtables.com', 'kunal@wowtables.com', 'deepa@wowtables.com');
                //$this->email->to($list);
                //$this->email->subject('Urgent: Zoho reservation posting error');
                $mailbody = 'E'.sprintf("%06d",$fetch_cookie['order_id']).' reservation has not been posted to zoho. Please fix manually.<br><br>';
                $mailbody .= 'Reservation Details<br>';
                foreach($zoho_data as $key => $val){
                    $name = str_replace('_',' ',$key);
                    $mailbody .= $name.' '.$val.'<br>';
                }

                Mail::send('site.pages.zoho_posting_error',[
                    'zoho_data'=> $mailbody,
                ], function($message) use ($zoho_data)
                {
                    $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                    $message->to('concierge@wowtables.com')->subject('Urgent: Zoho reservation posting error');
                    $message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com');
                });
            }

            $mergeReservationsArray = array('order_id'=> sprintf("%06d",$fetch_cookie['order_id']),
                'reservation_date'=> date('d-F-Y',strtotime($fetch_cookie['reservationDate'])),
                'reservation_time'=> date('g:i a',strtotime($fetch_cookie['reservationTime'])),
                'venue' => $fetch_cookie['restaurant_name'],
                'username' => $fetch_cookie['guestName']
            );

            $reservationResponse = array();
            $reservationResponse['data']['reservationDate'] = $fetch_cookie['reservationDate'];
            $reservationResponse['data']['reservationTime'] = $fetch_cookie['reservationTime'];
            $reservationResponse['data']['partySize'] = $fetch_cookie['partySize'];
            $reservationResponse['data']['reservationID'] = $fetch_cookie['order_id'];
            $reservationResponse['data']['reservation_type'] = "event";

            Mail::send('site.pages.experience_reservation',[
                'location_details'=> $locationDetails,
                'outlet'=> $outlet,
                'post_data'=>$fetch_cookie,
                'productDetails'=>$productDetails,
                'reservationResponse'=>$reservationResponse,
            ], function($message) use ($mergeReservationsArray){
                $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                $message->to(Input::get('email'))->subject('Your WowTables Reservation at '.$mergeReservationsArray['venue']);
                //$message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
            });
            $dataPost['admin']  = "yes";
            Mail::send('site.pages.experience_reservation',[
                'location_details'=> $locationDetails,
                'outlet'=> $outlet,
                'post_data'=>$fetch_cookie,
                'productDetails'=>$productDetails,
                'reservationResponse'=>$reservationResponse,
            ], function($message) use ($mergeReservationsArray){
                $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                $message->to('tech@gourmetitup.com')->subject('NR - #E'.$mergeReservationsArray['order_id'].' | '.$mergeReservationsArray['reservation_date'].' , '.$mergeReservationsArray['reservation_time'].' | '.$mergeReservationsArray['venue'].' | '.$mergeReservationsArray['username']);
                //$message->cc('kunal@wowtables.com', 'deepa@wowtables.com','tech@wowtables.com');
            });

            //echo "userid == ".$userID;
            $getUsersDetails = $this->experiences_model->fetchDetails($userID);

            //Start MailChimp
            if(!empty($getUsersDetails)){

                $merge_vars = array(
                    'MERGE1'=>$fetch_cookie['guestName'],
                    'MERGE10'=>date('m/d/Y'),
                    'MERGE11'=>$fetch_cookie['bookingsMade'],
                    'MERGE13'=>$fetch_cookie['phone'],
                    'MERGE27'=>date("m/d/Y",strtotime($fetch_cookie['reservationDate']))
                );
                $this->mailchimp->lists->subscribe($this->listId, ["email"=>$fetch_cookie['guestEmail']],$merge_vars,"html",false,true );
                //$this->mc_api->listSubscribe($list_id, $_POST['email'], $merge_vars,"html",true,true );
            }

            $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
            $arrResponse['cities'] = $cities;

            $city_id    = Input::get('city');
            $city_name      = Location::where(['Type' => 'City', 'id' => $city_id])->pluck('name');
            if(empty($city_name))
            {
                $city_name = 'mumbai';
            }

            $arrResponse['allow_guest']            ='Yes';
            $arrResponse['current_city']           = strtolower($city_name);

            $arrResponse['current_city_id']        = $city_id;

            $arrResponse['restaurant_name'] = $fetch_cookie['restaurant_name'];
            $arrResponse['experience_title'] = $fetch_cookie['experience_title'];
            $arrResponse['experience_description'] = $fetch_cookie['experience_description'];
            $arrResponse['reservation_date'] = $fetch_cookie['reservationDate'];
            $arrResponse['reservation_time'] = $fetch_cookie['reservationTime'];
            $arrResponse['order_id'] = $mergeReservationsArray['order_id'];
            $arrResponse['guests'] = $fetch_cookie['partySize'];
            $arrResponse['experience_includes'] = $fetch_cookie['experience_includes'];
            $arrResponse['terms_and_conditions'] = $fetch_cookie['terms_and_conditions'];
            $arrResponse['address'] = $fetch_cookie['address'];
            $arrResponse['lat'] = $fetch_cookie['lat'];
            $arrResponse['long'] = $fetch_cookie['long'];
            $arrResponse['city'] = $fetch_cookie['current_city'];
            $arrResponse['slug'] = $fetch_cookie['slug'];

            return Redirect::to('/experiences/thankyou/E'.$mergeReservationsArray['order_id'])->with('response' , $arrResponse);
        }


    }

    public function thankyou($response){
        //echo "orderid == ".$orderID;


        $result1= Session::get('response');

        session_start();
        $result= $_SESSION["result"]=$result1;
        //print_r($a);
        //exit;
        $data['current_city'] = $result['current_city'];
        $data['current_city_id'] = $result['current_city_id'];
        $data['user']   = Auth::user();
        $cities = Location::where(['Type' => 'City', 'visible' =>1])->lists('name','id');
        $data['cities'] = $cities;
        return view('frontend.pages.experience_thankyou',$data,['result'=>$result]);
        //echo "dsf<pre>"; print_r($result); echo "asc<pre>";
    }

    public function exporderexists()
    {
      
        $dataPost['reservationDate']    = Input::get('booking_date');
        $dataPost['reservationDay']     =  date("D", strtotime($dataPost['reservationDate']));
        $dataPost['reservationTime']    = Input::get('booking_time');
        $dataPost['partySize']          = Input::get('qty');
        $dataPost['vendorLocationID']   = Input::get('address');
        $dataPost['guestName']          = Input::get('fullname');
        $dataPost['guestEmail']         = Input::get('email');
        $dataPost['phone']              = Input::get('phone');
        $dataPost['reservationType']    = 'experience';
        $dataPost['specialRequest']     = Input::get('special');
        $dataPost['access_token']       = Session::get('id');
        $user_id = Auth::user()->id;
        $reserv_date_new = date('Y-m-d',strtotime(Input::get('booking_date')));
        $reserv_time_new = Input::get('booking_time');
        $check_user_query = DB::select("SELECT `reservation_date`,`reservation_time` FROM `reservation_details`
                                         WHERE `user_id`='$user_id' and `reservation_date`='$reserv_date_new' AND `reservation_status`IN ('edited', 'new')");
        //print_r($check_user_query);
        $success = '0';
      if(!empty($check_user_query))
      {
        foreach ($check_user_query as $value) {
           $reserv_date = $value->reservation_date;
           $reserv_time = $value->reservation_time;

                  $last_reserv_date = date('Y-m-d',strtotime($reserv_date));
                    $last_reserv_time =  strtotime($reserv_time);
                    $last_reserv_time_2_hours_after = strtotime('+2 Hour',$last_reserv_time);
                    //echo '<br>';
                    $last_reserv_time_2_hours_before = strtotime('-2 Hour',$last_reserv_time);
                    if($reserv_date_new == $last_reserv_date){
                        //echo 'if';
                        $new_reserv = strtotime($reserv_time_new);
                        
                        if( $new_reserv >= $last_reserv_time_2_hours_before && $new_reserv <= $last_reserv_time_2_hours_after){
                            $success =1;
                           break; 
                        }
                    }

                  }
      }
        
       
       $arrData = $this->experiences_model->validateReservationData($dataPost);
       $arrData['check_time'] = $success;
       //print_r($arrData);
       //exit;
        echo json_encode($arrData);
    }
    public function zoho_add_booking($data)
    {
        $ch = curl_init();
        $config = array(
            //'authtoken' => 'e56a38dab1e09933f2a1183818310629',
            'authtoken' => '7e8e56113b2c2eb898bca9916c52154c',
            'scope' => 'creatorapi',
        );
        $curlConfig = array(
            CURLOPT_URL            => "https://creator.zoho.com/api/gourmetitup/xml/experience-bookings/form/bookings/record/add/",
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => $config + $data,
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result; die;
        return	simplexml_load_string($result);
    }
}