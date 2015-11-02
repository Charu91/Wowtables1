<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRestdefaultsInDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_devices', function(Blueprint $table)
		{
			$table->dropColumn('rest_access_token');
			$table->dropColumn('rest_access_token_expires');
			$table->dropColumn('rest_notification_id');
		});
		Schema::table('user_devices', function(Blueprint $table)
		{
			$table->string('rest_access_token')->after('access_token')->nullable()->default(null);
			$table->timestamp('rest_access_token_expires')->after('access_token_expires')->nullable()->default(null);
			$table->string('rest_notification_id')->after('notification_id')->nullable()->default(null);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_devices', function(Blueprint $table)
		{
			//
		});
	}

}
