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
        Schema::create('news_feed_models', function (Blueprint $table) {
            $table->id();
            $table->string('news_ref_id'); 
            $table->string('news_title');
            $table->string('news_image');
            $table->string('news_message');  
            $table->string('last_updated');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_feed_models');
    }
};
