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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('feedbackId');
            $table->foreignId('FK1_userId')->constrained('users', 'userId')->onDelete('cascade');
            $table->foreignId('FK2_taskId')->constrained('tasks', 'taskId')->onDelete('cascade');
            $table->integer('rating');
            $table->text('comment');
            $table->dateTime('feedback_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
