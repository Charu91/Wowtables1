<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReservationAttributesText extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reservation_attributes_text', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reservation_id');
			$table->integer('reservation_attribute_id');
			$table->text('attribute_value');
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
		Schema::drop('reservation_attributes_text');
	}

}
