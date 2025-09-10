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
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id('assignmentId');
            $table->foreignId('taskId')->constrained('tasks', 'taskId')->onDelete('cascade');
            $table->foreignId('userId')->constrained('users', 'userId')->onDelete('cascade');
            $table->enum('status', ['assigned', 'submitted', 'completed'])->default('assigned');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Ensure a user can only be assigned to a user-uploaded task once
            $table->unique(['taskId', 'userId']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};
