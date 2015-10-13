<?php namespace WowTables\Http\Models\Eloquent\Api;


use Illuminate\Database\Eloquent\Model;
use Config;
use DB;

class UserBookmarks extends Model {

	protected $table = 'user_bookmarks';


	public static function getBookmarkedResturantInformation($expArr,$alaArr) {



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
			->leftjoin('vendor_location_address as vlaa', 'vlaa.vendor_location_id', '=', 'vl.id')
			->join('locations as loc1','loc1.id', '=' , 'vlaa.area_id')
			->join('locations as loc2', 'loc2.id', '=', 'vlaa.city_id')
			->join('locations as loc3', 'loc3.id', '=', 'vlaa.state_id')
			->join('locations as loc4', 'loc4.id', '=', 'vlaa.country_id')
			->join('locations as loc5','loc5.id','=','vl.location_id')
			->leftJoin('vendor_locations_flags_map as vlfm','vlfm.vendor_location_id','=','vl.id')
			->leftJoin('flags','flags.id','=','vlfm.flag_id')
			->leftJoin('vendor_locations_media_map as vlmm', 'vlmm.vendor_location_id','=', 'vl.id')
			->leftJoin('media_resized_new as mrn1', function($join) {
				$join->on('mrn1.media_id', '=', 'vlmm.media_id')
					->where('mrn1.image_type', '=' , 'mobile_listing_ios_alacarte');
			})
			->leftJoin('media_resized_new as mrn2', function($join) {
				$join->on('mrn2.media_id', '=', 'vlmm.media_id')
					->where('mrn1.image_type', '=', 'mobile_listing_ios_alacarte');
			})
			->where('v.status','Publish')
			->where('vl.status','Active')
			->where('vl.a_la_carte','=', 1)
			->whereIn('vl.id',$alaArr)
			->select('v.name', 'vl.pricing_level', 'vl.id as vl_id',
				DB::raw('GROUP_CONCAT(DISTINCT vaso.option separator ", ") as cuisine'),
				DB::raw(('COUNT(DISTINCT vlr.id) AS total_reviews')),
				DB::raw('If(count(DISTINCT vlr.id) = 0, 0, ROUND(AVG(vlr.rating), 2)) AS rating'),
				'loc.name as location_name',
				'vlaa.latitude','vlaa.longitude', 'vlaa.address', 'vlaa.pin_code',
				'loc1.name as area', 'loc1.id as area_id', 'loc2.name as city', 'loc3.name as state_name',
				'loc4.name as country', 'loc5.name as locality',
				DB::raw('IFNULL(flags.name,"") AS flag_name'),
				'mrn1.file as ios_image',
				'mrn2.file as android_image'
			)
			->groupBy('vl.id');
		//->get();

		//checking if city has been passed in
		/*if(array_key_exists('HTTP_X_WOW_CITY', $_SERVER)) {
			$queryResult = $queryResult->join('vendor_location_address as vla', 'vla.vendor_location_id', '=', 'vl.id')
									   ->where('vla.city_id','=', $_SERVER["HTTP_X_WOW_CITY"]);
		}*/

		//executing the query
		$queryResult = $queryResult->get();

		//array to store the information from the DB
		$data = array();
		$data['status']=Config::get('constants.API_SUCCESS');

		//reading the experiences
		//$arrExperience = self::readNearbyRestaurantsExperiences($input);
		$arrExperience = self::readAllBookmarkedRestaurantsExperiences($expArr);

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
					),
					'distance' 		=> round($row->distance, 2),
					'location_address' => array(
						"address_line" 	=> $row->address,
						"locality" 		=> $row->locality,
						"area"			=> $row->area,
						"city" 			=> $row->city,
						"pincode" 		=> $row->pin_code,
						"state" 		=> $row->state_name,
						"country" 		=> $row->country,
						"latitude" 		=> $row->latitude,
						"longitude" 	=> $row->longitude
					),
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

