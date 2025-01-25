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
        Schema::create('airtime_to_cashes', function (Blueprint $table) {
           
            $table->id();
            $table->string('username');
            $table->string('amount');
            $table->string('bill_type');
            $table->string('package_number');
            $table->string('status');
            $table->string('warrant');
            $table->string('instructions');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airtime_to_cashes');
    }
};
