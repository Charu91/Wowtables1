<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservStatusAttrMapsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reserv_status_attr_maps', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reservation_status_id');
			$table->integer('reservation_attribute_id');
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
		Schema::drop('reserv_status_attr_maps');
	}

}
