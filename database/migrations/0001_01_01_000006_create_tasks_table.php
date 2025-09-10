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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('taskId');
            $table->foreignId('FK1_userId')->constrained('users', 'userId')->onDelete('cascade');
            $table->string('title', 100);
            $table->text('description');
            $table->string('task_type', 30);
            $table->integer('points_awarded');
            $table->string('status', 30)->default('pending');
            $table->dateTime('creation_date');
            $table->dateTime('approval_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('published_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
