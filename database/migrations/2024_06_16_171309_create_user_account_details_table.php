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
        Schema::create('user_account_details', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('user_ref_id');
            $table->string('user_amount');
            $table->string('user_bonus');
            $table->string('last_update');
            $table->string('withdrawer_count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_account_details');
    }
};
