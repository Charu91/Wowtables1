<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBirthdayBashesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('birthday_bashes', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('email');
            $table->string('name');
            $table->string('phone_no');
            $table->string('lunch_option');
            $table->string('city');
            $table->string('promotion_type');
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
		Schema::drop('birthday_bashes');
	}

}
