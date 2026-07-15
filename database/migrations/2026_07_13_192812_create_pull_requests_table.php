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
        Schema::create('pull_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('repository_id')->constrained('repositories')->onDelete('cascade');
            $table->string('github_pr_number');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('author');
            $table->string('branch');
            $table->string('base_branch');
            $table->string('head_commit');
            $table->string('status');
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('merged_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pull_requests');
    }
};
