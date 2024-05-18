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
		Schema::create('questions', function (Blueprint $table) {
			$table->id();
			$table->longText('question_ar')->nullable();
			$table->longText('question_en')->nullable();
			$table->string('Justify', 255)->nullable();
			$table->string('file')->nullable();
			$table->enum('type', ['audio', 'video', 'image', 'text']);
			$table->enum('related', ['exams', 'assignments']);
			$table->foreignId("course_id")->constrained("courses")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("lecture_id")->constrained("lectures")->cascadeOnDelete()->cascadeOnUpdate();
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
		Schema::dropIfExists('questions');
	}
};
