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
        if (!Schema::hasTable('feedback')) {
            Schema::create('feedback', function (Blueprint $table) {
                $table->id('feedbackId');
                $table->unsignedBigInteger('taskId');
                $table->unsignedBigInteger('userId');
                $table->text('feedback_text');
                $table->integer('rating')->nullable(); // 1-5 star rating
                $table->enum('type', ['user_feedback', 'admin_feedback'])->default('user_feedback');
                $table->boolean('is_public')->default(true);
                $table->timestamps();
                
                // Foreign key constraints
                $table->foreign('taskId')->references('taskId')->on('tasks')->onDelete('cascade');
                $table->foreign('userId')->references('userId')->on('users')->onDelete('cascade');
                
                // Indexes
                $table->index(['taskId', 'userId']);
                $table->index('type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
