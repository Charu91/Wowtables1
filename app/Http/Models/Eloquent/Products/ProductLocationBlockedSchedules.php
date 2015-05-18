<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;

use DB;
/**
 * 
 */
class ProductLocationBlockedSchedules extends Model {
	/**
	 * Table to be used by this model.
	 * 
	 * @var		string
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $table = 'product_vendor_location_blocked_schedules';
	
	/**
	 * Columns that cannot be mass-assigned.
	 * 
	 * @var		array
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $guarded = array('id');
	
	//-----------------------------------------------------------------
	
	/**
	 * Checks whether the passed date is available for 
	 * booking.
	 * 
	 * @static	true
	 * @param	string	$date
	 * @access	public
	 * @since	1.0.0 
	 */
	public static function isDateBlocked($productVendorLocationID, $date) {
		//query to check if current date is available
		$queryResult = self::where(DB::raw('DATE(block_date)'),'=',$date)
						->where('product_vendor_location_id',$productVendorLocationID)
						->get();
		
		if($queryResult && count($queryResult) > 0) {
			return TRUE;
		}
		return FALSE;			
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * 
	 */
	public static function getBlockedDate($arrLocation) {
		//query to read all the block dates for the locations
		$queryResult = SELF::whereIn('product_vendor_location_id',$arrLocation)
						->select('id','product_vendor_location_id','block_date')
						->get();
		//array to store the block dates
		$arrBlockedDate = array();
		
		foreach($queryResult as $row){
			if(!array_key_exists($row->vendor_location_id, $arrBlockedDate)) {
				$arrBlockedDate[$row->vendor_location_id] = array($row->block_date);
			}
			else {
				$arrBlockedDate[$row->vendor_location_id][] = $row->block_date;
			}
		}
		
		return $arrBlockedDate;
	}
}
//end of class VendorLocationBlockedSchedules
//end of files VendorLocationBlockedSchedules.php