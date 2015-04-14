<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixMediablesCompositeKey extends Migration {

	private $table = 'mediables';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::update("ALTER TABLE mediables DROP PRIMARY KEY, ADD PRIMARY KEY(mediable_id, mediable_type, media_id)"); 
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::update("ALTER TABLE mediables DROP PRIMARY KEY, ADD PRIMARY KEY(mediable_id, media_id)"); 
	}

}
