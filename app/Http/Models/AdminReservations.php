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
                'p.id as product_id','p.name as exp_name','v.name as vendor_name','p.slug' ,'pvl.id as pvl_id','loc.name as city_name','pvl.vendor_location_id as pvl_vli'
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
            ->select('v.name', 'vl.id as vl_id','vl.slug','loc.name as city_name','loc1.name as area_name')
            ->groupBy('vl.id')
            ->get();

        //echo "<pre>"; print_r($queryResult); die;


        return $queryResult;
    }
}