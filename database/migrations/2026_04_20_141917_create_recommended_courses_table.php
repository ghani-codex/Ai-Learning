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
        Schema::create('recommended_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('course_title');
            $table->string('youtube_url');
            $table->string('thumbnail_url')->nullable();
            $table->string('channel_name')->nullable();
            $table->string('skill_target');
            $table->string('duration')->nullable();
            $table->string('level')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('skill_target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommended_courses');
    }
};
