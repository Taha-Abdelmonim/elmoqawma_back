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
		Schema::create('orders', function (Blueprint $table) {
			$table->id();
			$table->foreignId("user_id")->constrained("users")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("course_id")->nullable()->constrained("courses")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("lecture_id")->nullable()->constrained("lectures")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("offer_id")->nullable()->constrained("offers")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("currency_id")->constrained("currencies")->cascadeOnDelete()->cascadeOnUpdate();
			$table->enum("type", ["course", "lecture", "offer"]);
			$table->decimal("price");
			$table->string("code")->nullable();
			$table->string("bank_code")->nullable();
			$table->string("card_number")->nullable();
			$table->enum("card_type", ["wallet", "code", "cash", "visa"]);
			$table->boolean("status");
			$table->string('photo', 250)->nullable();
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
		Schema::dropIfExists('orders');
	}
};
