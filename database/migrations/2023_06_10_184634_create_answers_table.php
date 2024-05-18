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
		Schema::create('answers', function (Blueprint $table) {
			$table->id();
			$table->string('answer');
			$table->enum('answer_type', ['image', 'text']);
			$table->boolean('status');
			$table->foreignId("question_id")->constrained("questions")->cascadeOnDelete()->cascadeOnUpdate();
			//$table->softDeletes();
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
		Schema::dropIfExists('answers');
	}
};
