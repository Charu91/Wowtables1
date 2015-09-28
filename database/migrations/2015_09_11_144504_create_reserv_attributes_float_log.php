<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservAttributesFloatLog extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reservation_attributes_float_log', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reservation_status_log_id');
			$table->integer('reservation_attribute_id');
			$table->decimal('old_attribute_value',14,4);
			$table->decimal('new_attribute_value',14,4);
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
		Schema::drop('reservation_attributes_float_log');
	}

}
