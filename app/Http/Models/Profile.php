<?php  namespace WowTables\Http\Models;

use DB;
use Config;
use WowTables\Http\Models\Eloquent\ReservationDetails;
use Hash;

/**
 * Model class Profile
 *
 * @package WowTables
 */
class Profile {

    static $arrRules = array(
                            //'access_token' => 'required|exists:user_devices,access_token',
                            'full_name'     => 'max:64',
                            'phone_number'  => 'max:10',
                            'zip_code'      => 'max:45',
                            'location_id'   => 'integer',
                            'dob'           => 'date',
                            'gender'        => 'in:Male,Female'
                           );
    //-------------------------------------------------------------

    static $arrMyProfileRule=array( 'access_token' => 'required|exists:user_devices,access_token');

    /**
     * Get the Profile details of the user
     *
     * @static	true
     * @access	public
     * @param	array $token
     * @since	1.0.0
     */
            public static function getUserProfile($token) {

            $queryProfileResult = DB::table('users as u')
                                        ->leftjoin('user_attributes_date as uad','u.id','=','uad.user_id')
                                        ->leftjoin('user_attributes as ua', function($join) {
                                                                                            $join->on('uad.user_attribute_id','=','ua.id')
                                                                                                 ->where('ua.alias','=','date_of_birth'); 
                                                                                        })
                                        ->leftjoin('locations as l','l.id','=','u.location_id')
                                        ->leftjoin('user_attributes_singleselect as uas','u.id','=','uas.user_id')
                                        ->leftjoin('user_attributes_select_options as uaso','uas.user_attributes_select_option_id','=','uaso.id')
                                        ->leftjoin('user_attributes as ua2', function($join) {
                                                                                                $join->on('uaso.user_attribute_id','=','ua2.id')
                                                                                                     ->where('ua2.alias','=','gender');
                                                                                         })
                                        ->leftjoin('user_attributes_integer as uai','uai.user_id','=','u.id')
                                        ->leftjoin('user_attributes as ua3', 'ua3.id','=','uai.user_attribute_id')
                                        ->leftjoin('user_attributes as ua4', 'ua4.id','=','uad.user_attribute_id')
                                        ->leftjoin('user_devices as ud','u.id','=','ud.user_id')
                                        //->where('u.id',$userID)
                                        ->where('ud.access_token',$token)                                        
                                        ->select('u.id as user_id','u.full_name','u.email','phone_number','u.zip_code',
                                                'uaso.option as gender','l.id as location_id','l.name as location','ud.access_token',
                                                DB::raw('MAX(IF(ua3.alias = "points_earned", uai.attribute_value, 0)) AS points_earned'),
                                                DB::raw('MAX(IF(ua3.alias = "points_spent", uai.attribute_value, 0)) AS points_spent'),
                                                DB::raw('MAX(IF(ua3.alias = "bookings_made", uai.attribute_value, 0)) AS bookings_made'),
                                                DB::raw('MAX(IF(ua4.alias = "date_of_birth", date(uad.attribute_value), 0)) AS dob'),
                                                DB::raw('MAX(IF(ua4.alias = "anniversary_date", date(uad.attribute_value), 0)) AS anniversary_date'))
                                        ->groupby('u.id')
                                        ->first();
                                       
                                     

            //Read all the preferred locations
            $preferredLocations=Profile::getUserPreferences($token);

            //Read all the AREAS related to user's location            
            $cityAreas=Locations::readCityArea($queryProfileResult->location_id);            

            //array to contain the response to be sent back to client
            $arrResponse = array();

            $lastReservationDetail=ReservationDetails::getUserLastReservation($queryProfileResult->user_id);
            //print_r($lastReservationDetail);  

            if($queryProfileResult) {
                $arrResponse['status'] = Config::get('constants.API_SUCCESS');
                $arrResponse['data']=array(
                                            'user_id' => $queryProfileResult->user_id,
                                            //'access_token' => $queryProfileResult->access_token,
                                            'full_name' => $queryProfileResult->full_name,
                                            'email' => $queryProfileResult->email,
                                            'phone_number' => ($queryProfileResult->phone_number == 0) ? "" : (string) $queryProfileResult->phone_number,
                                            'zip_code' => $queryProfileResult->zip_code,
                                            'gender' => $queryProfileResult->gender,
                                            'location_id' => $queryProfileResult->location_id,
                                            'location' => $queryProfileResult->location,
                                            'points_earned' => $queryProfileResult->points_earned,
                                            'points_spent' => $queryProfileResult->points_spent,
                                            'points_remaining' => $queryProfileResult->points_earned - $queryProfileResult->points_spent,
                                            'bookings_made' => $queryProfileResult->bookings_made,
                                            'dob' => $queryProfileResult->dob,
                                            'anniversary_date' => $queryProfileResult->anniversary_date,
                                            'selectedPreferences' => $preferredLocations['data'],
                                            'areas' => $cityAreas['data'],
                                            'last_reservation_date' => (empty($lastReservationDetail->reservation_date)) ? "" : $lastReservationDetail->reservation_date,
                                            'last_reservation_time' => (empty($lastReservationDetail->reservation_time)) ? "" : $lastReservationDetail->reservation_time
                                          );
            }
            else {
                $arrResponse['status'] = Config::get('constants.API_SUCCESS');
                $arrResponse['data'] = array();
            }


            return $arrResponse;
        }

