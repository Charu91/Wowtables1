<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sharings', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('user_id')->default('0');
			$table->string('reservation_id')->default('null');
			$table->bigInteger('product_id')->default('0');
			$table->bigInteger('product_vendor_location_id')->default('0');
			$table->bigInteger('restaurant_id')->default('0');
			$table->bigInteger('restaurant_location_id')->default('0');
			$table->string('email_address')->default('null');
			$table->string('type')->default('null');
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
		Schema::drop('sharings');
	}

}
