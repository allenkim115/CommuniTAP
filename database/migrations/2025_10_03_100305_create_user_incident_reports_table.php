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
        Schema::create('user_incident_reports', function (Blueprint $table) {
            $table->id('reportId');
            $table->foreignId('FK1_reporterId')->constrained('users', 'userId')->onDelete('cascade');
            $table->foreignId('FK2_reportedUserId')->constrained('users', 'userId')->onDelete('cascade');
            $table->foreignId('FK3_taskId')->nullable()->constrained('tasks', 'taskId')->onDelete('set null');
            $table->string('incident_type', 50);
            $table->text('description');
            $table->text('evidence')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('FK4_moderatorId')->nullable()->constrained('users', 'userId')->onDelete('set null');
            $table->text('moderator_notes')->nullable();
            $table->string('action_taken', 50)->nullable();
            $table->dateTime('moderation_date')->nullable();
            $table->dateTime('report_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_incident_reports');
    }
};
