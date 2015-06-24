<?php namespace WowTables\Http\Models\Eloquent;

//use DB;
use Config;
use Illuminate\Database\Eloquent\Model;
use URL;
use WowTables\Http\Models\Eloquent\ReservationAddonsVariantsDetails;
use Mail;
use DB;
use WowTables\Http\Models\Eloquent\Products\Product;
use WowTables\Http\Models\Eloquent\Vendors\Locations\VendorLocation;
use Mailchimp;

/**
 * Model class Reservation.
 * 
 * @package		wowtables
 * @version		1.0.0
 * @since		1.0.0
 * @author		Parth Shukla <parthshukla@ahex.co.in>
 */
class ReservationDetails extends Model {	

	/**
	 * Table to be used by this model.
	 * 
	 * @var		string
	 * @access	protected
	 * @since	1.0.0
	 */
	protected $table = 'reservation_details';
	
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
	 * Writes the details of the reservation in the DB.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array 	$arrData
	 * @return	boolean
	 * @since	1.0.0
	 */
	public static function addReservationDetails($arrData, $userID, $objMailChimp, $userType='website_user') {
		//creating a new instance of the table
		$reservation = new ReservationDetails;
		
		//initializing the data
		$reservation->reservation_status = 'new';
		$reservation->reservation_date = $arrData['reservationDate'];
		$reservation->reservation_time = $arrData['reservationTime'];
		$reservation->no_of_persons = $arrData['partySize'];		
		$reservation->guest_name = $arrData['guestName'];
		$reservation->guest_email = $arrData['guestEmail'];
		$reservation->guest_phone = $arrData['phone'];
		$reservation->reservation_type = $arrData['reservationType'];
		$reservation->order_amount = 0;
		$reservation->user_id = $userID;
		$reservation->added_by = $userType;
		
		//setting up the variables that may be present
		if(isset($arrData['specialRequest'])) {
			$reservation->special_request = $arrData['specialRequest'];
		}		
		
		if(isset($arrData['giftCardID'])) {
			$reservation->giftcard_id = $arrData['giftCardID'];
		}
		
		//setting up the value of the location id as per type
 		if($arrData['reservationType'] == 'alacarte') {
 			
			//reading the resturants detail
			$aLaCarteDetail = self::readVendorDetailByLocationID($arrData['vendorLocationID']);
			
			$reservation->points_awarded = $aLaCarteDetail['reward_point'];
 			$reservation->vendor_location_id = $arrData['vendorLocationID'];
			$reservation->product_vendor_location_id = 0;
 		}
		else if($arrData['reservationType'] == 'experience') {
			
			//reading the product detail
			$productDetail = self::readProductDetailByProductVendorLocationID($arrData['vendorLocationID']);
			
			$reservation->points_awarded = $productDetail['reward_point'];
			$reservation->vendor_location_id = 0;
			$reservation->product_vendor_location_id = $arrData['vendorLocationID'];

		}
		#saving the information into the DB
		$savedData = $reservation->save();
		
		if($savedData) {			
			
			$reservation_id = ReservationDetails::where('user_id', '=', $userID)
													  ->where('reservation_date', '=', $arrData['reservationDate'])
													  ->where('reservation_time', '=', $arrData['reservationTime'])
													  ->select('id')
													  ->first();				

			if($arrData['reservationType'] == 'alacarte') {
				
				$arrResponse['status'] = Config::get('constants.API_SUCCESS');
				
				$arrResponse['data']['reservation_id'] = $reservation_id['id']; 
				$arrResponse['data']['name'] = $aLaCarteDetail['name'];
				$arrResponse['data']['url'] = URL::to('/').'/alacarte/'.$aLaCarteDetail['vl_id'];
				$arrResponse['data']['reservationDate'] = $arrData['reservationDate'];
				$arrResponse['data']['reservationTime'] = $arrData['reservationTime'];
				$arrResponse['data']['partySize'] = $arrData['partySize'];
				$arrResponse['data']['reward_point'] = $aLaCarteDetail['reward_point'];	

				//Increment the Reservation count by 1
				$reservationCount = self::incrementReservationCount($userID, $arrData['reservationType'] );  
				
				//Insert record for new reward point
				$storeRewardPoint = self::storeRewardPoint($userID, $aLaCarteDetail['reward_point'], $reservation_id['id']);

				//Mail by mailchimp
				$mailStatus = self::mailByMailChimp( $arrData, $userID ,$objMailChimp );	 		

				$zoho_data = array(
					                    'Name' => $arrData['guestName'],
					                    'Email_ids' => $arrData['guestEmail'],
					                    'Contact' => $arrData['phone'],
					                    'Experience_Title' => $aLaCarteDetail['name'].' - Ala Carte',
					                    'No_of_People' => $arrData['partySize'],
					                    'Date_of_Visit' => date('d-M-Y', strtotime($arrData['reservationDate'])),
					                    'Time' => date("G:ia", strtotime($arrData['reservationTime'])),
					                    //'Alternate_ID' =>  'A'.sprintf("%06d",$arrResponse['data']['reservationID']),//sprintf("%06d",$this->data['order_id1']);
					                    'Occasion' => (isset($arrData['specialRequest']) && !empty($arrData['specialRequest'])) ? $arrData['specialRequest'] : "" ,
					                    'Type' => "Alacarte",
					                    'API_added' => 'Mobile',
					                    //'GIU_Membership_ID' => '1001010',
					                    'Outlet' => $aLaCarteDetail['location'],
					                    //'Points_Notes'=>'test',
					                    'AR_Confirmation_ID'=>'0',
					                    'Auto_Reservation'=>'Not available',
					                    //'telecampaign' => $campaign_id,
					                    //'total_no_of_reservations'=> '1',
					                    'Calling_option' => 'No'
                					  );			
				//Calling zoho api method
				$zoho_res = Self::zohoAddBooking($zoho_data);
				
				//Call zoho send mail method
				Self::zohoSendMail($zoho_res, $zoho_data, $reservation_id['id'], $arrData);
						
			}
			else if($arrData['reservationType'] == 'experience') {
				
				$arrResponse['status'] = Config::get('constants.API_SUCCESS');
				if(array_key_exists('addon', $arrData) && !empty($arrData['addon'])) {
					self::addReservationAddonDetails($reservation->id, $arrData['addon']);
				}				
				
				$arrResponse['data']['reservation_id'] = $reservation_id['id']; 
				$arrResponse['data']['name'] = $productDetail['name'];
				$arrResponse['data']['url'] = URL::to('/').'/experience/'.$productDetail['id'];
				$arrResponse['data']['reservationDate'] = $arrData['reservationDate'];
				$arrResponse['data']['reservationTime'] = $arrData['reservationTime'];
				$arrResponse['data']['partySize'] = $arrData['partySize'];
				$arrResponse['data']['reward_point'] = $productDetail['reward_point'];	

				//Increment the Reservation count by 1
				$reservationCount = self::incrementReservationCount($userID, $arrData['reservationType'] );  
				
				//Insert record for new reward point
				$storeRewardPoint = self::storeRewardPoint($userID, $productDetail['reward_point'], $reservation_id['id']);	 
				
				//Mail by mailchimp
				$mailStatus = self::mailByMailChimp( $arrData, $userID ,$objMailChimp );

				$zoho_data = array(
					                    'Name' => $arrData['guestName'],
					                    'Email_ids' => $arrData['guestEmail'],
					                    'Contact' => $arrData['phone'],
					                    'Experience_Title' => $productDetail['vendor_name'].' - '.$productDetail['descriptive_title'],
					                    'No_of_People' => $arrData['partySize'],
					                    'Date_of_Visit' => date('d-M-Y', strtotime($arrData['reservationDate'])),
					                    'Time' => date("G:ia", strtotime($arrData['reservationTime'])),
					                    //'Alternate_ID' =>  'E'.sprintf("%06d",$arrResponse['data']['reservationID']),//sprintf("%06d",$this->data['order_id1']);
					                    'Occasion' => (isset($arrData['specialRequest']) && !empty($arrData['specialRequest'])) ? $arrData['specialRequest'] : "" ,
					                    'Type' => "Experience",
					                    'API_added' => 'Mobile',
					                    //'GIU_Membership_ID' => '1001010',
					                    'Outlet' => $productDetail['location'],
					                    //'Points_Notes'=>'test',
					                    'AR_Confirmation_ID'=>'0',
					                    'Auto_Reservation'=>'Not available',
					                    //'telecampaign' => $campaign_id,
					                    //'total_no_of_reservations'=> '1',
					                    'Calling_option' => 'No'
                						);
				
				//Calling zoho api method
				$zoho_res = Self::zohoAddBooking($zoho_data);

				//Call zoho send mail method
				Self::zohoSendMail($zoho_res, $zoho_data, $reservation_id['id'], $arrData);
			}
			else {
				$arrResponse['status'] = Config::get('constants.API_ERROR');
			}
			return $arrResponse;
		}
		
		return FALSE;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Reads the total number of reservations done againts a
	 * particular vendor location and type.
	 * 
	 * @static	true
	 * @access	public
	 * @param	array 	$arrData
	 * @return	integer
	 * @since	1.0.0 
	 */
	public static function getReservationCount($arrData) {
		$queryResult = Self::where('vendor_location_id',$arrData['vendorLocationID'])
						->where('reservation_date',$arrData['reservationDate'])
						->where('reservation_type',$arrData['reservationType'])
						->whereIn('reservation_status',array('new','edited'))
						->groupBy('vendor_location_id')
						->select(\DB::raw('SUM(no_of_persons) as person_count'))
						->first();
		
		if($queryResult) {
			return $queryResult->person_count;
		}
		return 0;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Updates the status of the reservation to cancel.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer	$reservationID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function cancelReservation($reservationID) {
		//array to hold response
		$arrResponse = array();
		
		$queryResult = Self::where('id',$reservationID)
							//->where('user_id', $userID)
							->where('reservation_status','!=','cancel')
							->first();
		
		if($queryResult) {
			$reservation = Self::find($reservationID);
			$reservation->reservation_status = 'cancel';
			$reservation->save();

			//Remove the points earned for the cancelled reservation
			$cancelReward = self::cancelRewardPoint( $reservationID );

			//Decrement the reservation count
			$cancelRewardCount = self::decrementReservationCount( $reservationID );

			//Send mail for cancel reservation
			$mailchimpStatus = self::sendMailchimp( $reservationID );

			
			$arrResponse['status'] = Config::get('constants.API_SUCCESS');
		}
		else {
			$arrResponse['status'] = Config::get('constants.API_ERROR');
			$arrResponse['msg'] = 'Sorry. No Such record exists.';
		}
		
		return $arrResponse;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Updates the reservation details stored in DB.
	 * 
	 * @access	public
	 * @param	array 	$arrData
	 * @return	array
	 * @since	1.0.0
	 */
	public static function updateReservationDetail($arrData) {
		//array to hold response
		$arrResponse = array();
		
		$queryResult = Self::where('id', $arrData['reservationID'])
						//->where('user_id',$arrData[])
						//->where('reservation_date',)
						->whereIn('reservation_status',array('new','edited'))
						->first();
		
		if($queryResult) {
			$reservation = Self::find($arrData['reservationID']);
			//initializing the data
			$reservation->reservation_status = 'edited';
			$reservation->reservation_date = $arrData['reservationDate'];
			$reservation->reservation_time = $arrData['reservationTime'];
			$reservation->no_of_persons = $arrData['partySize'];
			$reservation->vendor_location_id = $arrData['vendorLocationID'];
			$reservation->guest_name = $arrData['guestName'];
			$reservation->guest_email = $arrData['guestEmail'];
			$reservation->guest_phone = $arrData['phone'];
			$reservation->reservation_type = $arrData['reservationType'];
			
			//setting up the variables that may be present
			if(isset($arrData['specialRequest'])) {
				$reservation->special_request = $arrData['specialRequest'];
			}
		
			if(isset($arrData['addedBy'])) {
				$reservation->added_by = $arrData['addedBy'];
			}

			//setting up the value of the location id as per type
 			if($arrData['reservationType'] == 'alacarte') {
 				$reservation->vendor_location_id = $arrData['vendorLocationID'];
				$reservation->product_vendor_location_id = 0;
 			}
			else if($arrData['reservationType'] == 'experience') {
				$reservation->vendor_location_id = 0;
				$reservation->product_vendor_location_id = $arrData['vendorLocationID'];
				if(array_key_exists('addon', $arrData) && !empty($arrData['addon'])) {
					self::updateReservationAddonDetails($arrData['reservationID'],$arrData['addon']);
				}
			}
 		
			#saving the information into the DB
			$savedData = $reservation->save();
			//Added on 28.5.15
			$resultData = Self::where('id', $arrData['reservationID'])
						->select('reservation_type','product_vendor_location_id','vendor_location_id')
						->first();

			//print_r($resultData['product_vendor_location_id']);
			//print_r($resultData['vendor_location_id']);  
			//die();

			if($resultData['reservation_type']=='alacarte'){
				//reading the resturants detail
				$aLaCarteDetail = self::readVendorDetailByLocationID($arrData['vendorLocationID']);

				$arrResponse['data']['reservation_id'] = $arrData['reservationID']; 
				$arrResponse['data']['name'] = $aLaCarteDetail['name'];
				$arrResponse['data']['url'] = URL::to('/').'/alacarte/'.$aLaCarteDetail['vl_id'];
				$arrResponse['data']['reservationDate'] = $arrData['reservationDate'];
				$arrResponse['data']['reservationTime'] = $arrData['reservationTime'];
				$arrResponse['data']['partySize'] = $arrData['partySize'];
				$arrResponse['data']['reward_point'] = $aLaCarteDetail['reward_point'];	

			}
			else if($resultData['reservationType'] == 'experience'){
				//reading the product detail
				$productDetail = self::readProductDetailByProductVendorLocationID($arrData['vendorLocationID']);

				$arrResponse['data']['reservation_id'] = $arrData['reservationID']; 
				$arrResponse['data']['name'] = $productDetail['name'];
				$arrResponse['data']['url'] = URL::to('/').'/experience/'.$productDetail['id'];
				$arrResponse['data']['reservationDate'] = $arrData['reservationDate'];
				$arrResponse['data']['reservationTime'] = $arrData['reservationTime'];
				$arrResponse['data']['partySize'] = $arrData['partySize'];
				$arrResponse['data']['reward_point'] = $productDetail['reward_point'];

			}			

			$arrResponse['status'] = ($savedData) ? Config::get('constants.API_SUCCESS'): Config::get('constants.API_FAILED');
			
		}
		else {
			$arrResponse['status'] = Config::get('constants.API_ERROR');
		}
		
		return $arrResponse;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Reads the details of the reservation matching the passed 
	 * id.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer	$reservationID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function getActiveReservationDetail($reservationID) {
		$queryResult = Self::where('id',$reservationID)
							->whereIn('reservation_status',array('new','edited'))
							->first();
		
		//array to store response
		$arrData = array();
		
		if($queryResult) {
			$arrData['status'] = Config::get('constants.API_SUCCESS');
			$arrData['id'] = $queryResult->id;
			$arrData['userID'] = $queryResult->user_id;
			$arrData['reservationStatus'] = $queryResult->reservation_status;
			$arrData['reservationDate'] = $queryResult->reservation_date;
			$arrData['reservationTime'] = $queryResult->reservation_time;
			$arrData['numOfPersons'] = $queryResult->no_of_persons;
			$arrData['reservationType'] = $queryResult->reservation_type;
			$arrData['vendorLocationID'] = $queryResult->vendor_location_id;
			$arrData['productVendorLocationID'] = $queryResult->product_vendor_location_id;
		}
		else {
			$arrData['status'] = Config::get('constants.API_ERROR');
			$arrData['msg'] = 'Data not found.';
		}
		
		return $arrData;
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Reads the detial of the vendor by vendor location ID.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer	$vendorLocationID
	 * @return	array
	 * @since	1.0.0
	 */
	public static function readVendorDetailByLocationID($vendorLocationID) {
		//array to store the data
		$arrData = array();
		
		$queryResult = \DB::table('vendors')
						->join('vendor_locations as vl','vl.vendor_id','=','vendors.id')
						->leftJoin('vendor_location_attributes_integer as vai','vai.vendor_location_id','=','vl.id')
						->join(DB::raw('vendor_location_attributes_text as vlat'), 'vlat.vendor_location_id', '=', 'vl.id')
						->join(DB::raw('vendor_attributes as va1'), 'va1.id', '=', 'vlat.vendor_attribute_id')
						->join('vendor_attributes as va','va.id','=','vai.vendor_attribute_id')
						->join('locations as l', 'l.id', '=', 'vl.location_id') 
						->where('vl.id',$vendorLocationID)
						->where('va.alias','reward_points_per_reservation')
						->select(
								'vendors.id','vendors.name', 'l.name as location',
								'vai.attribute_value as reward_point','vl.id as vl_id',								
								DB::raw('MAX(IF(va1.alias = "short_description", vlat.attribute_value, "")) AS short_description'),
								DB::raw('MAX(IF(va1.alias = "terms_and_conditions", vlat.attribute_value, "")) AS terms_conditions')
								)
						->first();
		if($queryResult) {
			$arrData['id'] = $queryResult->id;
			$arrData['vl_id']=$queryResult->vl_id;
			$arrData['name'] = $queryResult->name;
			$arrData['reward_point'] = (empty($queryResult->reward_point))? 0.00 : $queryResult->reward_point;
			$arrData['location'] = $queryResult->location;
			$arrData['short_description'] = $queryResult->short_description;
			$arrData['terms_conditions'] = $queryResult->terms_conditions;
		}
		
		return $arrData;
	}

	//-----------------------------------------------------------------
	
	/**
	 * Reads the details of the product by vendor location ID.
	 * 
	 * @access	public
	 * @static	true
	 * @param	integer		$productVendorLocationID
	 * @return	array	
	 * @since	1.0.0
	 */
	public static function readProductDetailByProductVendorLocationID($productVendorLocationID) {
		//array to store the data
		$arrData = array();
		
		$queryResult = \DB::table('products')
						->join('product_vendor_locations as pvl','pvl.product_id','=','products.id')
						->leftJoin('product_attributes_integer as pai','pai.product_id','=','products.id')						
						->join('product_attributes as pa','pa.id','=','pai.product_attribute_id')

						->leftJoin(DB::raw('product_attributes_text as pat3'),'pat3.product_id','=','products.id')
						->leftJoin(DB::raw('product_attributes as pa3'), 'pa3.id','=','pat3.product_attribute_id')

						->leftJoin('product_attributes_text as pat', 'pat.product_id', '=', 'products.id')
						->join('product_attributes as pa2','pa2.id','=','pat.product_attribute_id')

						->join('vendor_locations as vl', 'vl.id', '=', 'pvl.vendor_location_id')

						->join('vendors as v', 'v.id', '=', 'vl.vendor_id')						
						->join('locations as l', 'l.id', '=', 'vl.location_id')
						->where('pvl.id',$productVendorLocationID)
                        ->where('pa.alias','reward_points_per_reservation')
                        ->where('pa2.alias', 'short_description')
						->select('products.id','products.name','pai.attribute_value as reward_point', 
								'l.name as location', 'pat.attribute_value as short_description',
								'v.name as vendor_name','pvl.descriptive_title',
								 //DB::raw('MAX(IF(pa.alias = "short_description", pat.attribute_value, "")) AS short_description'),
 								 DB::raw('MAX(IF(pa3.alias = "terms_and_conditions", pat.attribute_value, "")) AS terms_and_conditions'),
 								 DB::raw('MAX(IF(pa3.alias = "experience_includes", pat.attribute_value, "")) AS experience_includes')
								)
                        ->first(); 
		
		if($queryResult) {
			$arrData['id'] = $queryResult->id;
			$arrData['name'] = $queryResult->name;
			$arrData['reward_point'] = (empty($queryResult->reward_point))? 0.00 : $queryResult->reward_point;
			$arrData['location'] = $queryResult->location;
			$arrData['short_description'] = $queryResult->short_description;
			$arrData['terms_and_conditions'] = $queryResult->terms_and_conditions;
			$arrData['experience_includes'] = $queryResult->experience_includes;
			$arrData['vendor_name'] = $queryResult->vendor_name;
			$arrData['descriptive_title'] = $queryResult->descriptive_title;			
		}
		return $arrData;
	} 
	
	//-----------------------------------------------------------------
	
		
	/**
	 * Writes the addons details associated with a reservation into
	 * the DB.
	 * 
	 * @access	public
	 * @param	integer	$arrReservationID
	 * @param	array 	$arrAddon
	 * @since	1.0.0
	 */
	public static function addReservationAddonDetails($reservationID, $arrAddon) {
		//array to hold the data to be written into the DB
		$arrInsertData = array();
		
		foreach($arrAddon as $key => $detail) {
			$arrInsertData[] = array(
									'reservation_id' => $reservationID,
									'no_of_persons' => $detail['qty'],
									'options_id' => $detail['prod_id'],
									'option_type' => 'addon',
									'reservation_type' => 'experience',
									'created_at' => date('Y-m-d H:i:m'),
									'updated_at' => date('Y-m-d H:i:m'),
								);
		}
		
		//writing data to reservation_addons_variants_details table
		ReservationAddonsVariantsDetails::insert($arrInsertData);
	}
	
	//-----------------------------------------------------------------
	
	/**
	 * Updates the reservation Addon details in the database.
	 * 
	 * @access	public
	 * @static
	 * @param	integer
	 * @param	$arrAddon
	 * @since	1.0.0
	 */
	public static function updateReservationAddonDetails($reservationID, $arrAddon) {
			
		//array to hold the data to be written into the DB
		$arrInsertData = array();		
		
		//updating the addons values	
		foreach($arrAddon as $key => $value) {
			if(isset($value['id'])) {
				//getting an instance of the row to be updated
				$addOn = ReservationAddonsVariantsDetails::find($value['id']);
			
				//initializing the values to be updated
				$addOn->no_of_persons = $value['qty'];
				$addOn->options_id = $value['prod_id'];
			
				//writing the updated information
				$addOn->save();
			}
			else {
				$arrInsertData[] = array(
									'reservation_id' => $reservationID,
									'no_of_persons' => $value['qty'],
									'options_id' => $value['prod_id'],
									'option_type' => 'addon',
									'reservation_type' => 'experience',
									'created_at' => date('Y-m-d H:i:m'),
									'updated_at' => date('Y-m-d H:i:m'),
								);
			}
			
		}
		
		if(count($arrInsertData) > 0) {
			//writing data to reservation_addons_variants_details table
			ReservationAddonsVariantsDetails::insert($arrInsertData);
		}
	}
	//-----------------------------------------------------------------

	/**
	 * Sends api request to zoho
	 * 
	 * @access	public
	 * @static
	 * @param	array
	 * @param	$data
	 * @since	1.0.0
	 */
	public static function zohoAddBooking($data) {

		    $ch = curl_init();
		    $config = array(
		        //'authtoken' => 'e56a38dab1e09933f2a1183818310629',
		        //'authtoken' => 'f31eb33749ce0f39a7917dc5e1879a9c',
		        'authtoken' => '7e8e56113b2c2eb898bca9916c52154c',
		        'scope' => 'creatorapi',
		    );
		    $curlConfig = array(
		        CURLOPT_URL            => "https://creator.zoho.com/api/gourmetitup/xml/experience-bookings/form/bookings/record/add/",
		        CURLOPT_POST           => true,
		        CURLOPT_RETURNTRANSFER => true,
		        CURLOPT_POSTFIELDS     => $config + $data,
		    );		   

		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  ////------Added to ignore----
		    curl_setopt_array($ch, $curlConfig);
		    $result = curl_exec($ch);		   
		    curl_close($ch);    	    	
		    return simplexml_load_string($result);		    
		}
		//-----------------------------------------------------------------

	/**
	 * Sends mail based on response from the zoho
	 * 
	 * @access	public
	 * @static
	 * @param	array
	 * @param	$data
	 * @since	1.0.0
	 */  

	public static function zohoSendMail($zoho_res, $zoho_data, $reservation_id, $arrData) {	

		

        $zoho_success = $zoho_res->result->form->add->status;		
        if($zoho_success[0] != "Success") {

            $mailbody = 'E'.sprintf("%06d",$reservation_id).' reservation has not been posted to zoho. Please fix manually.<br><br>';
            $mailbody .= 'Reservation Details<br>';
            foreach($zoho_data as $key => $val){
                        $name = str_replace('_',' ',$key);
                        $mailbody .= $name.' '.$val.'<br>';
                    }

            Mail::raw($mailbody, function($message) use ($zoho_data)
                    {
                        $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                        $message->to('concierge@wowtables.com')->subject('Urgent: Zoho reservation posting error');
                        $message->cc('kunal@wowtables.com', 'deepa@wowtables.com');
                    });
        }

        
        if($arrData['reservationType'] == 'alacarte') {

        	//====================================
         	$outlet = self::getAlacarteOutlet($arrData['vendorLocationID']); 
         	
            $locationDetails = self::getAlacarteLocationDetails($arrData['vendorLocationID']);
              		
    		$vendorDetailsTemp =  self::readVendorDetailByLocationID($arrData['vendorLocationID']);     		
    		

    		$vendorDetails['attributes']= array('short_description' => $vendorDetailsTemp['short_description'], 
    							  				'terms_and_conditions' => $vendorDetailsTemp['terms_conditions']
    							  				);	
    		
    		$reservationResponse['status'] = 'success';
    		$reservationResponse['data']= array('reservation_type' => 'A la carte', 
    											'reservationID' => $reservation_id
    										   );    		
   		
    		//======================================  
    		$formattedRsvID = '#A-'. sprintf("%06d",$reservation_id);  	//Formatted Order ID	
        	$mergeReservationsArray = array('order_id'=> $formattedRsvID,
                    'reservation_date'=> date('d-F-Y',strtotime($arrData['reservationDate'])),
                    'reservation_time'=> date('g:i a',strtotime($arrData['reservationTime'])),
                    'venue' => $outlet->vendor_name,
                    'username' => $zoho_data['Name']
                );

                //echo "<pre>"; print_r($mergeReservationsArray); die;        	
        				$post_data =	$arrData;  
        		
                Mail::send('site.pages.restaurant_reservation',[
                    'location_details'=> $locationDetails,
                    'outlet'=> $outlet,
                    'post_data'=> $post_data,
                    'productDetails'=>$vendorDetails,
                    'reservationResponse'=>$reservationResponse,
                ], function($message) use ($mergeReservationsArray, $arrData){                
                    $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                    $message->to($arrData['guestEmail'])->subject('Your WowTables Reservation');
                });
                
                $emails = ['kunal@wowtables.com', 'deepa@wowtables.com'];	
                Mail::send('site.pages.restaurant_reservation',[
                    'location_details'=> $locationDetails,
                    'outlet'=> $outlet,
                    'post_data'=> $post_data,
                    'productDetails'=> $vendorDetails,
                    'reservationResponse'=>$reservationResponse,
                ], function($message) use ($mergeReservationsArray, $arrData, $emails){
                    $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');

                    $message->to('concierge@wowtables.com')->subject('NR - '.$mergeReservationsArray['order_id'].' | '.$mergeReservationsArray['reservation_date'].' , '.$mergeReservationsArray['reservation_time'].' | '.$mergeReservationsArray['venue'].' | '.$mergeReservationsArray['username'].' | App');
                    $message->cc($emails);                     
                });
        }
        else if($arrData['reservationType'] == 'experience') {


        	//====================================
             $locationDetails = self::getExperienceLocationDetails($arrData['vendorLocationID']);    	 
    		 $outlet = self::getExperienceOutlet($arrData['vendorLocationID']); 

    		 $productDetailsTemp =  self::readProductDetailByProductVendorLocationID($arrData['vendorLocationID']);     		
    		
    		 	
    		 $productDetails['attributes']= array(
    		 									'experience_includes' => $productDetailsTemp['experience_includes'],
    		 									'short_description' => $productDetailsTemp['short_description'], 
    							  				'terms_and_conditions' => $productDetailsTemp['terms_and_conditions']
    							  				);    
    						 

    		$reservationResponse['status'] = 'success';
    		$reservationResponse['data']= array('reservation_type' => 'Experience', 
    											'reservationID' => $reservation_id
    										   );     	    	 
    		$formattedRsvID = '#E-'. sprintf("%06d",$reservation_id);	//Formatted Order ID
        	$mergeReservationsArray = array('order_id'=> $formattedRsvID,
                    'reservation_date'=> date('d-F-Y',strtotime($arrData['reservationDate'])),
                    'reservation_time'=> date('g:i a',strtotime($arrData['reservationTime'])),
                    'venue' => $outlet->vendor_name,
                    'username' => $zoho_data['Name']
               );

                Mail::send('site.pages.experience_reservation',[
                    'location_details'=> $locationDetails,
                    'outlet'=> $outlet,
                    'post_data'=> $arrData,
                    'productDetails'=>$productDetails,
                    'reservationResponse'=>$reservationResponse,
                ], function($message) use ($mergeReservationsArray, $arrData){
                    $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');
                    $message->to($arrData['guestEmail'])->subject('Your WowTables Reservation');                    
                });

                $emails = ['kunal@wowtables.com', 'deepa@wowtables.com'];

                Mail::send('site.pages.experience_reservation',[
                    'location_details'=> $locationDetails,
                    'outlet'=> $outlet,
                    'post_data'=> $arrData,
                    'productDetails'=>$productDetails,
                    'reservationResponse'=>$reservationResponse,
                ], function($message) use ($mergeReservationsArray, $arrData, $emails){
                    $message->from('concierge@wowtables.com', 'WowTables by GourmetItUp');
                    $message->to('concierge@wowtables.com')->subject('NR - '.$mergeReservationsArray['order_id'].' | '.$mergeReservationsArray['reservation_date'].' , '.$mergeReservationsArray['reservation_time'].' | '.$mergeReservationsArray['venue'].' | '.$mergeReservationsArray['username'].' | App');
                    $message->cc($emails);                    
                });               

        }
     }

    public static function getAlacarteOutlet($vendorLocationID){
        $queryResult = DB::table('vendor_locations as vl')
            ->leftJoin('locations as l','vl.location_id','=','l.id')
            ->leftJoin('vendors as v','vl.vendor_id','=','v.id')
            ->where('vl.id',$vendorLocationID)
            ->select('l.name', 'v.name as vendor_name')
            ->first();

        return $queryResult;
    }

    public static function getAlacarteLocationDetails($vendorLocationID){
        $queryResult = DB::table('vendor_locations as vl')
            ->leftJoin('vendor_location_address as vla','vl.id','=','vla.vendor_location_id')
            ->where('vl.id',$vendorLocationID)
            ->select('vla.address','vla.latitude','vla.longitude')
            ->first();

        return $queryResult;
    }

      public static function getExperienceOutlet($vendorLocationID){
      $queryResult = DB::table('product_vendor_locations as pvl')
          ->join('vendor_locations as vl','pvl.vendor_location_id','=','vl.id')
          ->leftJoin('locations as l','vl.location_id','=','l.id')
          ->leftJoin('vendors as v','vl.vendor_id','=','v.id')
          ->leftJoin('products as p','pvl.product_id','=','p.id')
          ->where('pvl.id',$vendorLocationID)
          ->select('l.name', 'pvl.descriptive_title' ,'p.slug', 'p.name as product_name', 'v.name as vendor_name','p.id as product_id')
          ->first();

      return $queryResult;
  	}

  	public static function getExperienceLocationDetails($vendorLocationID){
      $queryResult = DB::table('product_vendor_locations as pvl')
          ->join('vendor_locations as vl','pvl.vendor_location_id','=','vl.id')
          ->leftJoin('vendor_location_address as vla','vl.id','=','vla.vendor_location_id')
          ->where('pvl.id',$vendorLocationID)
          ->select('vla.address','vla.latitude','vla.longitude')
          ->first();

      return $queryResult;
  	} 

  	public static function getUserLastReservation($user_id) {

  		$queryResult=DB::table('reservation_details as rd')
  							->where('user_id',$user_id)
  							->select('id', 'reservation_date', 'reservation_time')
  							->groupBy('reservation_date')
  							->groupBy('reservation_time')
  							->orderBy('reservation_date')
  							->orderBy('reservation_time')
  							->first();
  		return $queryResult;  							
  	}

  	/**
	 * Increment the reservation count at every new reservation.
	 * 
	 * @access	public
	 * @return	integer
	 * @since	1.0.0
	 */
  	public static function incrementReservationCount($userID, $reservationType ) {  		

  		if ( $reservationType == 'alacarte') {
  				$attrID = DB::table('user_attributes as ua')
										->where('ua.alias', '=', 'a_la_carte_reservation') 
										->select('id')
										->first();										

				$reservationId = DB::table('user_attributes_integer as uai')
												->join('user_attributes as ua', 'uai.user_attribute_id', '=', 'ua.id')
												->where('uai.user_id', $userID)												
												->where('ua.alias', '=', 'a_la_carte_reservation')
												->select('uai.user_id')
												->first();				 

				if($reservationId) {
	  						$reservationCountStatus = DB::table('user_attributes_integer as uai')
													->join('user_attributes as ua', 'uai.user_attribute_id', '=', 'ua.id')
													->where('uai.user_id', $userID)												
													->where('ua.alias', '=', 'a_la_carte_reservation')
													->increment('attribute_value', 1);
				} 
				else {			
							$reservationCountStatus = DB::table('user_attributes_integer')												
													->insert([															
																'user_id' => $userID, 
																'user_attribute_id' => $attrID->id,
																'attribute_value' => 1,
																'created_at' => date('Y-m-d H:i:s'),
																'updated_at' => date('Y-m-d H:i:s')															
															]);
				}
		} 
		else if ( $reservationType == 'experience') {

			$attrID = DB::table('user_attributes as ua')
										->where('ua.alias', '=', 'bookings_made') 
										->select('id')
										->first();
			$reservationId = DB::table('user_attributes_integer as uai')
												->join('user_attributes as ua', 'uai.user_attribute_id', '=', 'ua.id')
												->where('uai.user_id', $userID)												
												->where('ua.alias', '=', 'bookings_made')
												->select('uai.user_id')
												->first();				

			if($reservationId) {
	  						$reservationCountStatus = DB::table('user_attributes_integer as uai')
													->join('user_attributes as ua', 'uai.user_attribute_id', '=', 'ua.id')
													->where('uai.user_id', $userID)												
													->where('ua.alias', '=', 'bookings_made')
													->increment('attribute_value', 1);
			} 
			else {			

							$reservationCountStatus = DB::table('user_attributes_integer')												
													->insert([															
																'user_id' => $userID, 
																'user_attribute_id' => $attrID->id,
																'attribute_value' => 1,
																'created_at' => date('Y-m-d H:i:s'),
																'updated_at' => date('Y-m-d H:i:s')															
															]);
			}
		}
  			
  		
		return $reservationCountStatus;
  	} 
  	//-----------------------------------------------------------------

  	/**
	 * Store reward point for every new reservation.
	 * 
	 * @access	public
	 * @return	integer
	 * @since	1.0.0
	 */
  	public static function storeRewardPoint($userID, $rewardPoints, $reservationID ) {
  								
		$storeRewardPointStatus = DB::table('reward_points_earned')												
												->insert([															
															'user_id' 			=> $userID,															
															'reservation_id' 	=> $reservationID,
															'points_earned' 	=> $rewardPoints, 
															'status' 			=> 'approved', 
															'description' 		=> 'Reservation made',															
															'created_at' 		=> date('Y-m-d H:i:s'),
															'updated_at' 		=> date('Y-m-d H:i:s'),
															'deleted_at' 		=> date('Y-m-d H:i:s')																
														 ]);								
		return $storeRewardPointStatus;
  	}
  	//-----------------------------------------------------------------

  	/**
	 * Decrement the reservation count at every cancellation.
	 * 
	 * @access	public
	 * @return	integer
	 * @since	1.0.0
	 */
  	public static function decrementReservationCount( $reservationID ) { 

  		$queryResult = DB::table('reservation_details')
  								->where('id', $reservationID )
  								->select('reservation_type', 'user_id')
  								->first();  

  		if( $queryResult->reservation_type == "alacarte" ) { 
  					$decrementReservationCountStatus = DB::table('user_attributes_integer as uai')
															->join('user_attributes as ua', 'uai.user_attribute_id', '=', 'ua.id')
															->where('uai.user_id', $queryResult->user_id)												
															->where('ua.alias', '=', 'a_la_carte_reservation')
															->decrement('attribute_value', 1);
  		}
  		else if ( $queryResult->reservation_type == "experience" ) {
  					$decrementReservationCountStatus = DB::table('user_attributes_integer as uai')
															->join('user_attributes as ua', 'uai.user_attribute_id', '=', 'ua.id')
															->where('uai.user_id', $queryResult->user_id)												
															->where('ua.alias', '=', 'bookings_made')
															->decrement('attribute_value', 1);
  		}

  		return $decrementReservationCountStatus; 
  	}
  	//-----------------------------------------------------------------

  	/**
	 * Remove reward point for every reservation cancellation.
	 * 
	 * @access	public
	 * @return	integer
	 * @since	1.0.0
	 */
  	public static function cancelRewardPoint( $reservationID ) {

  		$rewardID = DB::table('reward_points_earned')
  								->where('reservation_id', $reservationID)
  								->select('id')
  								->first();

  		if($rewardID) {
  			$rewardCancelStatus = DB::table('reward_points_earned')
  									->where('id', $rewardID->id)
            						->update(['status' => 'cancelled']);
  		}
  		else {
  				$rewardCancelStatus = 0;
  		}

        return $rewardCancelStatus;
  	}
  	//-----------------------------------------------------------------

  	/**
	 * Send mail by mailchimp for every reservation .
	 * 
	 * @access	public
	 * @return	 
	 * @since	1.0.0
	 */
  	public static function mailByMailChimp( $arrData, $userID ,$objMailChimp) {

  		$listId = '986c01a26a';

  		if($arrData['reservationType'] == "alacarte") {

  				$reservation = DB::table('user_attributes_integer as uai')
															->join('user_attributes as ua', 'uai.user_attribute_id', '=', 'ua.id')
															->where('uai.user_id', $userID)												
															->where('ua.alias', '=', 'a_la_carte_reservation')
															->select('attribute_value as count')
															->first();

  				$merge_vars = array(
                    'MERGE1'=>$arrData['guestName'],
                    'MERGE10'=>date('m/d/Y'),
                    'MERGE11'=>$reservation->count,
                    'MERGE13'=>$arrData['phone'],
                    'MERGE27'=>date("m/d/Y",strtotime($arrData['reservationDate']))
                );
                //$this->mailchimp->lists->subscribe($this->listId, ['email' => $arrData['guestEmail']],$merge_vars,"html",false,true );
                $objMailChimp->lists->subscribe($listId, ['email' => $arrData['guestEmail']],$merge_vars,"html",false,true );
                //$this->mc_api->listSubscribe($list_id, $_POST['email'], $merge_vars,"html",true,true );
  		}
  		else if ($arrData['reservationType'] == "experience") {

  				$reservation = DB::table('user_attributes_integer as uai')
															->join('user_attributes as ua', 'uai.user_attribute_id', '=', 'ua.id')
															->where('uai.user_id', $userID)												
															->where('ua.alias', '=', 'bookings_made')
															->select('attribute_value as count')
															->first();

  				$merge_vars = array(
                    'MERGE1'=>$arrData['guestName'],
                    'MERGE10'=>date('m/d/Y'),
                    'MERGE11'=>$reservation->count,
                    'MERGE13'=>$arrData['phone'],
                    'MERGE27'=>date("m/d/Y",strtotime($arrData['reservationDate']))
                );
                //$this->mailchimp->lists->subscribe($this->listId, ['email' => $arrData['guestEmail']],$merge_vars,"html",false,true );
                $objMailChimp->lists->subscribe($listId, ['email' => $arrData['guestEmail']],$merge_vars,"html",false,true );
                //$this->mc_api->listSubscribe($list_id, $_POST['email'], $merge_vars,"html",true,true );
  		}

  	}
  	//------------------------------------------------------------------------

  	/**
	 * Send mail by mailchimp for every cancelled reservation .
	 * 
	 * @access	public
	 * @return	 
	 * @since	1.0.0
	 */
  		public static function sendMailchimp( $reservationID ) { 

  		$listId = '986c01a26a';
  		$queryResult = DB::table('reservation_details')
  								->where('id', $reservationID)
  								->select('reservation_type', 'user_id')
  								->first();  

  		if( $queryResult->reservation_type == "alacarte" ) { 
  					$arrResult = DB::table('user_attributes_integer as uai')
															->join('user_attributes as ua', 'uai.user_attribute_id', '=', 'ua.id')
															->where('uai.user_id', $queryResult->user_id)												
															->where('ua.alias', '=', 'a_la_carte_reservation')
															->select('uai.attribute_value')
															->first();
					$setBookingKey = 'MERGE26';					
					
  		}
  		else if ( $queryResult->reservation_type == "experience" ) {
  					$arrResult = DB::table('user_attributes_integer as uai')
															->join('user_attributes as ua', 'uai.user_attribute_id', '=', 'ua.id')
															->where('uai.user_id', $queryResult->user_id)												
															->where('ua.alias', '=', 'bookings_made')
															->select('uai.attribute_value')
															->first();;
					$setBookingKey = 'MERGE11';
															 
  		}
		
		$userResult = DB::table('reservation_details')
										->join('users', 'users.id', '=', 'reservation_details.user_id' )
										->where('reservation_details.id', $reservationID)
										->select('users.email')
										->first();
		
		if($queryResult) {
				$merge_vars = array(
						$setBookingKey => $arrResult->attribute_value,
						//$setBookingKey => $setBookingsValue - 1,
					);

					//$email = ["email"["email":]];
					///$this->mailchimp->lists->subscribe($this->listId, ["email"=>$userData['data']['email']],$merge_vars,"html",true,true );
					$this->mailchimp->lists->subscribe($listId, ["email"=>$userResult->email],$merge_vars,"html",true,true );
					//$this->mc_api->listSubscribe($list_id, $_POST['email'], $merge_vars,"html",true,true );
		}
  		return $arrResult; 
  	}
  	//-----------------------------------------------------------------

}
//end of class Reservation
//end of file app/Http/Models/Eloquent/Reservation.php