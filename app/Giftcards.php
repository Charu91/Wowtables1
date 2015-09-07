<?php namespace WowTables;

use Illuminate\Database\Eloquent\Model;
use DB;

class Giftcards extends Model {

	protected $table = "giftcards_purchase_details";

	public static function updateDetails($id,$transactionID){
		DB::table('giftcards_purchase_details')
			->where('id', $id)
			->update(array('order_status' => 'paid','transaction_id' => $transactionID));
	}

	public static function getExperienceDetails($id){
		//var_dump($id);
		$experiencename = DB::table('giftcards_purchase_details as gpd')
						->leftJoin('products as p','gpd.experience_id', '=', 'p.id')
						->where('gpd.id', $id)
						->select('p.name')
						->get();

		//echo "<pre>"; print_r($experiencename); die;

		return $experiencename;
	}

	public static function getAddonsDetails($id){
		$addonnames = DB::table('giftcards_addons_purchase_details as gapd')
					->leftJoin('giftcards_purchase_details as gpd','gapd.giftcards_purchase_details_id','=','gpd.id')
					->leftJoin('products as p','gapd.addon_id','=','p.id')
					->where('gapd.giftcards_purchase_details_id', $id)
					->select('p.name','gapd.no_of_guests')
					->get();

		return $addonnames;
	}

}
