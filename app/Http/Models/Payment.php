<?php namespace WowTables\Http\Models;

use Config;
use DB;

/**
 * Payment model class.
 *
 * @version   1.0.0
 * @author    Parth Shukla <parthshukla@ahex.co.in>
 */
class Payment {

	/**
	 * Generates the hash for PayU.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function generateMobilePayUHash($data) {

		//splitting the guest name 
		$arrName = explode(" ",$data['guestName']);

		$payHash = Config::get('PAYU_MERCHANT_ID') .'|'. $data['reservationID'] .'|'. 
					$data['amount'] .'|'. $data['shortDescription'] . '|' . $arrName[0] .'|'.
					$data['email'] .'|'. "|||||||||||". Config::get('PAYU_SALT');  
	}
}
// end of class Payment
// end of file Payment.php