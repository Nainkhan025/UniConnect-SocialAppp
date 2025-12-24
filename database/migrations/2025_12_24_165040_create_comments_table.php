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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // User who made the comment
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Post that is commented on
            $table->foreignId('post_id')
                  ->constrained('post_models')
                  ->cascadeOnDelete();

            // The actual comment text - THIS IS WHAT'S MISSING!
            $table->text('content');

            $table->timestamps();

            // Optional: Add indexes for better performance
            $table->index('post_id');
            $table->index('user_id');
            $table->index(['post_id', 'created_at']); // For fetching latest comments per post
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
