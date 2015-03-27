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
}
//end of class Search
//end of file WowTables\Http\Models\E