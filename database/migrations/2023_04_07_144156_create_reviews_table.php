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
    Schema::create('reviews', function (Blueprint $table) {
      $table->id();
      $table->longText('comment');
      $table->boolean('status')->default(0);
      $table
        ->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table
        ->foreignId('section_id')
        ->constrained('sections')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
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
    Schema::dropIfExists('reviews');
  }
};
