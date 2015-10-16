<?php namespace WowTables\Http\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * Transaction class.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Parth Shukla <parthshukla@ahex.co.in>
 */
class Transaction extends Model {

	/**
	 * Rules for validating a transaction.
	 *
	 * @access  public
	 * @param   array
	 * @since   1.0.0
	 */
	public static $arrRules = array(
						'transaction_number'	=> 'required',
						'reservation_id'		=> 'required|exists:reservation_details,id',
						'amount_paid'			=> 'required',
						'transaction_date'		=> 'required|date|date_format:Y-m-d',
						'transaction_details'	=> 'required|max:2048',
						'response_code'			=> 'required|max:255',
						'response_message'		=> 'required|max:2048',
						'source_type'			=> 'required'
					);

	/**
     * The database table used by the model.
     *
     * @access  protected
     * @var     string
     */
    protected $table = 'transactions_details';

    /**
     * Disable Laravel's Eloquent timestamps for this model.
     *
     */
    public $timestamps = false;

    /**
     * The attributes that are not mass assignable.
     *
     * @access   protected
     * @var      array
     */
    protected $guarded = array('id');
}
//end of class Transaction
// end of file Transaction.php