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
		Schema::create('bank_questions', function (Blueprint $table) {
			$table->id();
			$table->longText('question_ar')->nullable();
			$table->longText('question_en')->nullable();
			$table->string('Justify', 255)->nullable();
			$table->integer('order')->default(1);
			$table->string('file')->nullable();
			$table->enum('type', ['audio', 'video', 'image', 'text']);
			$table->foreignId("bank_category_id")->constrained("bank_categories")->cascadeOnDelete()->cascadeOnDelete();
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
		Schema::dropIfExists('bank_questions');
	}
};