    //-----------------------------------------------------------------

/**
* Get the Profile details of the user in web requrest
*
* @static  true
* @access  public
* @param   array $id
* @since   1.0.0
*/
public static function getUserProfileWeb($id) {  

$queryProfileResult = DB::table('users as u')
                    ->leftjoin('user_attributes_date as uad','u.id','=','uad.user_id')
                    ->leftjoin('user_attributes as ua', function($join) {
                                                                        $join->on('uad.user_attribute_id','=','ua.id')
                                                                             ->where('ua.alias','=','date_of_birth'); 
                                                                    })
                    ->leftjoin('locations as l','l.id','=','u.location_id')
                    ->leftjoin('user_attributes_singleselect as uas','u.id','=','uas.user_id')
                    ->leftjoin('user_attributes_select_options as uaso','uas.user_attributes_select_option_id','=','uaso.id')
                    ->leftjoin('user_attributes as ua2', function($join) {
                                                                            $join->on('uaso.user_attribute_id','=','ua2.id')
                                                                                 ->where('ua2.alias','=','gender');
                                                                     })
                    ->leftjoin('user_attributes_integer as uai','uai.user_id','=','u.id')
                    ->leftjoin('user_attributes as ua3', 'ua3.id','=','uai.user_attribute_id')
                    ->leftjoin('user_attributes as ua4', 'ua4.id','=','uad.user_attribute_id')
                    ->leftjoin('user_devices as ud','u.id','=','ud.user_id')
                    //->where('u.id',$userID)
                    ->where('u.id',$id)                                        
                    ->select('u.id as user_id','u.full_name','u.email','phone_number','u.zip_code','u.newsletter_frequency',
                            'uaso.option as gender','l.id as location_id','l.name as location','ud.access_token','u.points_earned as points_earned','u.points_spent as points_spent',
                            /*DB::raw('MAX(IF(ua3.alias = "points_earned", uai.attribute_value, 0)) AS points_earned'),
                            DB::raw('MAX(IF(ua3.alias = "points_spent", uai.attribute_value, 0)) AS points_spent'),*/
                            DB::raw('MAX(IF(ua3.alias = "bookings_made", uai.attribute_value, 0)) AS bookings_made'),
                            DB::raw('MAX(IF(ua3.alias = "membership_number", uai.attribute_value, 0)) AS membership_number'),
                            DB::raw('MAX(IF(ua3.alias = "a_la_carte_reservation", uai.attribute_value, 0)) AS a_la_carte_reservation'),
                            DB::raw('MAX(IF(ua4.alias = "date_of_birth", date(uad.attribute_value), 0)) AS dob'),
                            DB::raw('MAX(IF(ua4.alias = "anniversary_date", date(uad.attribute_value), 0)) AS anniversary_date'))
                    ->groupby('u.id')
                    ->first();
                               
                             

    //Read all the preferred locations
    //$preferredLocations=Profile::getUserPreferences($id);

    //Read all the AREAS related to user's location            
    $cityAreas=Locations::readCityArea($queryProfileResult->location_id);            

    //array to contain the response to be sent back to client
    $arrResponse = array();

    $lastReservationDetail=ReservationDetails::getUserLastReservation($queryProfileResult->user_id);
    //print_r($lastReservationDetail);  

    if($queryProfileResult) {
        $arrResponse['status'] = Config::get('constants.API_SUCCESS');
        $arrResponse['data']=array(
                                    'user_id' => $queryProfileResult->user_id,
                                    //'access_token' => $queryProfileResult->access_token,
                                    'full_name' => $queryProfileResult->full_name,
                                    'email' => $queryProfileResult->email,
                                    'phone_number' => ($queryProfileResult->phone_number == 0) ? "" : $queryProfileResult->phone_number,
                                    'zip_code' => $queryProfileResult->zip_code,
                                    'gender' => $queryProfileResult->gender,
                                    'location_id' => $queryProfileResult->location_id,
                                    'location' => $queryProfileResult->location,
                                    'points_earned' => $queryProfileResult->points_earned,
                                    'points_spent' => $queryProfileResult->points_spent,
                                    'points_remaining' => $queryProfileResult->points_earned - $queryProfileResult->points_spent,
                                    'bookings_made' => $queryProfileResult->bookings_made,
                                    'membership_number' => $queryProfileResult->membership_number,
                                    'a_la_carte_reservation' => $queryProfileResult->a_la_carte_reservation,
                                    'dob' => $queryProfileResult->dob,
                                    'anniversary_date' => $queryProfileResult->anniversary_date,
                                    'newsletter_frequency' => $queryProfileResult->newsletter_frequency,
                                   // 'selectedPreferences' => $preferredLocations['data'],
                                    'areas' => $cityAreas['data'],
                                    'last_reservation_date' => (empty($lastReservationDetail->reservation_date)) ? "" : $lastReservationDetail->reservation_date,
                                    'last_reservation_time' => (empty($lastReservationDetail->reservation_time)) ? "" : $lastReservationDetail->reservation_time
                                  );
    }
    else {
        $arrResponse['status'] = Config::get('constants.API_SUCCESS');
        $arrResponse['data'] = array();
    }


    return $arrResponse;
}

//-----------------------------------------------------------------

