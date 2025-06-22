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
        Schema::create('user_details_for_cards', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->string('username');
            $table->string('address_city');
            $table->string('address_state');
            $table->string('postal_code');
            $table->string('image');
            $table->string('time_created');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details_for_cards');
    }
};
