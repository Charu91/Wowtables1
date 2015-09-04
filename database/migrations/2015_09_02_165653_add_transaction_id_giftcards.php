<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionIdGiftcards extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('giftcards_purchase_details', function(Blueprint $table)
		{
			$table->string('transaction_id')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('giftcards_purchase_details', function(Blueprint $table)
		{
			$table->dropColumn('transaction_id');
		});
	}

}
