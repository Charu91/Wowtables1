<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservAddonVarientDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reserv_addon_varient_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reservation_id');
			$table->integer('option_id');
			$table->integer('no_of_persons');
			$table->integer('reservation_status_id');
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
		Schema::drop('reserv_addon_varient_details');
	}

}
