<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->text('description');
			$table->integer('redirect_to_id');
			$table->string('meta_title');
			$table->text('meta_description');
			$table->integer('sorting');
			$table->string('template');
			$table->integer('parent_id')->default(0);
			$table->string('remember_token', 100)->nullable();
			$table->boolean('show_in_main_menu');
			$table->boolean('show_in_footer_menu');
			$table->boolean('status');
			$table->string('external_link', 255);
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
        Schema::drop('pages');
	}

}
