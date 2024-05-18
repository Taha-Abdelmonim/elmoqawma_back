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
		Schema::create('lecture_views', function (Blueprint $table) {
			$table->id();
			$table->integer('count')->default(1);
			$table->foreignId("user_id")->constrained("users")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("lecture_id")->nullable()->constrained("lectures")->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId("order_id")->nullable()->constrained("orders")->cascadeOnDelete()->cascadeOnUpdate();
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
		Schema::dropIfExists('lecture_views');
	}
};
