<?php namespace WowTables\Http\Models\Eloquent;

//use DB;
//use Config;
use Illuminate\Database\Eloquent\Model;


/**
 * Model class ReservationAddonsVariantsDetails.
 * 
 * @package		wowtables
 * @version		1.0.0
 * @since		1.0.0
 * @author		Parth Shukla <parthshukla@ahex.co.in>
 */
class ReservationAddonsVariantsDetails extends Model {
	
	/**
	 * Table to be used by this model.
	 * 
	 * @access	protected
	 * @var		string
	 * @since	1.0.0
	 */
	protected $table = 'reservation_addons_variants_details';
	
	/**
	 * Columns that cannot be mass-assigned.
	 * 
	 * @access	protected
	 * @var		string
	 * @since	1.0.0
	 */
	protected $guarded = array('id');
	
	//-----------------------------------------------------------------
	
	
}
//end of class ReservationAddonsVariantsDetails
//end of file ReservationAddonsVariantsDetails.php