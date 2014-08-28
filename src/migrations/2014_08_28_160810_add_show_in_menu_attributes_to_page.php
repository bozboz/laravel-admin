<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShowInMenuAttributesToPage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pages', function($table)
		{
			$table->boolean('show_in_main_menu');
			$table->boolean('show_in_footer_menu');
		});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pages', function($table)
		{
			$table->dropColumn(['show_in_main_menu', 'show_in_footer_menu']);
		});
	}

}
