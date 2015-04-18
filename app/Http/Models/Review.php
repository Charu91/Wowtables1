<?php namespace WowTables\Http\Models;

use DB;

class Review {
	
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
	public static function findRatingByVendors($arrVendor){
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
	 * Reads the ratings of the product matching the vendors
	 * in the passed array.
	 * 
	 * @access	public
	 * @static	true
	 * @param	array 	$arrProduct
	 * @return	array
	 * @since	1.0.0
	 */
	public static function findRatingByProduct($arrProduct){
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
	 * Calculates the 
	 */
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
														'totalRating' => $total_ratings
													);
		}		
		return $arrRating;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * 
	 */
	public static function getVendorLocationRatingDetails($vendorID, $start = NULL, $limit = NULL) {
		$strQuery = DB::table(DB::raw('vendor_location_reviews as vlr'))
						->join('users','users.id','=', 'vlr.user_id')
						->where('vlr.vendor_location_id',$vendorID)
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
										'review' => $row->review,
										'rating' => $row->rating,
										'created_at' => $row->created_at
									);
			}
		}
		return $arrReviewDetail;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Reads the detail of the reviews given to 
	 * a particular product.
	 * 
	 * @static	true
	 * @access	public
	 * @param	integer 	$productID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function readPoductReviews($productID) {
		//query to read product reviews
		$strQuery = DB::table(DB::raw('product_reviews as pr'))
						->join('users','users.id','=', 'pr.user_id')
						->where('pr.product_id',$productID)
						->where('pr.status','approved')
						->select(DB::raw('AVG(rating) as avg_rating, COUNT(*) as total_ratings'),
								'users.id','users.full_name','pr.review','pr.rating','pr.created_at')
						->get();
		
		//array to store the result
		$arrReviewDetail = array();
		$arrReviewDetail['reviews'] = array();
		
		//initializing the results
		if($strQuery) {
			foreach($strQuery as $row) {
				$arrReviewDetail['avg_rating'] = $row->avg_rating;
				$arrReviewDetail['total_rating'] = $row->total_ratings;
				if(!is_null($row->id)) {
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
		}
		else {
				$arrReviewDetail['avg_rating'] = 0.00;
				$arrReviewDetail['total_rating'] = 0.00;
				$arrReviewDetail['reviews'][] = array();				
		}
		return $arrReviewDetail;		
	}	
}
//end of class Review
//endo of file WowTables\Http\Models\Review.php