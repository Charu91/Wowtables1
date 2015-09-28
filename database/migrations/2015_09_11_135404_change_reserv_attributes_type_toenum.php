<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeReservAttributesTypeToenum extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('reservation_attributes', function(Blueprint $table)
		{
			$table->dropColumn('type');
		});
		Schema::table('reservation_attributes', function(Blueprint $table)
		{
			$table->enum('type',['boolean','enum','datetime','float','int','single-select','multi-select','integer','text','varchar'])->default('boolean')->after('alias');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('reservation_attributes', function(Blueprint $table)
		{
			$table->dropColumn('type');
		});
		Schema::table('reservation_attributes', function(Blueprint $table)
		{
			$table->string('type')->after('alias');
		});
	}

}
