<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCareerModelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('career_models', function(Blueprint $table)
		{
			$table->increments('id');
			$table->longText('job_title');
			$table->longText('location');
			$table->longText('job_desc');
			$table->longText('job_qualification');
			$table->integer('status');
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
		Schema::drop('career_models');
	}

}
