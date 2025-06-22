<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('virtual_card_lists', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('username');
            $table->string('customer_id');
            $table->string('customer_name');
            $table->string('card_id');
            $table->string('card_type');
            $table->string('currency');
            $table->string('brand');
            $table->string('name');
            $table->string('first_six');
            $table->string('last_six');
            $table->string('masked');
            $table->string('frontnumber');
            $table->string('expiry');
            $table->string('cvv');
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code');
            $table->softDeletes(); //for recovery later 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_card_lists');
    }
};
