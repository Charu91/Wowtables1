<?php namespace WowTables\Http\Models\Eloquent\Products;

use Illuminate\Database\Eloquent\Model;
 
/**
 * Model class ProductVendorLocationBlockSchedule
 * 
 * @version		1.0.0
 * @since		1.0.0
 * @author		Parth Shukla<parthshukla@ahex.co.in> 
 */
class ProductVendorLocationBookingTimeRangeLimit extends Model {
	
	/**
	 * Table to be used by this model.
	 * 
	 * @access	protected
	 * @var		string
	 * @since	1.0.0
	 */
	protected $table = 'product_vendor_location_booking_time_range_limits';
	
	/**
	 * Columns that cannot be mass assigned.
	 * 
	 * @access	protected
	 * @var		array
	 * @since	1.0.0
	 */
	protected $guarded = array('id');
	
	//-----------------------------------------------------------------
	
	/**
	 * Reads the booking time range limits details based on
	 * pass parameters.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array 	$arrData
	 * @return	array
	 * @since	1.0.0
	 */
	public static function checkBookingTimeRangeLimits($arrData) {
		$queryResult = Self::where('product_vendor_location_id',$arrData['vendorLocationID'])
							->where('day',$arrData['reservationDay'])
							->orWhere('date',$arrData['reservationDate'])
							->get();
		
		//array to save the details
		$arrData = array();
		
		foreach($queryResult as $row) {
			$arrData[] = array(
								'id' => $row->id,
								'product_vendor_location_id' => $row->product_vendor_loction_id,
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
} 
//end of class ProductVendorLocationBlockSchedule
//end of file app/Http/Api/Models/Eloquent/Products/ProductVendorLocationBlockSchedule.php