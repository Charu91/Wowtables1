<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservAttributesVarcharLog extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reservation_attributes_varchar_log', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reservation_status_log_id');
			$table->integer('reservation_attribute_id');
			$table->string('old_attribute_value');
			$table->string('new_attribute_value');
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
		Schema::drop('reservation_attributes_varchar_log');
	}

}
