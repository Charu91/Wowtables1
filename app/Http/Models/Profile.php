<?php  namespace WowTables\Http\Models;

use DB;
use Config;

/**
 * Model class Profile
 *
 * @package WowTables\Http\Models
 */
class Profile {

    static $arrRules = array(
                            'access_token' => 'required|exists:user_devices,access_token',
                            'full_name' => 'required||max:64'  ,
                            'phone_number' => 'required',
                            'zip_code'  => 'required',
                            'location_id'  => 'required',
                            'dob' => 'required|date',
                            'gender' => 'required|in:Male,Female'
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
                                        ->leftjoin('user_devices as ud','u.id','=','ud.user_id')
                                        //->where('u.id',$userID)
                                        ->where('ud.access_token',$token)                                        
                                        ->select('u.id as user_id','u.full_name','u.email','phone_number','u.zip_code',
                                                'uaso.option as gender','l.id as location_id','l.name as location','ud.access_token',
                                                DB::raw('MAX(IF(ua3.alias = "points_earned", uai.attribute_value, 0)) AS points_earned'),
                                                DB::raw('MAX(IF(ua3.alias = "points_spent", uai.attribute_value, 0)) AS points_spent'),
                                                DB::raw('MAX(IF(ua3.alias = "bookings_made", uai.attribute_value, 0)) AS bookings_made'),
                                               DB::raw('date(uad.attribute_value) as dob'))
                                        ->groupby('u.id')
                                        ->first();
                                       
                                     

            //Read all the preferred locations
            $preferredLocations=Profile::getUserPreferences($token);

            //Read all the AREAS related to user's location            
            $cityAreas=Locations::readCityArea($queryProfileResult->location_id);            

            //array to contain the response to be sent back to client
            $arrResponse = array();

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
                                            'dob' => $queryProfileResult->dob,
                                            'selectedPreferences' => $preferredLocations['data'],
                                            'areas' => $cityAreas['data'],
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
            //print_r($id); die();
            //updating data in users table
            $userTableData = array(
                                    'full_name' => $data['full_name'],
                                    'phone_number' => $data['phone_number'],
                                    'zip_code' => $data['zip_code'],
                                    'location_id' => $data['location_id'],
                                    'updated_at' => date('Y-m-d H:i:s'),
                                  );
            DB::table('users')
                ->where('id', $userID->user_id)
                ->update($userTableData);


            $queryAttribute = DB::table('user_attributes') -> select('id','alias') -> get();

            //array having attribute alias and id as key value pair
            $arrAttribute = array();
            foreach($queryAttribute as $row) {
                $arrAttribute[$row->alias] = $row->id;
            }


            $dobUpdate = DB::table('user_attributes_date')
                        		->where('user_id', $userID->user_id)
                        		->where('user_attribute_id',$arrAttribute['date_of_birth'])
                        		->update(array('attribute_value' => $data['dob']));
			
			if(!$dobUpdate) {
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
							'user_id'           				=> $userID->user_id,
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

}
//end of class Profile.
//end of file Profile.php