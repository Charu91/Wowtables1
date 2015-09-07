<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingPaymentSourceIdTransactionDetails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('transactions_details', function(Blueprint $table)
		{
			$table->string('source_type')->default("experience");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('transactions_details', function(Blueprint $table)
		{
			$table->dropColumn('source_type');
		});
	}

}
