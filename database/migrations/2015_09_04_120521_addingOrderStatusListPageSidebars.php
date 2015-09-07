<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingOrderStatusListPageSidebars extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('listpage_sidebar', function(Blueprint $table)
		{
			$table->integer('order_status')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('listpage_sidebar', function(Blueprint $table)
		{
			$table->dropColumn('order_status');
		});
	}

}
