<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('media', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('filename');
			$table->string('type');
			$table->timestamps();
		});

		Schema::create('mediables', function(Blueprint $table)
		{
			$table->morphs('mediable');
			$table->integer('media_id');
			$table->primary(array('media_id', 'mediable_id'));
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('media');
		Schema::drop('mediables');
	}

}
