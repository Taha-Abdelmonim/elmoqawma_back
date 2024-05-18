<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up()
  {
    Schema::create('supports', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->longText('message');
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
      $table->boolean('done_read')->default(0);
      $table->boolean('done_contact')->default(0);
      $table->boolean('done_problem')->default(0);
      $table->softDeletes();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('supports');
  }
};
