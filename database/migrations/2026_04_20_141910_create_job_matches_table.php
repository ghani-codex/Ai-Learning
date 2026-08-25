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
        Schema::create('job_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('job_title');
            $table->string('company_name');
            $table->string('location')->nullable();
            $table->string('job_url');
            $table->string('salary')->nullable();
            $table->string('employment_type')->nullable();
            $table->text('job_description')->nullable();
            $table->unsignedTinyInteger('match_score')->default(0);
            $table->json('skills_matched')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('match_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_matches');
    }
};
