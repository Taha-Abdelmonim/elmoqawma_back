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
        
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->string("statement");
            $table->integer("bank_id");
            $table->decimal("amount");
            $table->decimal("bank_amount_after");
            $table->integer("type");
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("user_pay_id")->constrained("users")->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("currency_id")->constrained("currencies")->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_transactions');
    }
};
