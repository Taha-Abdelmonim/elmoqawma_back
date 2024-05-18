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
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->string('name_ar');
			$table->string('name_en');
			$table->string('email')->unique();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->text('roles_name')->nullable();
			$table->boolean('status')->default(0);
			$table->string('phone', 30);
			$table->string('phone_parent', 30);
			$table->decimal('balance')->default(0);
			$table->string('photo', 2048)->nullable();
			$table->string('oauth_id')->nullable();
			$table->string('oauth_type')->nullable();
			$table->date('birth')->nullable();
			$table->text('access_token')->nullable();
			$table->foreignId("teacher_id")->nullable()->constrained("teachers")->cascadeOnUpdate()->nullOnDelete();
			$table->rememberToken();
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
		Schema::dropIfExists('users');
	}
};
