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
        Schema::create('all_data_packages', function (Blueprint $table) {
            $table->id();
            $table->string('plan_id');
            $table->string('title');
            $table->string('network_type');
            $table->string('alias');
            $table->string('amount');
            $table->string('reference');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**a
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('all_data_packages');
    }
};
