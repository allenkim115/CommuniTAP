<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration removes unused tables and columns to clean up the database schema:
     * - Drops task_assignment table (replaced by task_assignments)
     * - Drops reports table (replaced by user_incident_reports)
     * - Removes image_url column from rewards table (keeping only image_path)
     */
    public function up(): void
    {
        // Drop unused task_assignment table (replaced by task_assignments)
        Schema::dropIfExists('task_assignment');

        // Drop unused reports table (replaced by user_incident_reports)
        Schema::dropIfExists('reports');

        // Remove redundant image_url column from rewards (keeping image_path)
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add image_url column to rewards
        Schema::table('rewards', function (Blueprint $table) {
            $table->string('image_url', 255)->nullable()->after('reward_name');
        });

        // Recreate reports table
        Schema::create('reports', function (Blueprint $table) {
            $table->id('reportId');
            $table->foreignId('FK1_userId')->constrained('users', 'userId')->onDelete('cascade');
            $table->string('report_type', 30);
            $table->text('description');
            $table->dateTime('report_date');
            $table->string('status', 30)->default('pending');
            $table->dateTime('moderation_date')->nullable();
            $table->text('action_taken')->nullable();
            $table->timestamps();
        });

        // Recreate task_assignment table
        Schema::create('task_assignment', function (Blueprint $table) {
            $table->id('assignmentId');
            $table->foreignId('FK1_userId')->constrained('users', 'userId')->onDelete('cascade');
            $table->foreignId('FK2_taskId')->constrained('tasks', 'taskId')->onDelete('cascade');
            $table->dateTime('assignment_date');
            $table->string('status', 30)->default('assigned');
            $table->timestamps();
        });
    }
};
