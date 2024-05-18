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
		Schema::create('courses', function (Blueprint $table) {
			$table->id();
			$table->string('name', 500)->default("");
			$table->longText("description_ar");
			$table->longText("description_en");
			$table->string('photo', 500);
			$table->string('description_photo', 500)->nullable();
			$table->decimal('price')->default(0);
			$table->decimal('sub_price')->default(0);
			$table->decimal('discount')->default(0);

			$table->string('whatsapp');
			$table->string('telegram');
			$table->string('subscribers', 50);
			$table->integer('subscription_duration')->nullable()->default(0);
			$table->boolean('stars')->default(5);
			$table->boolean('status')->default(1);
			$table->boolean('type');
			$table->string('collectionID');
			$table->foreignId("teacher_id")->nullable()->constrained("teachers")->cascadeOnUpdate()->nullOnDelete();
			$table->foreignId("subject_id")->nullable()->constrained("subjects")->cascadeOnUpdate()->nullOnDelete();
			$table->foreignId("section_id")->nullable()->constrained("sections")->nullOnDelete()->cascadeOnUpdate();
			$table->foreignId("currency_id")->default(1)->constrained("currencies")->cascadeOnDelete()->cascadeOnUpdate();
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
		Schema::dropIfExists('courses');
	}
};
