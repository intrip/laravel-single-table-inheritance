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
			$table->string('name');
			$table->string('food');
			$table->string('toy');
			$table->string('collar')->nullable();
			$table->string('bedding')->nullable();
			$table->string('type');
			$table->timestamps();
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
