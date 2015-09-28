<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeReservStatusLogName extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('reserv_status_logs', function(Blueprint $table)
		{
			$table->rename("reservation_status_log");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('reservation_status_log', function(Blueprint $table)
		{
			$table->rename("reserv_status_logs");
		});
	}

}
