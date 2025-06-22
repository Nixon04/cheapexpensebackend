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
        Schema::create('eligible_for_cards', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('bvn');
            $table->string('nin');
            $table->string('revoke');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eligible_for_cards');
    }
};
