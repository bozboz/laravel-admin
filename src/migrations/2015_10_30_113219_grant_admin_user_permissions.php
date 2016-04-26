<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GrantAdminUserPermissions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$users = DB::table('users')->where('is_admin', true)->select('id')->get();

		foreach($users as $user) {
			$this->grantWildCardPermissionForUser($user);
		}
	}

	protected function grantWildCardPermissionForUser($user)
	{
		$now = new DateTime;

		DB::table('permissions')->insert([
			'user_id' => $user->id,
			'action' => '*',
			'created_at' => $now,
			'updated_at' => $now
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('permissions')->truncate();
	}

}
