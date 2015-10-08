<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table) {
			$table->increments('id');

			$table->string('email')->unique();
			$table->string('password');
			$table->string('first_name', 35);
			$table->string('last_name', 35);
			$table->boolean('is_admin')->default(false);
			$table->string('remember_token', 100);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
