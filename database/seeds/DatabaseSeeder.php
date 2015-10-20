<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use WowTables\Http\Controllers\ConciergeApi\ReservationController;

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

		//$this->call('RolesSeeder');
		//$this->call('UsersSeeder');
		//$this->call('VendorLocationContactsSeeder');// for adding to vendor location details table
		//$this->call('ReservationAttributesSeeder');
		//$this->call('ReservationStatusesSeeder');
		//$this->call('ReservationDetailsSeeder');
		//$this->call('ReservationStatusLogSeeder');
		//$this->call('ReservationAttrIntSeeder');
		//$this->call('ReservationAttrIntLogSeeder');
		//$this->call('ReservationAttrDateTimeSeeder');
		//$this->call('ReservationAttrDateTimeLogSeeder');
		//$this->call('ReservationAttrTextSeeder');
		//$this->call('ReservationAttrVarcharSeeder');
		//$this->call('ReservationSeatingStatusSeeder');
		//$this->call('ReservationRejectionReasonSeeder');
		//$this->call('ReservationAddonsVariantsDetailsSeeder');
		//$this->call('UserAttributesSeeder');
	}
}

class RolesSeeder extends Seeder{
	public function run(){
		//Add Roles
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		DB::table('roles')->truncate();
		DB::table('roles')->insert(array(
			array('name'=>'Admin','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Editor','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Gourmet','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'User','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Restaurant Manager','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Restaurant Executive','created_at' => Carbon::now(),'updated_at' => Carbon::now() )
		));
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}
}

class UsersSeeder extends Seeder{
	public function run(){
		//Add Concierge User
		DB::table('users')->insert(array(
			/*array('role_id'=>4,'location_id'=>3,'email'=>'kunal@wowtables.com','full_name'=>'Kunal Jain','phone_number'=>'9811111111','password'=>Hash::make('kunal'),'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('role_id'=>4,'location_id'=>3,'email'=>'manan@wowtables.com','full_name'=>'Manan Shah','phone_number'=>'9811111111','password'=>Hash::make('manan'),'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('role_id'=>4,'location_id'=>3,'email'=>'tanvi@wowtables.com','full_name'=>'Tanvi','phone_number'=>'9811111111','password'=>Hash::make('tanvi'),'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('role_id'=>5,'location_id'=>3,'email'=>'biju@binaryveda.com','full_name'=>'Biju Chandran','phone_number'=>'9811111111','password'=>Hash::make('biju'),'created_at' => Carbon::now(),'updated_at' => Carbon::now()),
            array('role_id'=>6,'location_id'=>3,'email'=>'aditya@binaryveda.com','full_name'=>'Aditya Gokula','phone_number'=>'9811111111','password'=>Hash::make('aditya'),'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('role_id'=>6,'location_id'=>3,'email'=>'shishir@binaryveda.com','full_name'=>'Shishir Pednekar','phone_number'=>'9811111111','password'=>Hash::make('shishir'),'created_at' => Carbon::now(),'updated_at' => Carbon::now() )
            array('role_id'=>6,'location_id'=>3,'email'=>'','full_name'=>'Karishma','phone_number'=>'','password'=>Hash::make('123456'),'created_at' => Carbon::now(),'updated_at' => Carbon::now() )
			array('role_id'=>6,'location_id'=>3,'email'=>'karishmaverenkar@gmail.com','full_name'=>'Karishma','phone_number'=>'9811111111','password'=>Hash::make('123456'),'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
			array('role_id'=>5,'location_id'=>3,'email'=>'biju@binaryveda.com','full_name'=>'Biju Chandran','phone_number'=>'9811111111','password'=>Hash::make('biju'),'created_at' => Carbon::now(),'updated_at' => Carbon::now()),

		));
	}
}

class VendorLocationContactsSeeder extends Seeder{
	public function run(){
		//Assign vendor location to concierge users
		DB::table('vendor_location_contacts')->insert(array(
			array('vendor_location_id'=>153,'name'=>'Biju','designation'=>'Restaurant Manager','email'=>'biju@binaryveda.com','phone_number'=>'9811111111','user_id'=>44229,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			/*array('vendor_location_id'=>153,'name'=>'Karishma','designation'=>'Restaurant Manager','email'=>'karishmaverenkar@gmail.com','phone_number'=>'9811111111','user_id'=>44226,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('vendor_location_id'=>203,'name'=>'Shishir Pednekar','designation'=>'Restaurant Executive','email'=>'shishir@binaryveda.com','phone_number'=>'9811111111','user_id'=>43646,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('vendor_location_id'=>203,'name'=>'Aditya Gokula','designation'=>'Restaurant Executive','email'=>'aditya@binaryveda.com','phone_number'=>'9811111111','user_id'=>43647,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('vendor_location_id'=>203,'name'=>'Manan Shah','designation'=>'Restaurant Executive','email'=>'manan@wowtables.com','phone_number'=>'9811111111','user_id'=>42964,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),*/
		));
	}
}

class ReservationAttributesSeeder extends Seeder {

