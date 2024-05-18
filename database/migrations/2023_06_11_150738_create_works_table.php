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
		Schema::create('works', function (Blueprint $table) {
			$table->id();
			$table->string('photo', 500);
			$table->string("name_ar", 100);
			$table->string("name_en", 100);
			$table->longText("description_ar");
			$table->longText("description_en");
			$table->foreignId("work_section_id")->constrained("work_sections")->cascadeOnDelete()->cascadeOnUpdate();
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
		Schema::dropIfExists('works');
	}
};