    /**
     * Get the Preferred Locations of the user
     *
     * @static	true
     * @access	public
     * @param	array $token
     * @since	1.0.0
     */
        public static function getUserPreferences($token) {
            $locations=DB::table('user_preferred_areas as upa')
                                            ->join('locations as l','l.id','=','upa.location_id')
                                            ->join('user_devices as ud','ud.user_id','=','upa.user_id')
                                            ->where('ud.access_token',$token)
                                            ->select('l.id as location_id', 'l.name as location_name')
                                            ->get();
            //array to contain the response to be sent back to client
            $arrResponse = array();

            if($locations) {
                $arrResponse['status'] = Config::get('constants.API_SUCCESS');
                $arrResponse['data']= $locations;

            }
            else {
                $arrResponse['status'] = Config::get('constants.API_SUCCESS');
                $arrResponse['data'] = array();
            }


            return $arrResponse;

        }

    //-----------------------------------------------------------------

    /**
     * Update the Profile details of the user
     *
     * @static	true
     * @access	public
     * @param	array $data
     * @since	1.0.0
     */
        public static function updateProfile($data){

            $userID = DB::table('user_devices as ud')
                        ->join('users as u','u.id','=','ud.user_id')
                        ->where('ud.access_token',$data['access_token'])
                        ->select('ud.user_id')
                        ->first();
                            
            
            //updating data in users table
            $userTableData = array(
                                    //'full_name' => $data['full_name'],
                                    //'phone_number' => $data['phone_number'],
                                    //'zip_code' => $data['zip_code'],
                                    //'location_id' => $data['location_id'],
                                    'updated_at' => date('Y-m-d H:i:s'),
                                  );
            //Checking for empty values
            if(array_key_exists('full_name', $data) && !empty(trim($data['full_name'])) ) {
                $userTableData['full_name'] = $data['full_name'];
            }

            if(array_key_exists('phone_number', $data) && !empty(trim($data['phone_number'])) ) {
                $userTableData['phone_number'] = $data['phone_number'];
            }

            if(array_key_exists('zip_code', $data) && !empty(trim($data['zip_code'])) ) {
                $userTableData['zip_code'] = $data['zip_code'];
            }

            if(array_key_exists('location_id', $data) && !empty(trim($data['location_id'])) ) {
                $userTableData['location_id'] = $data['location_id'];
            }
            
            DB::table('users')
                ->where('id', $userID->user_id)
                ->update($userTableData);


            $queryAttribute = DB::table('user_attributes') -> select('id','alias') -> get();

            //array having attribute alias and id as key value pair
            $arrAttribute = array();
            foreach($queryAttribute as $row) {
                $arrAttribute[$row->alias] = $row->id;
            }

            $dobID = DB::table('user_attributes_date')
                                ->where('user_id', 53)
                                ->whereIn('user_attribute_id', array($arrAttribute['date_of_birth'], $arrAttribute['anniversary_date']))
                                ->select('id', 'user_attribute_id')
                                ->get();

            $updateDOBFlag = FALSE;
            $updateAnniversary = FALSE;


            if(array_key_exists('dob', $data) && !empty($data['dob'])) {
                    if(!empty($arrAttribute['date_of_birth'])) {
                            foreach($dobID as $row ) {              
                                if ( $row->user_attribute_id == $arrAttribute['date_of_birth'] ) {

                                    $dobUpdate = DB::table('user_attributes_date')
                                                    ->where('id', $row->id)                               
                                                    ->update(['attribute_value' => $data['dob']]); 
                                    $updateDOBFlag = TRUE;             

                                }
                                else if ( $row->user_attribute_id == $arrAttribute['anniversary_date'] && array_key_exists('anniversary_date', $data)) {

                                    $anniversaryDate = DB::table('user_attributes_date')
                                                                ->where('id', $row->id)                                                
                                                                ->update(['attribute_value' => $data['anniversary_date']]);
                                    $updateAnniversary = TRUE;

                                }
                            }
           
                            if(!$updateDOBFlag) {
                                        //adding data to the table
                                        DB::table('user_attributes_date')
                                                ->insert(array(
                                                    'user_id'           => $userID->user_id,
                                                    'user_attribute_id' => $arrAttribute['date_of_birth'],
                                                    'attribute_value'   => $data['dob'],
                                                    'created_at'        => date('Y-m-d H:i:s'),
                                                    'updated_at'        => date('Y-m-d H:i:s')
                                                ));
                            }
                        }
            }

            if(!$updateAnniversary && array_key_exists('anniversary_date', $data)) {
                        //adding data to the table
                        DB::table('user_attributes_date')
                                ->insert(array(
                                    'user_id'           => $userID->user_id,
                                    'user_attribute_id' => $arrAttribute['anniversary_date'],
                                    'attribute_value'   => $data['anniversary_date'],
                                    'created_at'        => date('Y-m-d H:i:s'),
                                    'updated_at'        => date('Y-m-d H:i:s')
                                ));
                    }
                    

            if( array_key_exists('gender', $data) && !empty(trim($data['gender']))) {
                $queryGenderValue = DB::table('user_attributes_select_options')
                                            -> select('id','option')
                                            -> where('user_attribute_id', $arrAttribute['gender'])
                                            -> get();
            
                //array having options and id as key value pair
                $arrGender = array();
                $arrGenderId = array();
                foreach( $queryGenderValue as $row) {
                    $arrGender[$row->option] = $row->id;
                    $arrGenderId[] = $row->id;
                }
                 
                //updating user gender
                $genderUpdate = DB::table('user_attributes_singleselect')
                                    ->where('user_id',$userID->user_id)
                                    ->whereIn('user_attributes_select_option_id',$arrGenderId)
                                    ->update(array('user_attributes_select_option_id' => $arrGender[$data['gender']]));
                
                if( !$genderUpdate) {
                    //adding data to the table 
                    DB::table('user_attributes_singleselect')
                            ->insert(array(
                                'user_id'                           => $userID->user_id,
                                'user_attributes_select_option_id'  => $arrGender[$data['gender']],
                                'created_at'                        => date('Y-m-d H:i:s'),
                                'updated_at'                        => date('Y-m-d H:i:s')
                            ));
                }
            }

            //array to contain the response to be sent back to client
            $arrResponse = array();

                $arrResponse['status'] = Config::get('constants.API_SUCCESS');
                $arrResponse['data'] = array();

            return $arrResponse;
        }
    //-----------------------------------------------------------------