	public static function readAllBookmarkedRestaurantsExperiences($expArr) {

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
			->leftjoin('vendor_location_address as vlaa', 'vlaa.vendor_location_id', '=', 'vl.id')
			->join('locations as loc1','loc1.id', '=' , 'vlaa.area_id')
			->join('locations as loc2', 'loc2.id', '=', 'vlaa.city_id')
			->join('locations as loc3', 'loc3.id', '=', 'vlaa.state_id')
			->join('locations as loc4', 'loc4.id', '=', 'vlaa.country_id')
			->join('locations as loc5','loc5.id','=','vl.location_id')
			->leftJoin('product_attributes_varchar AS pav', 'p.id', '=', 'pav.product_id')
			->join('product_attributes_multiselect as pam', 'pam.product_id','=', 'p.id')
			->leftJoin('product_attributes AS vamso', function($join){
				$join->on('pa.id', '=', 'pav.product_attribute_id')
					->on('vamso.alias','=', DB::raw('"cuisines"'));
			})
			->leftJoin('product_attributes_select_options AS paso', 'paso.id', '=', 'pam.product_attributes_select_option_id')
			->where('p.status', 'Publish')
			->where('pvl.status','Active')
			->where('mrn1.image_type','mobile_listing_ios_experience')
			->where('mrn2.image_type', 'mobile_listing_android_experience')
			->whereIn('p.id',$expArr)
			->select(
				'p.id as product_id','p.name', 'pvl.id as pvl_id',
				DB::raw(('COUNT(DISTINCT pr.id) AS total_reviews')),
				DB::raw('GROUP_CONCAT(DISTINCT paso.option separator ", ") as cuisine'),
				DB::raw('MAX(IF(pa.alias = "short_description", pat.attribute_value, "")) AS short_description'),
				DB::raw('If(count(DISTINCT pr.id) = 0, 0, ROUND(AVG(pr.rating), 2)) AS rating'),
				DB::raw('GROUP_CONCAT(DISTINCT loc.name separator ", ") as location_name'),
				'mrn1.file as ios_image','mrn2.file as android_image',
				'flags.name as flag_name',
				'vlaa.latitude','vlaa.longitude', 'vlaa.address', 'vlaa.pin_code',
				'loc1.name as area', 'loc1.id as area_id', 'loc2.name as city', 'loc3.name as state_name',
				'loc4.name as country', 'loc5.name as locality',
				'pp.post_tax_price','pp.price',
				'pp.taxes', 'pt.type_name as price_type'
			)
			->groupBy('pvl.id');

		//echo $queryResult->toSql(); die();
		//->groupBy('p.id');
		//->get();

		//checking if city has been passed in
		if(array_key_exists('HTTP_X_WOW_CITY', $_SERVER)) {
			$queryResult = $queryResult->join('vendor_location_address as vla', 'vla.vendor_location_id', '=', 'vl.id')
				->where('vla.city_id','=', $_SERVER["HTTP_X_WOW_CITY"]);
		}

		//executing the query
		$queryResult = $queryResult->get();
		//return $queryResult;

		//array to store the information from the DB
		$data = array();
		if($queryResult) {
			foreach($queryResult as $row) {


				$data[] = array(
					'prod_id' 			=> $row->product_id,
					'pvl_id' 			=> $row->pvl_id,
					'name' 				=> $row->name,
					'total_reviews' 	=> $row->total_reviews,
					'rating' 			=> $row->rating,
					'cuisine' 			=> (empty($row->cuisine)) ? "" : $row->cuisine,
					'price' 			=> $row->price,
					'post_tax_price' 	=> $row->post_tax_price,
					'taxes' 			=> $row->taxes,
					'price_type' 		=> $row->price_type,
					'location' 			=> $row->location_name,
					'distance' 			=> round($row->distance,2),
					'location_address' => array(
						"address_line" 	=> $row->address,
						"locality" 		=> $row->locality,
						"area"			=> $row->area,
						"city" 			=> $row->city,
						"pincode" 		=> $row->pin_code,
						"state" 		=> $row->state_name,
						"country" 		=> $row->country,
						"latitude" 		=> $row->latitude,
						"longitude" 	=> $row->longitude
					),
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
}
