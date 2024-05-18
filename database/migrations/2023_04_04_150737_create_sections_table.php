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
		Schema::create('sections', function (Blueprint $table) {
			$table->id();
			$table->string("name_ar", 100);
			$table->string("name_en", 100);
			$table->string('photo', 500);
			$table->longText("description_ar");
			$table->longText("description_en");
			$table->timestamps();
//          $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sections');
	}
};
