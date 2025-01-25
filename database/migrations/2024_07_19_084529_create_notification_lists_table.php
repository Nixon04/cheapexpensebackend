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
        Schema::create('notification_lists', function (Blueprint $table) {
            $table->id();
            $table->string('notification_id');   
            $table->string('notification_title'); 
            $table->string('notification_message');
            $table->string('last_updated');
            $table->string('status_read');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_lists');
    }
};
