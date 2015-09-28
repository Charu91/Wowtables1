<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('UserTableSeeder');
		$this->call('ReservationAttributesSeeder');
		$this->call('ReservationStatusesSeeder');
	}
}

	class ReservationAttributesSeeder extends Seeder {

		public function run()
		{
			//delete users table records
			//DB::table('reservation_attributes')->delete();
			//insert some dummy records
			//var_dump(Carbon::now());die;
			DB::table('reservation_attributes')->insert(array(
				array('name'=>'Alternate Id','alias'=>'alternate_id','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Gift Card Id (From Reservation)','alias'=>'gift_card_id_reserv','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Customer Name','alias'=>'cust_name','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Contact No','alias'=>'contact_no','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Email','alias'=>'email','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Reservation Type','alias'=>'reserv_type','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Reservation Date & Time','alias'=>'reserv_datetime','type'=>'datetime','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Experience','alias'=>'experience','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Outlet','alias'=>'outlet','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'GIU Membership Id','alias'=>'giu_membership_id','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'No Of People Booked','alias'=>'no_of_people_booked','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Total Seated','alias'=>'total_seated','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Actual Experience Takers','alias'=>'actual_experience_takers','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Order Completed','alias'=>'order_completed','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Loyalty Points Awarded','alias'=>'loyalty_points_awarded','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Special Request','alias'=>'special_request','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Admin Comments','alias'=>'admin_comments','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Referral','alias'=>'referral','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Gift Card Id','alias'=>'gift_card_id','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Gift Card Value','alias'=>'gift_card_value','type'=>'float','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Special Offer Title','alias'=>'special_offer_title','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Special Offer Description','alias'=>'special_offer_desc','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				//array('name'=>'Booked Billings','alias'=>'booked_billings','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('name'=>'Api Added','alias'=>'api_added','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now()),
				array('name'=>'Actual Experience Takers','alias'=>'actual_experience_takers','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now()),
				array('name'=>'Actual Alacarte Takers','alias'=>'actual_alacarte_takers','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now()),
				array('name'=>'Total Billings','alias'=>'total_billings','type'=>'float','created_at' => Carbon::now(),'updated_at' => Carbon::now()),
				array('name'=>'Total Commission','alias'=>'total_commission','type'=>'float','created_at' => Carbon::now(),'updated_at' => Carbon::now()),
			));
		}

	}

	class ReservationStatusesSeeder extends Seeder
	{

		public function run()
		{
			DB::table('reservation_statuses')->delete();
			DB::table('reservation_statuses')->insert(array(
				array('status' => 'new', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
				array('status' => 'edited', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
				array('status' => 'cancelled', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
				array('status' => 'unpaid', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
				array('status' => 'prepaid', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
				array('status' => 'accepted', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
				array('status' => 'rejected', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
				array('status' => 'closed', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now())
			));
		}
	}


