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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_job_id')->constrained('jobs')->onDelete('cascade');
            $table->foreignId('pull_request_id')->constrained('pull_requests')->onDelete('cascade');
            $table->unsignedSmallInteger('score');
            $table->string('status')->default('pending');
            $table->text('summary');
            $table->string('ai_model');
            $table->unsignedSmallInteger('tokens_used');
            $table->unsignedSmallInteger('review_duration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
