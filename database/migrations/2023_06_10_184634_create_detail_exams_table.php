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
		Schema::create('detail_exams', function (Blueprint $table) {
			$table->id();

			$table->foreignId("course_id")->constrained("courses")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("lecture_id")->constrained("lectures")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("user_id")->constrained("users")->cascadeOnDelete()->cascadeOnUpdate();
			$table->string('score');
			$table->string('correct');
			$table->string('mistake');
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
		Schema::dropIfExists('detail_exams');
	}
};
