<?php namespace WowTables\Http\Models;


use DB;
use URL;
use Config;

/**
 * Model class LaCarte.
 *
 * @version	1.0.0
 * @since	1.0.0
 * @author	Parth Shukla <shuklaparth@hotmail.com>
 */
class AdminReservations {

    public static function getMatchingRestaurants($matchString) {

        //array to store the restaurant details
        $data = array();


        $experienceResult = DB::table('vendors as v')
            ->where('v.name','LIKE',"%$matchString%")
            ->where('v.status', 'Publish')
            ->select('v.name', 'v.id')
            ->get();
        $arrData = array();
        if($experienceResult) {

            foreach($experienceResult as $row) {
                //echo "<pre>"; print_r($row);
                $arrData[] = $row->name;
            }
        }else{
            $arrData[] = "No Data Found!";
        }
        // echo '<pre>';print_r($arrData['data']);
        $arrDataNew =  array_unique( $arrData);
        return $arrDataNew;
    }

    public static function readRestaurantsExperiences($vendorID) {

        //query to read experiences available at the Restaurant
        $queryResult = DB::table('products as p')
            ->join('product_vendor_locations as pvl','pvl.product_id','=','p.id')
            ->join('vendor_locations as vl', 'vl.id','=', 'pvl.vendor_location_id')
            ->leftJoin('vendors as v','v.id','=','vl.vendor_id')
            ->leftJoin('vendor_location_address as vla','vla.vendor_location_id','=','vl.id')
            ->leftJoin('locations as loc','loc.id','=','vla.city_id')
            ->where('vl.vendor_id', $vendorID)
            ->where('pvl.status', 'Active')
            ->select(
                'p.id as product_id','p.name as exp_name','v.name as vendor_name','p.slug' ,'vla.city_id as city_id','pvl.id as pvl_id','loc.name as city_name','pvl.vendor_location_id as pvl_vli'
            )
            ->groupBy('product_id','vla.city_id')
            ->get();
        //echo "<pre>"; print_r($queryResult); die;

        return $queryResult;
    }

    public static function getResturantLocations($vendorID) {

        //query to read the vendor details
        $queryResult = DB::table('vendors as v')
            ->join('vendor_locations as vl', 'vl.vendor_id', '=', 'v.id')
            ->leftJoin('vendor_location_address as vla','vla.vendor_location_id','=','vl.id')
            ->leftJoin('locations as loc','loc.id','=','vla.city_id')
            ->leftJoin('locations as loc1','loc1.id','=','vla.area_id')
            ->where('vl.vendor_id',$vendorID)
            ->where('vl.status','Active')
            ->where('vl.a_la_carte',1)
            ->select('v.name', 'vl.id as vl_id','vl.slug','loc.name as city_name','vla.city_id as city_id','loc1.name as area_name')
            ->groupBy('vl.id')
            ->get();

        //echo "<pre>"; print_r($queryResult); die;


        return $queryResult;
    }

    public static function getExperienceEndDate($product_id){
        $queryExperience = DB::table('products')
            ->leftJoin('product_attributes_date as pad1','pad1.product_id','=','products.id')
            ->leftJoin('product_attributes as pa', 'pa.id','=','pad1.product_attribute_id')
            ->where('products.id',$product_id)
            ->select( DB::raw('MAX(IF(pa.alias = "end_date", pad1.attribute_value, "")) AS end_date'))->get();

        return $queryExperience;
    }

    public static function getExperienceBlockDates($expId=0)
    {
        $queryResult = DB::table('product_vendor_locations as pvl')
            ->leftJoin('product_vendor_location_block_schedules as pvlbs', 'pvlbs.product_vendor_location_id','=','pvl.id')
            ->where('pvl.product_id', $expId)
            ->select('pvl.id as vendor_location_id', 'block_date')
            ->get();


        $arrBlockedDate = array();

        foreach($queryResult as $row){
            $formatted_date = '';
            if(!empty($row->block_date))
            {
                $formatted_date =  date('Y-m-d',strtotime($row->block_date));
            }

            if(array_key_exists($row->vendor_location_id, $arrBlockedDate)) {
                $arrBlockedDate[$row->vendor_location_id][] = $formatted_date;
            }
            else {
                $arrBlockedDate[$row->vendor_location_id] = array($formatted_date);
            }


        }

        return $arrBlockedDate;

    }

    public static function zoho_add_booking($data)
    {
        $ch = curl_init();
        $config = array(
            //'authtoken' => 'e56a38dab1e09933f2a1183818310629',
            'authtoken' => '7e8e56113b2c2eb898bca9916c52154c',
            'scope' => 'creatorapi',
        );
        $curlConfig = array(
            CURLOPT_URL            => "https://creator.zoho.com/api/gourmetitup/xml/experience-bookings/form/bookings/record/add/",
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS     => $config + $data,
        );
        curl_setopt_array($ch, $curlConfig);
        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result; die;
        return	simplexml_load_string($result);
    }
}