<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetaFieldsToPages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pages', function($table)
		{
			$table->string('html_title');
			$table->text('meta_description');
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
			$table->dropColumn('html_title');
			$table->dropColumn('meta_description');
		});
	}

}
