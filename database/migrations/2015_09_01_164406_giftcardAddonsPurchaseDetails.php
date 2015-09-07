<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GiftcardAddonsPurchaseDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('giftcards_addons_purchase_details', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->integer('giftcards_purchase_details_id')->default('0');
			$table->integer('no_of_guests')->default('0');
			$table->integer('addon_id')->default('0');
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
		Schema::drop('giftcards_addons_purchase_details');
	}

}
