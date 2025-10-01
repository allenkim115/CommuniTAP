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
        Schema::table('task_assignments', function (Blueprint $table) {
            // accepted, on_the_way, working, done, submitted_proof
            $table->enum('progress', [
                'accepted',
                'on_the_way',
                'working',
                'done',
                'submitted_proof',
            ])->default('accepted')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_assignments', function (Blueprint $table) {
            $table->dropColumn('progress');
        });
    }
};


