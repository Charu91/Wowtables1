<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
/**
 * Model class PasswordRequest
 * 
 * @package	wowtables
 * @since	1.0.0
 * @version	1.0.0
 * @author	Parth Shukla <parthshukla@ahex.co.in>
 */

class PasswordRequest extends Model {

	/**
	 * Table to be used 
	 * 
	 * @var		string
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $table = 'password_request';
	
 	/**
	 * Column that cannot be mass assigned.
	 * 
	 * @var		array
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $guarded = array('id');

}
//end of class PasswordRequest
//end of file WowTables\Http\Models\Eloquent\PasswordRequest.php