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
		Schema::create('coupons', function (Blueprint $table) {
			$table->id();
			$table->string('code');
			$table->decimal("discount")->default(0);
			$table->foreignId("course_id")->nullable()->constrained("courses")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("lecture_id")->nullable()->constrained("lectures")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("offer_id")->nullable()->constrained("offers")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("user_id")->nullable()->constrained("users")->cascadeOnDelete()->cascadeOnUpdate();
			$table->enum("type", ["course", "lecture", "offer", "wallet"]);
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
		Schema::dropIfExists('coupons');
	}
};
