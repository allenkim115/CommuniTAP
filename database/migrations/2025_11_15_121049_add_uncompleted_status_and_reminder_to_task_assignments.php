<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('task_assignments', function (Blueprint $table) {
            // Add reminder_sent_at to track if 1-hour notification was sent
            $table->timestamp('reminder_sent_at')->nullable()->after('completed_at');
        });

        // Modify the status enum to include 'uncompleted'
        // Note: MySQL doesn't support modifying enum directly, so we need to use raw SQL
        // Check if the column exists and modify it
        if (Schema::hasColumn('task_assignments', 'status')) {
            DB::statement("ALTER TABLE task_assignments MODIFY COLUMN status ENUM('assigned', 'submitted', 'completed', 'uncompleted') NOT NULL DEFAULT 'assigned'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_assignments', function (Blueprint $table) {
            $table->dropColumn('reminder_sent_at');
        });

        // Revert status enum back to original
        DB::statement("ALTER TABLE task_assignments MODIFY COLUMN status ENUM('assigned', 'submitted', 'completed') NOT NULL DEFAULT 'assigned'");
    }
};
