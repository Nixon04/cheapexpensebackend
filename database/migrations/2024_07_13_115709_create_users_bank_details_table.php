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
        Schema::create('users_bank_details', function (Blueprint $table) {
          
            $table->id();
            $table->string('username');
            $table->string('bank_name_id');
            $table->string('bank_user_name');
            $table->string('user_account');
            $table->string('user_bank');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_bank_details');
    }
};
