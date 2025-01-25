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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('amount');
            $table->string('type_of_purchase');
            $table->string('sub_type_purchase');
            $table->string('data_type');
            $table->string('status');
            $table->string('ref_num_purchase');
            $table->string('reference');
            $table->string('date_of_purchase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
