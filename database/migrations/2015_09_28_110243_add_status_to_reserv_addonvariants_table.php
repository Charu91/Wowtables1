<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToReservAddonvariantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('reservation_addons_variants_details', function(Blueprint $table)
		{
			$table->integer('reservation_status_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('reservation_addons_variants_details', function(Blueprint $table)
		{
			$table->dropColumn('reservation_status_id');
		});
	}

}
