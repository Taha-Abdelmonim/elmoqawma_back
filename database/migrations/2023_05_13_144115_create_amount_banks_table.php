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
        Schema::create('amount_banks', function (Blueprint $table) {
            $table->id();
            $table->decimal("amount")->default(0);
            $table->foreignId("bank_id")->constrained("banks")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('amount_banks');
    }
};