    //-----------------------------------------------------------------

    /**
     * Update the Profile details of the user using by website
     *
     * @static  true
     * @access  public
     * @param   array $data
     * @since   1.0.0
     */
        public static function updateProfileWeb($data, $userID){

            /*$userID = DB::table('user_devices as ud')
                        ->join('users as u','u.id','=','ud.user_id')
                        ->where('ud.access_token',$data['access_token'])
                        ->select('ud.user_id')
                        ->first();*/
                            
            //print_r($id); die();
            //updating data in users table
            $userTableData = array(
                                    'full_name' => $data['full_name'],
                                    'phone_number' => $data['phone_number'],
                                    'zip_code' => $data['zip_code'],
                                    'location_id' => $data['location_id'],
                                    'updated_at' => date('Y-m-d H:i:s'),
                                  ); 

            if($data['password']!="")
            {
                $passwordUpdate = DB::table('users')
                                ->where('id', $userID)
                                ->update(array('password' => Hash::make($data['password'])));

            }
            
            DB::table('users')
                ->where('id', $userID)
                ->update($userTableData);
            


            $queryAttribute = DB::table('user_attributes') -> select('id','alias') -> get();

            //array having attribute alias and id as key value pair
            $arrAttribute = array();
            foreach($queryAttribute as $row) {
                $arrAttribute[$row->alias] = $row->id;
            }


            $dobUpdate = DB::table('user_attributes_date')
                                ->where('user_id', $userID)
                                ->where('user_attribute_id',$arrAttribute['date_of_birth'])
                                ->update(array('attribute_value' => date('Y-m-d H:i:s',strtotime($data['dob']))));
            
            if($data['dob'] =='0') {
               
            }
            else
            {
                //adding data to the table
                DB::table('user_attributes_date')
                        ->insert(array(
                            'user_id'           => $userID,
                            'user_attribute_id' => $arrAttribute['date_of_birth'],
                            'attribute_value'   => date('Y-m-d H:i:s',strtotime($data['dob'])),
                            'created_at'        => date('Y-m-d H:i:s'),
                            'updated_at'        => date('Y-m-d H:i:s')
                        ));

            }
            /*print_r($arrAttribute);
            exit;*/
            
            if($data['aniversary_date']!='0000-00-00')
            {
                $anversiryUpdate = DB::table('user_attributes_date')
                                    ->where('user_id', $userID)
                                    ->where('user_attribute_id',$arrAttribute['anniversary_date'])
                                    ->update(array('attribute_value' => date('Y-m-d H:i:s',strtotime($data['aniversary_date']))));
                
                if(!$anversiryUpdate) {
                    //adding data to the table
                    DB::table('user_attributes_date')
                            ->insert(array(
                                'user_id'           => $userID,
                                'user_attribute_id' => $arrAttribute['anniversary_date'],
                                'attribute_value'   => date('Y-m-d H:i:s',strtotime($data['aniversary_date'])),
                                'created_at'        => date('Y-m-d H:i:s'),
                                'updated_at'        => date('Y-m-d H:i:s')
                            ));
                }
            }


            $queryGenderValue = DB::table('user_attributes_select_options')
                                            -> select('id','option')
                                            -> where('user_attribute_id', $arrAttribute['gender'])
                                            -> get();
            

            //array having options and id as key value pair
            $arrGender = array();
            $arrGenderId = array();
            foreach( $queryGenderValue as $row) {
                $arrGender[$row->option] = $row->id;
                $arrGenderId[] = $row->id;
            }

            //updating user gender
            $genderUpdate = DB::table('user_attributes_singleselect')
                                ->where('user_id',$userID)
                                ->whereIn('user_attributes_select_option_id',$arrGenderId)
                                ->update(array('user_attributes_select_option_id' => $arrGender[$data['gender']]));
            
            if( !$genderUpdate) {
                //adding data to the table
                DB::table('user_attributes_singleselect')
                        ->insert(array(
                            'user_id'                           => $userID,
                            'user_attributes_select_option_id'  => $arrGender[$data['gender']],
                            'created_at'                        => date('Y-m-d H:i:s'),
                            'updated_at'                        => date('Y-m-d H:i:s')
                        ));
            }

            //array to contain the response to be sent back to client
            $arrResponse = array();

                $arrResponse['status'] = Config::get('constants.API_SUCCESS');
                $arrResponse['data'] = array();

            return $arrResponse;
        }
    //-----------------------------------------------------------------



