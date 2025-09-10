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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
