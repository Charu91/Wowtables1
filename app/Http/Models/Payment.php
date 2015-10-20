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

	public  static $arrRules = array(
										'guestName' 		=> 'required',
										'reservationID' 	=> 'required|exists:reservation_details,id',									
										'shortDescription' 	=> 'required',
										'amount' 			=> 'required|integer',	
										'email' 			=> 'required|email|max:255'
									);
	//-------------------------------------------------------------------------

	/**
	 * Generates the hash for PayU.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public static function generateMobilePayUHash($data) {

		//splitting the guest name 
		$arrName = explode(" ",$data['guestName']);

		$payHashString = Config::get('PAYU_MERCHANT_ID') .'|'. $data['reservationID'] .'|'. 
							$data['amount'] .'|'. $data['shortDescription'] . '|' . $arrName[0] .'|'.
							$data['email'] .'|'. "||||||||||". Config::get('PAYU_SALT');  

		$secureHash  = hash("sha512", $payHashString);

		//saving the information into DB
		DB::table('payu_mobile_hash')
			->insert([
				'reservation_id'  => $data['reservationID'],
				'hash'            => $secureHash
				]);

		return $secureHash;
		
	}
	//-------------------------------------------------------------------------

	/**
	 * Handle the request to generate mobile PayUHash Code by using data sent by 
	 * user and return the newly generated code.
	 * 
	 * @access public
	 * @since  1.0.0
	 */
	public static function getPayUHash($data) {

		$secureCode = self::generateMobilePayUHash($data);

		return $secureCode;
	}

	//-------------------------------------------------------------------------

	/**
	 * Matches the passed payu hash with the one stored in the 
	 * database.
	 *
	 * @access  public
	 * @param   array    $data
	 * @since   1.0.0
	 */
	public function matchPayUHash($reservationID, $hash) {

		$dbResult = DB::table('payu_mobile_hash')
						->where('reservation_id','=',$reservationID)
						->where('hash','=',$hash)
						->select('id')
						->first();

		if($dbResult && $dbResult->id > 0) {
			return TRUE;
		}

		return FALSE;
	}

}
// end of class Payment
// end of file Payment.php