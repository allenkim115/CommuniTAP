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
        // Check if table exists first
        if (!Schema::hasTable('tap_nominations')) {
            Schema::create('tap_nominations', function (Blueprint $table) {
                $table->id('nominationId');
                $table->foreignId('FK1_taskId')->constrained('tasks', 'taskId')->onDelete('cascade');
                $table->foreignId('FK2_nominatorId')->constrained('users', 'userId')->onDelete('cascade');
                $table->foreignId('FK3_nomineeId')->constrained('users', 'userId')->onDelete('cascade');
                $table->dateTime('nomination_date');
                $table->string('status', 30)->default('pending');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tap_nominations');
    }
};
