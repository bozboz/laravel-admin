<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Bozboz\Admin\Models\User;

class CreateAdminUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$user = new User();
		$user->fill(array(
			'username' => 'admin',
			'email' => 'admin@bozboz.co.uk',
			'name' => 'Bozboz Admin'
		));
		$user->password = 'gukbeb6s';
		$user->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		User::where('email', '=', 'admin@bozboz.co.uk')->delete();
	}

}
