<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class Animal extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('food')->nullable();
			$table->string('collar')->nullable();
			$table->string('bedding')->nullable();
			// field that contain the type of the class saved
			$table->string('type');
			$table->timestamps();
			// we use softdeletes here and in the models
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('');
	}

}
