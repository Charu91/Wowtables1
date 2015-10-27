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
		//array to store the various hashes
		$arrHashes = array();

		//splitting the guest name 
		$arrName = explode(" ",$data['guestName']);

		//creating the payhash
		$payHashString = Config::get('constants.PAYU_MERCHANT_ID') .'|'. $data['reservationID'] .'|'. 
							$data['amount'] .'|'. $data['shortDescription'] . '|' . $arrName[0] .'|'.
							$data['email'] .'|'. "||||||||||". Config::get('constants.PAYU_SALT');  

		$arrHashes['paymentHash']  = hash("sha512", $payHashString);

		//payment related details for mobile sdk hash
		$cmnPaymentRelatedDetailsForMobileSdk1 = 'payment_related_details_for_mobile_sdk';
		$strDetailsForMobileSdk = Config::get('constants.PAYU_MERCHANT_ID')  . '|' . $cmnPaymentRelatedDetailsForMobileSdk1 . '|default|' . Config::get('constants.PAYU_SALT');
     	$detailsForMobileSdk1 = strtolower(hash('sha512', $strDetailsForMobileSdk));
     	$arrHashes['paymentRelatedDetailsForMobileSDKHash'] = $detailsForMobileSdk1;

     	if($data['userCredentials'] != NULL  && $data['userCredentials'] == '') {
     		//creating the user card hash
     		$cmnNameGetUserCard = 'get_user_cards';
            $strGetUserCardHash = Config::get('constants.PAYU_MERCHANT_ID')  . '|' . $cmnNameGetUserCard . '|' . $userCredentials . '|' . Config::get('constants.PAYU_SALT');
            $getUserCardHash = strtolower(hash('sha512', $strGetUserCardHash));
            $arrHashes['getUserCardsHash'] = $getUserCardHash;

            //creating save user card hash
            $cmnNameSaveUserCard = 'save_user_card';
           	$strSaveUserCardHash = Config::get('constants.PAYU_MERCHANT_ID')  . '|' . $cmnNameSaveUserCard . '|' . $userCredentials . '|' . Config::get('constants.PAYU_SALT') ;
           	$saveUserCardHash = strtolower(hash('sha512', $strSaveUserCardHash));
           	$arrHashes['saveUserCardHash'] = $saveUserCardHash;
     	}

		//saving the information into DB
		/*
		DB::table('payu_mobile_hash')
			->insert([
				'reservation_id'  => $data['reservationID'],
				'hash'            => $secureHash
				]); */

		return $arrHashes;
		
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