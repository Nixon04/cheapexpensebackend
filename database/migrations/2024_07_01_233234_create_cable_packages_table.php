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
        Schema::create('cable_packages', function (Blueprint $table) {
            $table->id();
            $table->string("packagename");
            $table->string("variation_name");
            $table->string("variation_code");
            $table->string("variation_amount");
            $table->string("fixed_price");
            $table->string("calculated_price");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cable_packages');
    }
};
