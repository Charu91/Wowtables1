<?php namespace WowTables\Http\Models\Frontend;
use DB;
use Config;
use URL;
//use WowTables\Http\Models\Eloquent\Products\ProductVendorLocationBlockSchedule;
use WowTables\Http\Models\Eloquent\Vendors\VendorLocationBlockedSchedules;
use WowTables\Http\Models\Eloquent\ReservationDetails;
use WowTables\Http\Models\Eloquent\Vendors\VendorLocationBookingTimeRangeLimit;
use WowTables\Http\Models\Eloquent\Products\ProductVendorLocationBlockedSchedule;
use WowTables\Http\Models\Eloquent\Products\ProductVendorLocationBookingTimeRangeLimit;
use WowTables\Http\Models\Schedules;
use WowTables\Http\Models\Experiences;
use Illuminate\Database\Eloquent\Model;
/**
 * Class User
 * @package WowTables\Http\Models
 */
class ReservationModel extends Model {
 


    /**
   * Table to be used by this model.
   * 
   * @var   string
   * @access  protected
   * @since 1.0.0
   */
  protected $table = 'reservation_details';
   //-----------------------------------------------------------------
  
  /**
   * Returns the Reservation record of a user.
   * 
   * @static  true
   * @access  public
   * @param integer   $userID
   * @param integer   $start
   * @param integer   $limit
   * @return  array
   * @since 1.0.0
   */
  public static function getReservationRecord($userID,$start=NULL,$limit=NULL) {
    /*$queryResult = DB::table('reservation_details as rd')
            ->leftJoin('vendor_locations as vl','vl.id','=', 'rd.vendor_location_id')
            //->leftJoin('product_vendor_locations as pvl','pvl.id','=','rd.product_vendor_location_id')
            //->leftJoin('product_vendor_locations as pvl','pvl.product_id','=','rd.product_id and pvl.vendor_location_id = rd.vendor_location_id')
            ->leftJoin('product_vendor_locations as pvl', function($join){
                $join->on('pvl.product_id', '=', 'rd.product_id');
                $join->on(DB::raw('(  and pvl.vendor_location_id = rd.vendor_location_id)'));
              })
            ->leftJoin('products','products.id','=','pvl.product_id')
            ->leftJoin('vendors','vendors.id','=','vl.vendor_id')
            ->leftJoin('product_attributes_text as pat','pat.product_id','=','products.id')
            ->leftJoin('product_attributes as pa','pa.id','=','pat.product_attribute_id')
            ->leftJoin('vendor_location_attributes_text as vlat','vlat.vendor_location_id','=','vl.id')
            ->leftJoin('vendor_attributes as va','va.id','=','vlat.vendor_attribute_id')
            ->leftJoin('vendor_locations as vl2','vl2.id','=','pvl.vendor_location_id')
            ->leftJoin('locations as ploc','ploc.id','=','vl2.location_id')
            ->leftJoin('vendor_location_address as pvla','pvla.vendor_location_id','=','pvl.vendor_location_id')
            ->leftJoin('vendor_location_address as vvla','vvla.vendor_location_id','=','rd.vendor_location_id')
            ->leftJoin('locations as vloc', 'vloc.id','=', 'vl.location_id')
            ->where('rd.user_id', $userID)
            ->whereIn('reservation_status',array('new','edited'))
            ->select('rd.id','rd.user_id','rd.reservation_status','rd.reservation_date',
                  'rd.reservation_time','rd.no_of_persons', 'products.name as product_name','vendors.id as vendor_id',
                   'vendors.name as vendor_name', 'rd.reservation_type', 'products.id as product_id',
                   'rd.vendor_location_id', 'rd.product_vendor_location_id', 'rd.special_request',
                   'rd.giftcard_id', 'rd.guest_name', 'rd.guest_name', 'rd.guest_email',
                   'rd.guest_phone', 'rd.points_awarded',
                   DB::raw('MAX(IF(pa.alias="short_description", pat.attribute_value,"")) AS product_short_description'),
                   DB::raw('MAX(IF(va.alias="short_description", vlat.attribute_value, ""))AS vendor_short_description'),
                   'ploc.name as product_locality','pvla.address as product_address',
                   'vloc.name as vendor_locality', 'vvla.address as vendor_address', 
                   'vvla.latitude as latitude', 'vvla.longitude as longitude', 'products.slug as product_slug',
                   'ploc.name as city',DB::raw('DAYNAME(rd.reservation_date) as dayname'),'pvl.id as product_vendor_location_id')
            ->orderBy('rd.reservation_date','asc')
            ->orderBy('rd.reservation_time','asc')
            ->groupBy('rd.id') 
            ->get();*/

         $queryResult = DB::select("select `rd`.`id`, `rd`.`user_id`, `rd`.`reservation_status`, `rd`.`reservation_date`, `rd`.`reservation_time`, `rd`.`no_of_persons`,
                                   `products`.`name` as `product_name`, `vendors`.`id` as `vendor_id`, `vendors`.`name` as `vendor_name`,
                                    `rd`.`reservation_type`, `products`.`id` as `product_id`, `rd`.`vendor_location_id`,
                                     `rd`.`product_vendor_location_id`,
                                   `rd`.`special_request`, `rd`.`giftcard_id`, `rd`.`guest_name`, 
                                   `rd`.`guest_name`, `rd`.`guest_email`, `rd`.`guest_phone`, 
                                   `rd`.`points_awarded`, MAX(IF(pa.alias='short_description', pat.attribute_value,'')) AS product_short_description,
                                    MAX(IF(va.alias='short_description', vlat.attribute_value, ''))AS vendor_short_description, `ploc`.`name` as `product_locality`,
                                     `pvla`.`address` as `product_address`, `vloc`.`name` as `vendor_locality`,
                                     `vvla`.`address` as `vendor_address`, `vvla`.`latitude` as `latitude`,
                                      `vvla`.`longitude` as `longitude`, `products`.`slug` as `product_slug`, `ploc`.`name` as `city`,
                                       DAYNAME(rd.reservation_date) as dayname,pvl.id as product_vendor_location_id 
                                    from `reservation_details` as `rd` 
                                    left join `vendor_locations` as `vl` on `vl`.`id` = `rd`.`vendor_location_id`
                                    left join `product_vendor_locations` as `pvl` on `pvl`.`product_id` = `rd`.`product_id` and pvl.vendor_location_id = `rd`.`vendor_location_id` 
                                    left join `products` on `products`.`id` = `pvl`.`product_id` 
                                    left join `vendors` on `vendors`.`id` = `vl`.`vendor_id` 
                                    left join `product_attributes_text` as `pat` on `pat`.`product_id` = `products`.`id` 
                                    left join `product_attributes` as `pa` on `pa`.`id` = `pat`.`product_attribute_id` 
                                    left join `vendor_location_attributes_text` as `vlat` on `vlat`.`vendor_location_id` = `vl`.`id` 
                                    left join `vendor_attributes` as `va` on `va`.`id` = `vlat`.`vendor_attribute_id` 
                                    left join `vendor_locations` as `vl2` on `vl2`.`id` = `pvl`.`vendor_location_id` 
                                    left join `locations` as `ploc` on `ploc`.`id` = `vl2`.`location_id` 
                                    left join `vendor_location_address` as `pvla` on `pvla`.`vendor_location_id` = `pvl`.`vendor_location_id` 
                                    left join `vendor_location_address` as `vvla` on `vvla`.`vendor_location_id` = `rd`.`vendor_location_id` 
                                    left join `locations` as `vloc` on `vloc`.`id` = `vl`.`location_id`
                                     where `rd`.`user_id` = $userID and `reservation_status` in ('new', 'edited') 
                                    group by `rd`.`id` order by `rd`.`reservation_date` asc, `rd`.`reservation_time` asc");
    //echo $queryResult->toSql();
    
    //array to store the information
    $arrData = array();
    
    //sub array to store the previous reservation information
    $arrData['data']['pastReservation'] = array();
    
    //sub array to store the upcoming reservation information
    $arrData['data']['upcomingReservation'] = array(); 
    
    if($queryResult) {
      //converting current day time to timestamp
      $currentTimestamp = strtotime(date('Y-m-d H:i:s'));
      
      //getting each reservation addons
      foreach($queryResult as $row) {
        $arrReservation[] = $row->id;
      }
      
      //array to keep record of addons of reservation
      $arrSelectedAddOn = array();
      $arrSchedule = array();
      $arrAddOn = array();
      
      
      $arrSelectedAddOn = self::getReservationAddonsDetails($arrReservation);
      //$arrAddOn = Experiences::readExperienceAddOns($row->product_id);
      
      foreach($queryResult as $row) {
        //converting reservation day time to timestamp
        $reservationTimestamp = strtotime($row->reservation_date.' '.$row->reservation_time);
        if($reservationTimestamp >= $currentTimestamp) {
          if($row->reservation_type == 'experience') {
            $day = date('D',strtotime($row->reservation_date));
            $arrSchedule = Schedules::getExperienceLocationSchedule($row->product_id, NULL,  $day);
            $arrAddOn = Experiences::readExperienceAddOns($row->product_id);
            
          }
          else if($row->reservation_type == 'alacarte') {
            $day = date('D',strtotime($row->reservation_date));
            $arrSchedule = Schedules::getVendorLocationSchedule($row->vendor_location_id, $day);
          }
        }
        $arrDatum = array(
                  'id' => $row->id,
                  'short_description' => (empty($row->product_short_description)) ? $row->vendor_short_description : $row->product_short_description,
                  'status' => $row->reservation_status,
                  'date' => $row->reservation_date,
                  'dayname' => $row->dayname,
                  'time' => $row->reservation_time,
                  'no_of_persons' => $row->no_of_persons,
                  'name' => (empty($row->vendor_name)) ? $row->product_name : $row->product_name,
                  'type' => $row->reservation_type,
                  'product_id' => ($row->product_vendor_location_id == 0) ? $row->vendor_id:$row->product_id,
                  //'vl_id' => ($row->vendor_location_id == 0) ? $row->product_vendor_location_id:$row->vendor_location_id,
                  'vl_id' => $row->product_vendor_location_id,
                  'special_request' => (is_null($row->special_request)) ? "" : $row->special_request,
                  'giftcard_id' => (is_null($row->giftcard_id)) ? "" : $row->giftcard_id,
                  'guest_name' => $row->guest_name,
                  'guest_email' => $row->guest_email,
                  'guest_phone' => $row->guest_phone,
                  'reward_point' => $row->points_awarded,
                  'latitude' => $row->latitude,
                  'longitude' => $row->longitude,
                  'product_slug' => $row->product_slug,
                  'address' => (empty($row->product_address)) ? $row->vendor_address : $row->product_address,
                  'locality' => (empty($row->product_locality)) ? $row->vendor_locality : $row->product_locality,
                  'city' => $row->city,
                  'selected_addon' => (array_key_exists($row->id, $arrSelectedAddOn)) ? $arrSelectedAddOn[$row->id]:array(),
                  'day_schedule' => $arrSchedule,
                  'addons' => $arrAddOn,
                );
        
        if($reservationTimestamp >= $currentTimestamp ) {
          array_push($arrData['data']['upcomingReservation'],$arrDatum);
        }
        else {
          array_push($arrData['data']['pastReservation'],$arrDatum);
        }
        
      }
      $arrData['data']['pastReservationCount'] = count($arrData['data']['pastReservation']);
      $arrData['data']['upcomingReservationCount'] = count($arrData['data']['upcomingReservation']);
      $arrData['status'] = Config::get('constants.API_SUCCESS');
    }
    else {
      $arrData['status'] = Config::get('constants.API_SUCCESS');
      $arrData['msg'] = 'No matching record found.';
      $arrData['data']['pastReservationCount'] = 0;  
      $arrData['data']['upcomingReservationCount'] = 0; 
    }
    return $arrData;
  }

  //-----------------------------------------------------------------
  /**
   * Reads the details of the add-ons associated with a reservation.
   * 
   * @access  public
   * @static
   * @param array   $arrReservation
   * @return  array 
   */
  public static function getReservationAddonsDetails($arrReservation) {
    $queryResult = DB::table('reservation_addons_variants_details as ravd')
              ->join('products as p','p.id','=','ravd.options_id')
              ->whereIn('ravd.reservation_id',$arrReservation)
              ->select('ravd.id','ravd.options_id as prod_id','ravd.no_of_persons as qty',
                    'ravd.reservation_id')
              ->get();
    
    //array to store the addons details
    $arrData = array();
    
    foreach($queryResult as $row) {
      if(array_key_exists($row->reservation_id, $arrData)) {
        $arrData[$row->reservation_id][] = array(
                            'id' => $row->id,
                            'prod_id' => $row->prod_id,
                            'qty' => $row->qty
                          );
      }
      else {
        $arrData[$row->reservation_id][] = array(
                            'id' => $row->id,
                            'prod_id' => $row->prod_id,
                            'qty' => $row->qty
                          );
      }
    }
    return $arrData;
  }


  //-----------------------------------------------------------------
  
  /**
   * Updates the status of the reservation to cancel.
   * 
   * @access  public
   * @static  true
   * @param integer $reservationID
   * @return  array
   * @since 1.0.0
   */
  public static function cancelReservation($reservationID, $reservationType) {
    //array to hold response
    $arrResponse = array();
    
    $queryResult = Self::where('id',$reservationID)
              //->where('user_id', $userID)
              ->where('reservation_status','!=','cancel')
              ->first()->toArray();
    
  
    if($queryResult) {
      $reservation = Self::find($reservationID);
      $reservation->reservation_status = 'cancel';
      $reservation->save();
      
      $arrResponse['status'] = 'ok';
    }
    else {
      $arrResponse['status'] = 'failed';
      $arrResponse['msg'] = 'Sorry. No Such record exists.';
    }
    
    return $arrResponse;
  }
  
  //----------------------------------------------------------------- 

}