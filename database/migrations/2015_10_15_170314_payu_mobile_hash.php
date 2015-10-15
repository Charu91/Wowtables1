<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayuMobileHash extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payu_mobile_hash', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('reservation_id');
			$table->string('hash');
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
		Schema::drop('payu_mobile_hash');
	}

}
