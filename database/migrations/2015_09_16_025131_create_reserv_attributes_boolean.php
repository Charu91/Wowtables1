<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservAttributesBoolean extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reservation_attributes_boolean', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reservation_id');
			$table->integer('reservation_attribute_id');
			$table->boolean('attribute_value');
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
		Schema::drop('reservation_attributes_boolean');
	}

}
