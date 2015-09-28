<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeReservAttrFloatTodecimal extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('reservation_attributes_float', function(Blueprint $table)
		{
			$table->dropColumn('attribute_value');
		});
		Schema::table('reservation_attributes_float', function(Blueprint $table)
		{
			//Just for consistency
			$table->decimal('attribute_value',14,4)->after('reservation_attribute_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('reservation_attributes_float', function(Blueprint $table)
		{
			$table->dropColumn('attribute_value');
		});
		Schema::table('reservation_attributes_float', function(Blueprint $table)
		{
			$table->double('attribute_value',14,4)->after('reservation_attribute_id');
		});
	}

}
