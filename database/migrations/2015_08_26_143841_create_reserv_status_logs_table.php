<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservStatusLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reserv_status_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reservation_id');
			$table->integer('old_reservation_id');
			$table->integer('new_reservation_id');
			$table->integer('user_id');
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
		Schema::drop('reserv_status_logs');
	}

}
