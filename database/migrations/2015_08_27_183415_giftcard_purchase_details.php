<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GiftcardPurchaseDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('giftcards_purchase_details', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('receiver_name')->default('0');
			$table->string('receiver_email')->default('null');
			$table->integer('order_type')->default('0');
			$table->integer('amount')->default('0');
			$table->integer('experience_id')->default('0');
			$table->integer('no_of_guests')->default('0');
			$table->integer('total_amount')->default('0');
			$table->string('sending_type')->default('null');
			$table->string('mailing_address')->default('null');
			$table->integer('user_id')->default('0');
			$table->string('order_status')->default('unpaid');
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
		Schema::drop('giftcards_purchase_details');
	}

}
