<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRestFaqCategoryType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('restaurant_faqs', function(Blueprint $table)
		{
			$table->dropColumn('category');
		});
		Schema::table('restaurant_faqs', function(Blueprint $table)
		{
			//Can be a separate table
			$table->enum('category',['About WowTables','Experience & Reservations','Payments','Points & Rewards','Gift Cards'
				,'VIP Menus','Parties & Events','Masterclasses','Career','Press'])->default('About WowTables')->after('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('restaurant_faqs', function(Blueprint $table)
		{
			Schema::table('restaurant_faqs', function(Blueprint $table)
			{
				$table->dropColumn('category');
			});
			Schema::table('restaurant_faqs', function(Blueprint $table)
			{
				$table->string('category')->after('id');
			});
		});
	}

}
