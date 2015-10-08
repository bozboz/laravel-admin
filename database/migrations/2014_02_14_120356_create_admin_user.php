<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateAdminUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('users')->insert([
			'first_name' => 'Bozboz',
			'last_name' => 'Admin',
			'email' => 'admin@bozboz.co.uk',
			'is_admin' => 1,
			'password' => app('hash')->make('gukbeb6s')
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('users')->where('email', 'admin@bozboz.co.uk')->delete();
	}

}
