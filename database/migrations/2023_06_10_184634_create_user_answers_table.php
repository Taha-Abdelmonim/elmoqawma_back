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
		Schema::create('user_answers', function (Blueprint $table) {
			$table->id();
			$table->foreignId("detail_exam_id")->constrained("detail_exams")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("question_id")->constrained("questions")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("answer_id")->constrained("answers")->cascadeOnDelete()->cascadeOnUpdate();
			$table->boolean('status');
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
		Schema::dropIfExists('user_answers');
	}
};
