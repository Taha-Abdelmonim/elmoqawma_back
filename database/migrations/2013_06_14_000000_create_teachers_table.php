<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('teachers', function (Blueprint $table) {
			$table->id();
			$table->string('name_ar');
			$table->string('name_en');
			$table->longText("description_ar");
			$table->longText("description_en");
			$table->string('email')->nullable();
			$table->string('phone', 30)->nullable();
			$table->string('photo', 2048);
			$table->softDeletes();
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
		Schema::dropIfExists('teachers');
	}
};
