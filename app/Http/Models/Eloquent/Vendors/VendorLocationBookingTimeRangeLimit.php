<?php namespace WowTables\Http\Models\Eloquent\Vendors;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
class VendorLocationBookingTimeRangeLimit extends Model {
	
	/**
	 * Table to be used by this model.
	 * 
	 * @var		string
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $table = 'vendor_location_booking_time_range_limits';
	
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
	 * 
	 */
	public static function checkBookingTimeRangeLimits($arrData) {
		$queryResult = Self::where('vendor_location_id',$arrData['vendorLocationID'])
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
//end of class VendorLocationBookingTimeRangeLimit
//end of file VendorLocationBookingTimeRangeLimit.php