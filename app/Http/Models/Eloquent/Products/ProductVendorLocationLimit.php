<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;

/**
 * Model class ProductVendorLocationLimit
 * 
 * @package	Wowtables
 * @since	1.0.0
 * @version	1.0.0
 * @author	Parth Shukla<parthshukla@ahex.co.in>
 */
class ProductVendorLocationLimit extends Model {
	
	/**
	 * Table to be used 
	 * 
	 * @var		string
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $table = 'product_vendor_locations_limits';
	
	/**
	 * Column that cannot be mass assigned.
	 * 
	 * @var		array
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $guarded = array('id');
	
	
	//-----------------------------------------------------------------
	
	/**
	 * Returns the people reservation limits for a procuct 
	 * vendor location.
	 * 
	 * @static	true
	 * @access	public
	 * @param	integer	$productVendorLocationID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getProductPeopleReservationLimit($productVendorLocationID) {
		$query = self::where('product_vendor_location_id',$productVendorLocationID)->first();
		
		//array to store data
		$arrData = array();
		if($query) {
			$arrData = array(
							'min_people' => $query->min_people_per_reservation,
							'max_people' => $query->max_people_per_reservation,
							'increment' => $query->min_people_increments 
						);
		}
		
		return $arrData;
	}
}
//end of class ProductVendorLocationLimit
//end of file WowTables\Http\Models\Eloquent\Products\ProductVendorLocationLimit.php