	public function run()
	{
		DB::table('reservation_attributes')->truncate();
		DB::table('reservation_attributes')->insert(array(
			/*array('name'=>'Reservation Status','alias'=>'reservation_status_id','type'=>'int','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Reserved For','alias'=>'reservation_date','type'=>'datetime','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Concierge User','alias'=>'concierge_user_id','type'=>'int','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'No of Persons','alias'=>'party_size','type'=>'int','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Special Request','alias'=>'special_request','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Special Request Accepted','alias'=>'is_request_accepted','type'=>'boolean','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Special Request Response','alias'=>'special_request_response','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Gift Card on Reservation','alias'=>'reserv_gift_card_id','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Points Awarded','alias'=>'points_awarded','type'=>'int','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Note to WowTables','alias'=>'note_to_wowtables','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Seating Status','alias'=>'seating_status','type'=>'int','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Table Size','alias'=>'table_size','type'=>'int','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Gift Card on Closure','alias'=>'closure_gift_card_id','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Bill Amount','alias'=>'bill_amount','type'=>'float','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Prepaid Amount','alias'=>'prepaid_amount','type'=>'float','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Server Name','alias'=>'server_name','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Customer Preferences','alias'=>'customer_preferences','type'=>'varchar','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Reason for Rejection','alias'=>'rejection_reason_id','type'=>'int','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Experience_Attendees','alias'=>'experience_attendees','type'=>'int','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Alacarte Attendees','alias'=>'alacarte_attendees','type'=>'int','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('name'=>'Closed on','alias'=>'closed_on','type'=>'datetime','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),*/


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
			array('name'=>'Zoho Booking Update','alias'=>'zoho_booking_update','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now()),
			//Added by Biju
			array('name'=>'Reservation Status','alias'=>'reservation_status_id','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Special Request Accepted','alias'=>'is_request_accepted','type'=>'boolean','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Special Request Response','alias'=>'special_request_response','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Note to WowTables','alias'=>'note_to_wowtables','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Seating Status','alias'=>'seating_status','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Table Size','alias'=>'table_size','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Prepaid Amount','alias'=>'prepaid_amount','type'=>'float','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Server Name','alias'=>'server_name','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Customer Preferences','alias'=>'customer_preferences','type'=>'varchar','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Reason for Rejection','alias'=>'rejection_reason_id','type'=>'integer','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Closed on','alias'=>'closed_on','type'=>'datetime','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('name'=>'Note from WowTables','alias'=>'note_from_wowtables','type'=>'text','created_at' => Carbon::now(),'updated_at' => Carbon::now()),
		));
	}

}

class ReservationStatusesSeeder extends Seeder
{

	public function run()
	{
		DB::table('reservation_statuses')->truncate();
		DB::table('reservation_statuses')->insert(array(
			array('status' => 'new', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
			array('status' => 'edited', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
			array('status' => 'cancelled', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
			array('status' => 'unpaid', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
			array('status' => 'prepaid', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
			array('status' => 'accepted', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
			array('status' => 'rejected', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
			array('status' => 'closed', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()),
			array('status' => 'no show', 'no_show' => Carbon::now(), 'updated_at' => Carbon::now())
		));
	}
}

class ReservationStatusLogSeeder extends Seeder {

	public function run()
	{
		DB::table('reservation_status_log')->truncate();
		DB::table('reservation_status_log')->insert(array(
			array('reservation_id'=>22996,'user_id'=>36423,'old_reservation_status_id'=>1,'new_reservation_status_id'=>2,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() )
		));
	}

}

class ReservationDetailsSeeder extends Seeder {

	public function run()
	{
		//New
		DB::table('reservation_details')->insert(array(
			/*array('user_id'=>19,'reservation_status' =>'new','no_of_persons' =>5,
            'reservation_type'=>'experience','vendor_location_id'=>185,'product_id'=>233,'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
			/*array('user_id'=>20,'reservation_status' =>'new','no_of_persons' =>7,
                'reservation_type'=>'experience','vendor_location_id'=>221,'product_id'=>294,'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
			//Aditya Reservations
			/*
            array('user_id'=>20,'reservation_status' =>'new','no_of_persons' =>7,
                'reservation_type'=>'experience','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('user_id'=>35,'reservation_status' =>'new','no_of_persons' =>3,
                'reservation_type'=>'experience','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('user_id'=>40,'reservation_status' =>'new','no_of_persons' =>4,
                'reservation_type'=>'experience','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('user_id'=>42,'reservation_status' =>'new','no_of_persons' =>5,
                'reservation_type'=>'experience','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() )
            */

			//Kunal Reservations
			/*array('user_id'=>20,'reservation_status' =>'new','no_of_persons' =>7,
                'reservation_type'=>'experience','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('user_id'=>35,'reservation_status' =>'new','no_of_persons' =>3,
                'reservation_type'=>'alacarte','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('user_id'=>40,'reservation_status' =>'new','no_of_persons' =>4,
                'reservation_type'=>'experience','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('user_id'=>42,'reservation_status' =>'new','no_of_persons' =>5,
                'reservation_type'=>'experience','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),*/

			//Manan Reservations
			/*array('user_id'=>20,'reservation_status' =>'new','no_of_persons' =>7,
                'reservation_type'=>'experience','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('user_id'=>35,'reservation_status' =>'new','no_of_persons' =>3,
                'reservation_type'=>'experience','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('user_id'=>40,'reservation_status' =>'new','no_of_persons' =>4,
                'reservation_type'=>'alacarte','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('user_id'=>42,'reservation_status' =>'new','no_of_persons' =>5,
                'reservation_type'=>'experience','vendor_location_id'=>199,'product_id'=>559,'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/

		));
	}

}

class ReservationAttrIntSeeder extends Seeder {

	public function run()
	{
		DB::table('reservation_attributes_integer')->truncate();
		DB::table('reservation_attributes_integer')->insert(array(
			/*array('reservation_id'=>22983,'reservation_attribute_id' =>1,'attribute_value' =>1,
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            //Adding here again. In future remove from reservation_details
            array('reservation_id'=>22983,'reservation_attribute_id' =>4,'attribute_value' =>5,
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('reservation_id'=>22983,'reservation_attribute_id' =>9,'attribute_value' =>50,
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('reservation_id'=>22983,'reservation_attribute_id' =>11,'attribute_value' =>1,
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('reservation_id'=>22984,'reservation_attribute_id' =>1,'attribute_value' =>1,
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            //Adding here again. In future remove from reservation_details
            array('reservation_id'=>22984,'reservation_attribute_id' =>4,'attribute_value' =>7,
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('reservation_id'=>22984,'reservation_attribute_id' =>9,'attribute_value' =>200,
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('reservation_id'=>22984,'reservation_attribute_id' =>11,'attribute_value' =>1,
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
			//Adding here again. In future remove from reservation_details
			/************** For only Aditya **************************************************/
			array('reservation_id'=>22993,'reservation_attribute_id' =>29,'attribute_value' =>1,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22993,'reservation_attribute_id' =>11,'attribute_value' =>7,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22993,'reservation_attribute_id' =>15,'attribute_value' =>200,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22993,'reservation_attribute_id' =>33,'attribute_value' =>1,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

			array('reservation_id'=>22994,'reservation_attribute_id' =>29,'attribute_value' =>1,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22994,'reservation_attribute_id' =>11,'attribute_value' =>3,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22994,'reservation_attribute_id' =>15,'attribute_value' =>500,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22994,'reservation_attribute_id' =>33,'attribute_value' =>1,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

			array('reservation_id'=>22995,'reservation_attribute_id' =>29,'attribute_value' =>1,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22995,'reservation_attribute_id' =>11,'attribute_value' =>4,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22995,'reservation_attribute_id' =>15,'attribute_value' =>0,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22995,'reservation_attribute_id' =>33,'attribute_value' =>1,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

			array('reservation_id'=>22996,'reservation_attribute_id' =>29,'attribute_value' =>2,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22996,'reservation_attribute_id' =>11,'attribute_value' =>5,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22996,'reservation_attribute_id' =>15,'attribute_value' =>750,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22996,'reservation_attribute_id' =>33,'attribute_value' =>1,
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() )
			/* *********************************************************************************
           //Kunal Reservations
           array('reservation_id'=>22997,'reservation_attribute_id' =>1,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>22997,'reservation_attribute_id' =>4,'attribute_value' =>7,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>22997,'reservation_attribute_id' =>9,'attribute_value' =>200,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>22997,'reservation_attribute_id' =>11,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

           array('reservation_id'=>22998,'reservation_attribute_id' =>1,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>22998,'reservation_attribute_id' =>4,'attribute_value' =>3,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>22998,'reservation_attribute_id' =>9,'attribute_value' =>500,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>22998,'reservation_attribute_id' =>11,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

           array('reservation_id'=>22999,'reservation_attribute_id' =>1,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>22999,'reservation_attribute_id' =>4,'attribute_value' =>4,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>22999,'reservation_attribute_id' =>9,'attribute_value' =>0,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>22999,'reservation_attribute_id' =>11,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

           array('reservation_id'=>23000,'reservation_attribute_id' =>1,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23000,'reservation_attribute_id' =>4,'attribute_value' =>5,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23000,'reservation_attribute_id' =>9,'attribute_value' =>750,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23000,'reservation_attribute_id' =>11,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

           //Manan Reservations
           array('reservation_id'=>23001,'reservation_attribute_id' =>1,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23001,'reservation_attribute_id' =>4,'attribute_value' =>7,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23001,'reservation_attribute_id' =>9,'attribute_value' =>200,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23001,'reservation_attribute_id' =>11,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

           array('reservation_id'=>23002,'reservation_attribute_id' =>1,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23002,'reservation_attribute_id' =>4,'attribute_value' =>3,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23002,'reservation_attribute_id' =>9,'attribute_value' =>500,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23002,'reservation_attribute_id' =>11,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

           array('reservation_id'=>23003,'reservation_attribute_id' =>1,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23003,'reservation_attribute_id' =>4,'attribute_value' =>4,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23003,'reservation_attribute_id' =>9,'attribute_value' =>0,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23003,'reservation_attribute_id' =>11,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

           array('reservation_id'=>23004,'reservation_attribute_id' =>1,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23004,'reservation_attribute_id' =>4,'attribute_value' =>5,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23004,'reservation_attribute_id' =>9,'attribute_value' =>750,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
           array('reservation_id'=>23004,'reservation_attribute_id' =>11,'attribute_value' =>1,
               'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
		));
	}

}

class ReservationAttrIntLogSeeder extends Seeder {

	public function run()
	{
		DB::table('reservation_attributes_integer_log')->truncate();
		DB::table('reservation_attributes_integer_log')->insert(array(
			array('reservation_status_log_id'=>1,'reservation_attribute_id' =>11,'old_attribute_value' =>5,
				'new_attribute_value' =>6,'created_at' => Carbon::now(),'updated_at' => Carbon::now() )
		));
	}

}




class ReservationAttrDateTimeSeeder extends Seeder {

	public function run()
	{
		DB::table('reservation_attributes_date')->truncate();
		DB::table('reservation_attributes_date')->insert(array(
			/*array('reservation_id'=>22983,'reservation_attribute_id' =>2,'attribute_value' =>Carbon::now()->addDays(10),
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('reservation_id'=>22984,'reservation_attribute_id' =>2,'attribute_value' =>Carbon::now()->addDays(5),
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
			array('reservation_id'=>22993,'reservation_attribute_id' =>7,'attribute_value' =>Carbon::now()->addDays(7),
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22994,'reservation_attribute_id' =>7,'attribute_value' =>Carbon::now()->addDays(5),
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22995,'reservation_attribute_id' =>7,'attribute_value' =>Carbon::now()->addDays(-4),
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22996,'reservation_attribute_id' =>7,'attribute_value' =>Carbon::now()->addDays(3),
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() )/*,

				array('reservation_id'=>22997,'reservation_attribute_id' =>2,'attribute_value' =>Carbon::now()->addDays(7),
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>22998,'reservation_attribute_id' =>2,'attribute_value' =>Carbon::now()->addDays(5),
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>22999,'reservation_attribute_id' =>2,'attribute_value' =>Carbon::now()->addDays(4),
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23000,'reservation_attribute_id' =>2,'attribute_value' =>Carbon::now()->addDays(3),
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),

				array('reservation_id'=>23001,'reservation_attribute_id' =>2,'attribute_value' =>Carbon::now()->addDays(10),
						'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23002,'reservation_attribute_id' =>2,'attribute_value' =>Carbon::now()->addDays(12),
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23003,'reservation_attribute_id' =>2,'attribute_value' =>Carbon::now()->addDays(13),
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23004,'reservation_attribute_id' =>2,'attribute_value' =>Carbon::now()->addDays(14),
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
		));
	}

}

class ReservationAttrDateTimeLogSeeder extends Seeder {

	public function run()
	{
		DB::table('reservation_attributes_date_log')->truncate();
		DB::table('reservation_attributes_date_log')->insert(array(
			array('reservation_status_log_id'=>1,'reservation_attribute_id' =>7,'old_attribute_value' =>Carbon::now()->addDays(7),
				'new_attribute_value' =>Carbon::now()->addDays(3),'created_at' => Carbon::now(),'updated_at' => Carbon::now() )
		));
	}

}

class ReservationAttrTextSeeder extends Seeder {

	public function run()
	{
		DB::table('reservation_attributes_text')->truncate();
		DB::table('reservation_attributes_text')->insert(array(
			/*array('reservation_id'=>22983,'reservation_attribute_id' =>5,'attribute_value' =>'Prefer a table by the window.',
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('reservation_id'=>22984,'reservation_attribute_id' =>5,'attribute_value' =>'Need a baby seat',
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
			array('reservation_id'=>22993,'reservation_attribute_id' =>16,'attribute_value' =>'Need a baby seat',
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22994,'reservation_attribute_id' =>16,'attribute_value' =>'A corner with least noise',
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22994,'reservation_attribute_id' =>2,'attribute_value' =>'GD12345',
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22995,'reservation_attribute_id' =>16,'attribute_value' =>'I would like a window seat',
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22996,'reservation_attribute_id' =>16,'attribute_value' =>'Candle lit room',
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() )/*,
				array('reservation_id'=>22997,'reservation_attribute_id' =>5,'attribute_value' =>'Need a baby seat',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>22998,'reservation_attribute_id' =>5,'attribute_value' =>'A corner with least noise',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>22999,'reservation_attribute_id' =>8,'attribute_value' =>'GD12345',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23000,'reservation_attribute_id' =>5,'attribute_value' =>'Need a baby seat',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23001,'reservation_attribute_id' =>5,'attribute_value' =>'I would like a window seat',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23002,'reservation_attribute_id' =>5,'attribute_value' =>'Need a baby seat',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23003,'reservation_attribute_id' =>5,'attribute_value' =>'Candle lit room',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23004,'reservation_attribute_id' =>5,'attribute_value' =>'Need a baby seat',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
		));
	}

}

class ReservationAttrVarcharSeeder extends Seeder {

	public function run()
	{
		DB::table('reservation_attributes_varchar')->truncate();
		DB::table('reservation_attributes_varchar')->insert(array(
			/*array('reservation_id'=>22983,'reservation_attribute_id' =>17,'attribute_value' =>'Well mannered & Courteous.',
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
            array('reservation_id'=>22984,'reservation_attribute_id' =>17,'attribute_value' =>'Prefers Family Section.',
                'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
			/*array('reservation_id'=>22993,'reservation_attribute_id' =>37,'attribute_value' =>'Prefers Family Section.',
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22994,'reservation_attribute_id' =>37,'attribute_value' =>'Prefers Family Section.',
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22995,'reservation_attribute_id' =>37,'attribute_value' =>'Prefers Family Section.',
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22996,'reservation_attribute_id' =>37,'attribute_value' =>'Prefers Family Section.',
				'created_at' => Carbon::now(),'updated_at' => Carbon::now() )/*,
				array('reservation_id'=>22997,'reservation_attribute_id' =>17,'attribute_value' =>'Prefers Family Section.',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>22998,'reservation_attribute_id' =>17,'attribute_value' =>'Prefers Family Section.',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>22999,'reservation_attribute_id' =>17,'attribute_value' =>'Prefers Family Section.',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23000,'reservation_attribute_id' =>17,'attribute_value' =>'Prefers Family Section.',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23001,'reservation_attribute_id' =>17,'attribute_value' =>'Prefers Family Section.',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23002,'reservation_attribute_id' =>17,'attribute_value' =>'Prefers Family Section.',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23003,'reservation_attribute_id' =>17,'attribute_value' =>'Prefers Family Section.',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
				array('reservation_id'=>23004,'reservation_attribute_id' =>17,'attribute_value' =>'Prefers Family Section.',
					'created_at' => Carbon::now(),'updated_at' => Carbon::now() )*/
		));
	}

}

class ReservationSeatingStatusSeeder extends Seeder {

	public function run()
	{
		DB::table('reservation_seating_status')->truncate();
		DB::table('reservation_seating_status')->insert(array(
			array('status'=>'Upcoming','slug' =>'upcoming','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('status'=>'Successful','slug' =>'successful','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('status'=>'Cancelled','slug' =>'cancelled','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('status'=>'No Show','slug' =>'no_show','created_at' => Carbon::now(),'updated_at' => Carbon::now() )
		));
	}

}

class ReservationRejectionReasonSeeder extends Seeder {

	public function run()
	{	DB::table('reservation_rejection_reasons')->truncate();
		DB::table('reservation_rejection_reasons')->insert(array(
			array('reason'=>'Venue Blocked','slug' =>'venue_blocked','created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reason'=>'Venue Closed','successful' =>'upcoming','created_at' => Carbon::now(),'updated_at' => Carbon::now() )
		));
	}

}

class ReservationAddonsVariantsDetailsSeeder extends Seeder {

	public function run()
	{
		/*DB::table('reservation_addons_variants_details')->insert(array(
			array('reservation_id'=>22993,'no_of_persons' =>2,'options_id'=>560,'option_type'=>'addon','reservation_type'=>'experience','reservation_status_id'=>ReservationController::$new_status_id,'created_at' => Carbon::now(),'updated_at' => Carbon::now() ),
			array('reservation_id'=>22996,'no_of_persons' =>2,'options_id'=>560,'option_type'=>'addon','reservation_type'=>'experience','reservation_status_id'=>ReservationController::$new_status_id,'created_at' => Carbon::now(),'updated_at' => Carbon::now() )
		));*/
	}

}

class UserAttributesSeeder extends Seeder {

	public function run()
	{
		DB::table('user_attributes')->insert(array(
			array('name'=>'Customer Preferences','alias' =>'customer_preferences','type'=>'varchar')
		));
	}

}