    //-----------------------------------------------------------------

    /**
     * Update the Profile details of the user using by website
     *
     * @static  true
     * @access  public
     * @param   array $data
     * @since   1.0.0
     */
        public static function updateProfileWebAdmin($data, $userID){

            /*$userID = DB::table('user_devices as ud')
                        ->join('users as u','u.id','=','ud.user_id')
                        ->where('ud.access_token',$data['access_token'])
                        ->select('ud.user_id')
                        ->first();*/
                            
            //print_r($id); die();
            //updating data in users table
            $userTableData = array(
                                    'full_name' => $data['full_name'],
                                    'phone_number' => $data['phone_number'],
                                    'location_id' => $data['location_id'],
                                    'updated_at' => date('Y-m-d H:i:s'),
                                  ); 
            
            DB::table('users')
                ->where('id', $userID)
                ->update($userTableData);
            


            $queryAttribute = DB::table('user_attributes') -> select('id','alias') -> get();

            //array having attribute alias and id as key value pair
            $arrAttribute = array();
            foreach($queryAttribute as $row) {
                $arrAttribute[$row->alias] = $row->id;
            }


            $dobUpdate = DB::table('user_attributes_date')
                                ->where('user_id', $userID)
                                ->where('user_attribute_id',$arrAttribute['date_of_birth'])
                                ->update(array('attribute_value' => $data['date_of_birth']));
            
            if(!$dobUpdate) {
                //adding data to the table
                DB::table('user_attributes_date')
                        ->insert(array(
                            'user_id'           => $userID,
                            'user_attribute_id' => $arrAttribute['date_of_birth'],
                            'attribute_value'   => date('Y-m-d H:i:s',strtotime($data['date_of_birth'])),
                            'created_at'        => date('Y-m-d H:i:s'),
                            'updated_at'        => date('Y-m-d H:i:s')
                        ));
            }

            $queryGenderValue = DB::table('user_attributes_select_options')
                                            -> select('id','option')
                                            -> where('user_attribute_id', $arrAttribute['gender'])
                                            -> get();
            

            //array having options and id as key value pair
            $arrGender = array();
            $arrGenderId = array();
            foreach( $queryGenderValue as $row) {
                $arrGender[$row->option] = $row->id;
                $arrGenderId[] = $row->id;
            }

            //updating user gender
            $genderUpdate = DB::table('user_attributes_singleselect')
                                ->where('user_id',$userID)
                                ->whereIn('user_attributes_select_option_id',$arrGenderId)
                                ->update(array('user_attributes_select_option_id' => $arrGender[$data['gender']]));
            
            if( !$genderUpdate) {
                //adding data to the table
                DB::table('user_attributes_singleselect')
                        ->insert(array(
                            'user_id'                           => $userID,
                            'user_attributes_select_option_id'  => $arrGender[$data['gender']],
                            'created_at'                        => date('Y-m-d H:i:s'),
                            'updated_at'                        => date('Y-m-d H:i:s')
                        ));
            }

            //array to contain the response to be sent back to client
            $arrResponse = array();

                $arrResponse['status'] = Config::get('constants.API_SUCCESS');
                $arrResponse['data'] = array();

            return $arrResponse;
        }
    //-----------------------------------------------------------------
        
