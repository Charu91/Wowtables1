<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeReservStatusLogColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('reservation_status_log', function(Blueprint $table)
		{
			Schema::table('reservation_status_log', function(Blueprint $table)
			{
				$table->dropColumn('old_reservation_id');
				$table->dropColumn('new_reservation_id');
			});
			Schema::table('reservation_status_log', function(Blueprint $table)
			{
				$table->integer('old_reservation_status_id')->after('user_id');
				$table->integer('new_reservation_status_id')->after('old_reservation_status_id');
			});
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
			Schema::table('reservation_status_log', function(Blueprint $table)
			{
				$table->dropColumn('old_reservation_status_id');
				$table->dropColumn('new_reservation_status_id');
			});
			Schema::table('reservation_status_log', function(Blueprint $table)
			{
				$table->integer('old_reservation_id')->after('user_id');
				$table->integer('new_reservation_id')->after('old_reservation_id');
			});
		});
	}

}
