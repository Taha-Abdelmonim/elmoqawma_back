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
		Schema::create('settings', function (Blueprint $table) {
			$table->id();
			$table->string("name");
			$table->string("key");
			$table->boolean("key_status")->default(1);
			$table->string('photo', 500)->nullable();
			$table->enum("type", ["text", "html", "photo"]);
			$table->longText("value_ar")->nullable();
			$table->longText("value_en")->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/** social_design
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('settings');
	}
};
