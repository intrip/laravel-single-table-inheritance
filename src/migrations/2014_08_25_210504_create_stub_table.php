<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStubTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stub', function ($table){
      $table->increments('id');
      $table->string('working')->nullable();
      $table->string('working_child')->nullable();
      // the table type field
      $table->string('type');
      $table->timestamps();
      // for model with soft delete
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
		Schema::drop('stub');
	}

}