    /**
     * Set the Preferred areas of the user
     *
     * @static	true
     * @access	public
     * @param	array $area
     * @since	1.0.0
     */
    public static function savePreferredAreas($area){

        $id=DB::table('user_devices as ud')
            ->where('ud.access_token',$area['access_token'])
            ->select('ud.user_id')
            ->first();
        DB::table('user_preferred_areas')
            ->where('user_id',$id->user_id)
            ->delete();
        foreach($area['areas'] as $key => $value){
            DB::table('user_preferred_areas')
                ->insert([
                            'id' => '',
                            'user_id' => $id->user_id,
                            'location_id' => $value['location_id'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                         ]);
        }

        //array to contain the response to be sent back to client
        $arrResponse = array();

        $arrResponse['status'] = Config::get('constants.API_SUCCESS');
        $arrResponse['data'] = array();

        return $arrResponse;
    }
    //-----------------------------------------------------------------

    public static function updateReservationInUsers($points,$type,$bookings,$reservationType,$userID,$lastOrderId){
        $queryAttribute = DB::table('user_attributes') -> select('id','alias') -> get();

        //array having attribute alias and id as key value pair
        $arrAttribute = array();
        foreach($queryAttribute as $row) {
            $arrAttribute[$row->alias] = $row->id;
        }



        if($type == "new"){

            DB::table('reward_points_earned')
                ->insert(array(
                    'user_id'         => $userID,
                    'reservation_id'  => $lastOrderId,
                    'points_earned'   => $points,
                    'status'          => 'approved',
                    'description'     => 'Reservation Made',
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s')
                ));



            $q = "UPDATE users SET points_earned = points_earned + ".$points." WHERE id = ?";

            DB::update($q,[$userID]);


            if($reservationType == "experience"){

                $bookingsMade = DB::table('user_attributes_integer')
                    ->where('user_id', $userID)
                    ->where('user_attribute_id',$arrAttribute['bookings_made'])
                    ->update(array('attribute_value' => $bookings));

                if(!$bookingsMade) {
                    //adding data to the table
                    DB::table('user_attributes_integer')
                        ->insert(array(
                            'user_id'           => $userID,
                            'user_attribute_id' => $arrAttribute['bookings_made'],
                            'attribute_value'   => $bookings,
                            'created_at'        => date('Y-m-d H:i:s'),
                            'updated_at'        => date('Y-m-d H:i:s')
                        ));
                }

            }

            if($reservationType == "alacarte"){

                $bookingsMade = DB::table('user_attributes_integer')
                    ->where('user_id', $userID)
                    ->where('user_attribute_id',$arrAttribute['a_la_carte_reservation'])
                    ->update(array('attribute_value' => $bookings));

                if(!$bookingsMade) {
                    //adding data to the table
                    DB::table('user_attributes_integer')
                        ->insert(array(
                            'user_id'           => $userID,
                            'user_attribute_id' => $arrAttribute['a_la_carte_reservation'],
                            'attribute_value'   => $bookings,
                            'created_at'        => date('Y-m-d H:i:s'),
                            'updated_at'        => date('Y-m-d H:i:s')
                        ));
                }

            }

        } else if($type == "cancel"){

            DB::update("update reward_points_earned set status='cancelled' where reservation_id = '$lastOrderId'");

            $q = "UPDATE users SET points_spent = points_spent + ".$points." WHERE id = ?";

            DB::update($q,[$userID]);


            if($reservationType == "experience"){

                $bookingsMade = DB::table('user_attributes_integer')
                    ->where('user_id', $userID)
                    ->where('user_attribute_id',$arrAttribute['bookings_made'])
                    ->update(array('attribute_value' => $bookings));

                if(!$bookingsMade) {
                    //adding data to the table
                    DB::table('user_attributes_integer')
                        ->insert(array(
                            'user_id'           => $userID,
                            'user_attribute_id' => $arrAttribute['bookings_made'],
                            'attribute_value'   => $bookings,
                            'created_at'        => date('Y-m-d H:i:s'),
                            'updated_at'        => date('Y-m-d H:i:s')
                        ));
                }

            }

            if($reservationType == "alacarte"){

                $bookingsMade = DB::table('user_attributes_integer')
                    ->where('user_id', $userID)
                    ->where('user_attribute_id',$arrAttribute['a_la_carte_reservation'])
                    ->update(array('attribute_value' => $bookings));

                if(!$bookingsMade) {
                    //adding data to the table
                    DB::table('user_attributes_integer')
                        ->insert(array(
                            'user_id'           => $userID,
                            'user_attribute_id' => $arrAttribute['a_la_carte_reservation'],
                            'attribute_value'   => $bookings,
                            'created_at'        => date('Y-m-d H:i:s'),
                            'updated_at'        => date('Y-m-d H:i:s')
                        ));
                }

            }

        }


    }

    public static function updatePointsManually($points,$description,$user_id,$status){

        if($status == "add_points"){
            DB::table('reward_points_earned')
                ->insert(array(
                    'user_id'         => $user_id,
                    'reservation_id'  => 0,
                    'points_earned'   => $points,
                    'status'          => 'approved',
                    'description'     => $description,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s')
                ));


            $q = "UPDATE users SET points_earned = points_earned + ".$points." WHERE id = ?";

            DB::update($q,[$user_id]);

        } else if($status == "redeem_points" || $status == "remove_points"){

            DB::table('reward_points_redeemed')
                ->insert(array(
                    'user_id'         => $user_id,
                    'points_redeemed'   => $points,
                    'description'     => $description,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s')
                ));

            $q = "UPDATE users SET points_spent = points_spent + ".$points." WHERE id = ?";

            DB::update($q,[$user_id]);
        }

    }

}
//end of class Profile.
//end of file Profile.php