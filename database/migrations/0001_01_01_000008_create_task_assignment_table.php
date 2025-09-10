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
        Schema::create('task_assignment', function (Blueprint $table) {
            $table->id('assignmentId');
            $table->foreignId('FK1_userId')->constrained('users', 'userId')->onDelete('cascade');
            $table->foreignId('FK2_taskId')->constrained('tasks', 'taskId')->onDelete('cascade');
            $table->dateTime('assignment_date');
            $table->string('status', 30)->default('assigned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignment');
    }
};
