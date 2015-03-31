<?php namespace WowTables\Http\Models;

use DB;
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
	public function findMatchingExperience($arrData) {
		$experinceResult = DB::table('products')
							->join('product_attributes_varchar','product_attributes_varchar.product_id','=','products.id')
							->join('product_attributes_text','product_attributes_text.product_id','=','products.id')
							->join(DB::raw('product_attributes_multiselect as pam'),'pam.product_id','=','products.id')
							->join(DB::raw('product_attributes_select_options as paso'),'paso.id','=','pam.product_attributes_select_option_id')
							->join(DB::raw('product_attributes as pa1'),'pa1.id','=','product_attributes_varchar.product_attribute_id')
							->join(DB::raw('product_attributes as pa2'),'pa2.id','=','product_attributes_text.product_attribute_id')
							->join(DB::raw('product_attributes as pa3'),'pa3.id','=','paso.product_attribute_id')							
							->join(DB::raw('product_media_map as pmm'), 'pmm.product_id','=','products.id')
							->join('media','media.id','=','pmm.media_id')
							->join(DB::raw('product_pricing as pp'),'pp.product_id','=','products.id')
							->join(DB::raw('product_vendor_locations as pvl'),'pvl.product_id','=','products.id')
							->join(DB::raw('vendor_locations as vl'),'vl.id','=','pvl.vendor_location_id')
							->join('locations','locations.id','=','vl.location_id')
							->leftJoin(DB::raw('product_tag_map as ptm'),'ptm.product_id','=','products.id')
							->leftJoin('tags','tags.id','=','ptm.tag_id')
							->where('pvl.status','Active')
							->where('pa1.alias','short_description')
							->orWhere('pa2.alias','experience_info')
							->orWhere('pa3.alias','cuisines')
							->whereIn('locations.name',$arrData['arrLocation'])
							->whereIn('tags.name',$arrData['arrTags'])
							->whereIn('paso.option',$arrData['arrCuisine'])
							->whereBetween('pp.price',array($arrData['minPrice'], $arrData['maxPrice']))
							->select(DB::raw('product_attributes_varchar.attribute_value as title, product_attributes_text.attribute_value as description,
											pp.price, media.file as image'))
							->get();
							
		//array to store the product ids
		$arrProduct = array();
		//array to store final result
		$arrData = array();
		
		$arrData['resultCount'] = 0;
		
		#query executed successfully
		if($experinceResult) {
			#initializing the total number of matching rows returned
			$arrData['resultCount'] = count($experinceResult);
			
			#initializing the array of products
			foreach($experience as $row) {
				$arrProduct[] = $row->id;
			}
			#reading the product ratings detail
			$arrRatings = $this->findRatingByProduct($arrExperience);
			
			foreach($experience as $row) {
				$arrData[] = array(
									'id' => $row->id,
									'title' => $row->title,
									'description' => substr(strip_tags($row->description),0,20),
									'price' => $row->price,
									'image' => $row->image,
									'averageRating' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['averageRating']:0,
									'totalRating' => array_key_exists($row->id, $arrRatings) ? $arrRatings[$row->id]['totalRating']:0,
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
	
	
}
//end of class Search
//end of file WowTables\Http\Models\E