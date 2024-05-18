<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
          $table->id();
          $table->string("name_ar");
          $table->string("name_en");
          $table->decimal('price');
          $table->string('photo', 500);
          $table->string('subscribers', 50);
          $table->boolean('stars')->default(5);
          $table->string('duration');
          $table->longText("description_ar");
          $table->longText("description_en");
          $table->foreignId("currency_id")->constrained("currencies")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('offers');
    }
};
