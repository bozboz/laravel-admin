<?php

use Bozboz\Admin\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarkAdminUserAsAdmin extends Migration {

	private $user;

	public function __construct()
	{
		$this->user = User::where('email', '=', 'admin@bozboz.co.uk')->firstOrFail();
	}

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->user->is_admin = 1;
		$this->user->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$this->user->is_admin = 0;
		$this->user->save();
	}
}
