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
										'firstname' 		=> 'required',
										'txnID' 			=> 'required|exists:reservation_details,id',									
										'productInfo' 		=> 'required',
										'amount' 			=> 'required|numeric',	
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
		/*$payHashString = Config::get('constants.PAYU_MERCHANT_ID') .'|'. $data['reservationID'] .'|'. 
							$data['amount'] .'|'. $data['shortDescription'] . '|' . $arrName[0] .'|'.
							$data['email'] .'|'. "||||||||||". Config::get('constants.PAYU_SALT'); */

		//creating the payhash
		$payHashString = Config::get('constants.PAYU_MERCHANT_ID') . '|' . (string)$data['reservationID'] . '|' 
							. (string)$data['amount']  . '|' . $data['shortDescription']  . '|' 
							. $arrName[0] . '|' . $data['email'] . '|' 
							. '' . '|' . '' . '|' . '' . '|' 
							. '' . '|' . '' . '||||||' 
							. Config::get('constants.PAYU_SALT');

		$arrHashes['paymentHash']  = strtolower(hash("sha512", $payHashString));

		//payment related details for mobile sdk hash
		$cmnPaymentRelatedDetailsForMobileSdk1 = 'payment_related_details_for_mobile_sdk';
		$strDetailsForMobileSdk = Config::get('constants.PAYU_MERCHANT_ID')  . '|' . $cmnPaymentRelatedDetailsForMobileSdk1 . '|default|' . Config::get('constants.PAYU_SALT');
     	$detailsForMobileSdk1 = strtolower(hash('sha512', $strDetailsForMobileSdk));
     	$arrHashes['paymentRelatedDetailsForMobileSDKHash'] = $detailsForMobileSdk1;

     	if($data['userCredentials'] != NULL  && $data['userCredentials'] != '') {
     		//creating the user card hash
     		$cmnNameGetUserCard = 'get_user_cards';
            $strGetUserCardHash = Config::get('constants.PAYU_MERCHANT_ID')  . '|' . $cmnNameGetUserCard . '|' . $data['userCredentials'] . '|' . Config::get('constants.PAYU_SALT');
            $getUserCardHash = strtolower(hash('sha512', $strGetUserCardHash));
            $arrHashes['getUserCardsHash'] = $getUserCardHash;

            //creating save user card hash
            $cmnNameSaveUserCard = 'save_user_card';
           	$strSaveUserCardHash = Config::get('constants.PAYU_MERCHANT_ID')  . '|' . $cmnNameSaveUserCard . '|' . $data['userCredentials'] . '|' . Config::get('constants.PAYU_SALT') ;
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

	//-------------------------------------------------------------------------

	/**
	 *
	 */
	public static function getHashes($arrData) {
		
		$udf1 = (empty($arrData['udf1'])) ? '':$arrData['udf1'];
		$udf2 = (empty($arrData['udf2'])) ? '':$arrData['udf2'];
		$udf3 = (empty($arrData['udf3'])) ? '':$arrData['udf3'];
		$udf4 = (empty($arrData['udf4'])) ? '':$arrData['udf4'];
		$udf5 = (empty($arrData['udf5'])) ? '':$arrData['udf5'];

		$key = Config::get('constants.PAYU_MERCHANT_ID');
    	$salt = Config::get('constants.PAYU_SALT');

    	$payhashStr = $key . '|' . $arrData['txnID'] . '|' .$arrData['amount']  . '|' 
    				.$arrData['productInfo']  . '|' . $arrData['firstname'] . '|' 
    				. $arrData['email'] . '|' . $udf1 . '|' . $udf2 . '|' 
    				. $udf3 . '|' . $udf4 . '|' . $udf5 . '||||||' 
    				. $salt;

    	$paymentHash = strtolower(hash('sha512', $payhashStr));
    	$arr['payment_hash'] = $paymentHash;

    	$cmnNameMerchantCodes = 'get_merchant_ibibo_codes';
    	$merchantCodesHash_str = $key . '|' . $cmnNameMerchantCodes . '|default|' . $salt ;
    	$merchantCodesHash = strtolower(hash('sha512', $merchantCodesHash_str));
    	$arr['get_merchant_ibibo_codes_hash'] = $merchantCodesHash;

    	$cmnMobileSdk = 'vas_for_mobile_sdk';
    	$mobileSdk_str = $key . '|' . $cmnMobileSdk . '|default|' . $salt;
    	$mobileSdk = strtolower(hash('sha512', $mobileSdk_str));
    	$arr['vas_for_mobile_sdk_hash'] = $mobileSdk;

    	$cmnPaymentRelatedDetailsForMobileSdk1 = 'payment_related_details_for_mobile_sdk';
    	$detailsForMobileSdk_str1 = $key  . '|' . $cmnPaymentRelatedDetailsForMobileSdk1 . '|default|' . $salt ;
    	$detailsForMobileSdk1 = strtolower(hash('sha512', $detailsForMobileSdk_str1));
    	$arr['payment_related_details_for_mobile_sdk_hash'] = $detailsForMobileSdk1;

    	if($arrData['user_credentials'] != NULL && $arrData['user_credentials'] != '') {
    		$cmnNameDeleteCard = 'delete_user_card';
          	$deleteHash_str = $key  . '|' . $cmnNameDeleteCard . '|' . $arrData['user_credentials'] . '|' . $salt ;
          	$deleteHash = strtolower(hash('sha512', $deleteHash_str));
          	$arr['delete_user_card_hash'] = $deleteHash;
          
          	$cmnNameGetUserCard = 'get_user_cards';
          	$getUserCardHash_str = $key  . '|' . $cmnNameGetUserCard . '|' . $arrData['user_credentials'] . '|' . $salt ;
          	$getUserCardHash = strtolower(hash('sha512', $getUserCardHash_str));
          	$arr['get_user_cards_hash'] = $getUserCardHash;
          
          	$cmnNameEditUserCard = 'edit_user_card';
          	$editUserCardHash_str = $key  . '|' . $cmnNameEditUserCard . '|' . $arrData['user_credentials'] . '|' . $salt ;
          	$editUserCardHash = strtolower(hash('sha512', $editUserCardHash_str));
          	$arr['edit_user_card_hash'] = $editUserCardHash;
          
          	$cmnNameSaveUserCard = 'save_user_card';
          	$saveUserCardHash_str = $key  . '|' . $cmnNameSaveUserCard . '|' . $arrData['user_credentials'] . '|' . $salt ;
          	$saveUserCardHash = strtolower(hash('sha512', $saveUserCardHash_str));
          	$arr['save_user_card_hash'] = $saveUserCardHash;
          
          	$cmnPaymentRelatedDetailsForMobileSdk = 'payment_related_details_for_mobile_sdk';
          	$detailsForMobileSdk_str = $key  . '|' . $cmnPaymentRelatedDetailsForMobileSdk . '|' . $arrData['user_credentials'] . '|' . $salt ;
          	$detailsForMobileSdk = strtolower(hash('sha512', $detailsForMobileSdk_str));
          	$arr['payment_related_details_for_mobile_sdk_hash'] = $detailsForMobileSdk;
    	}

    	if ($arrData['offerKey']!=NULL && !empty($arrData['offerKey'])) {
    		$cmnCheckOfferStatus = 'check_offer_status';
    		$checkOfferStatus_str = $key  . '|' . $cmnCheckOfferStatus . '|' . $arrData['offerKey'] . '|' . $salt ;
            $checkOfferStatus = strtolower(hash('sha512', $checkOfferStatus_str));
      		$arr['check_offer_status_hash']=$checkOfferStatus;
    	}

    	if ($arrData['cardBin']!=NULL && !empty($arrData['cardBin'])) {
    		$cmnCheckIsDomestic = 'check_isDomestic';
            $checkIsDomestic_str = $key  . '|' . $cmnCheckIsDomestic . '|' . $arrData['cardBin'] . '|' . $salt ;
            $checkIsDomestic = strtolower(hash('sha512', $checkIsDomestic_str));
      		$arr['check_isDomestic_hash']=$checkIsDomestic;
    	}

    return $arr;
  }

  //---------------------------------------------------------------------------


  function checkNull($value) {
    if ($value == null) {
      return '';
    } else {
      return $value;
    }
  }

}
// end of class Payment
// end of file Payment.php