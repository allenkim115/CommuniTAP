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
        Schema::create('task_submission', function (Blueprint $table) {
            $table->id('submissionId');
            $table->foreignId('FK1_taskId')->constrained('tasks', 'taskId')->onDelete('cascade');
            $table->foreignId('FK2_userId')->constrained('users', 'userId')->onDelete('cascade');
            $table->text('proof_details');
            $table->dateTime('submission_date');
            $table->string('verification_status', 30)->default('pending');
            $table->dateTime('verification_date')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_submission');
    }
};
