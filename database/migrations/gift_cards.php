<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GiftCards extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gift_cards', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('gift_card_id');
			$table->string('buyer');
			$table->string('buyer_email');
			$table->string('buyer_contact');
			$table->string('buyer_detail');
			$table->integer('number_of_guest');
		    $table->string('cash_value');
			$table->string('name_of_giftee');
			$table->string('giftee_email');
			$table->string('contact_of_giftee');
			$table->date('gift_card_expire_date');
			$table->enum('redeem', ['yes', 'no','cancel']);
			$table->string('credit_remaining');
			$table->string('notes');
			$table->timestamps();
		});
	}
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('gift_cards', function(Blueprint $table)
		{
			//
		});
	}

}
