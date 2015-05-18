<?php namespace WowTables\Http\Models\Frontend;
use DB;
use Config;
/**
 * Class User
 * @package WowTables\Http\Models
 */
class CommonModel {
 
  public function getAllCuisines()
  {
    //query to read cuisines
    $queryCuisine = DB::table('product_attributes_select_options as paso')
                ->join('product_attributes as pa','pa.id','=','paso.product_attribute_id')
                ->where('pa.alias','cuisines')
                ->select('paso.id','paso.option')
                ->get();
    
    #setting up the cuisines filter information
    $arrCuisine = array();
    if($queryCuisine) {
      foreach ($queryCuisine as $row) {
        $arrCuisine[$row->id] = $row->option;
      }
    }
    return $arrCuisine;
  }

  public function getAllAreas()
  {
    //query to read cuisines
    $queryAreas = DB::table('locations as l')
                ->where('l.type','area')
                ->where('l.visible','1')
                ->select('l.id','l.slug','l.name')
                ->get();
    
    #setting up the cuisines filter information
    $arrAreas = array();
    if($queryAreas) {
      foreach ($queryAreas as $row) {
        $arrAreas[$row->id]['slug'] = $row->slug;
        $arrAreas[$row->id]['name'] = $row->name;
      }
    }
    return $arrAreas;
  }

  public function getAllPrices()
  {
    $arrPrices = array('bl1000'=>'Below #rupee#1000', 'bw1000_2000'=>'#rupee#1000 - #rupee#2000', 'ab2000'=>'Above #rupee#2000');
    return $arrPrices;
  }

}