<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResttokenToDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_devices', function(Blueprint $table)
		{
			$table->string('rest_access_token')->after('access_token');
			$table->timestamp('rest_access_token_expires')->after('access_token_expires');
			$table->string('rest_notification_id')->after('notification_id');
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
			$table->dropColumn('rest_access_token');
			$table->dropColumn('rest_access_token_expires');
			$table->dropColumn('rest_notification_id');
		});
	}

}
