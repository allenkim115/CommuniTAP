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
        Schema::create('points_history', function (Blueprint $table) {
            $table->id('historyId');
            $table->foreignId('FK1_userId')->constrained('users', 'userId')->onDelete('cascade');
            $table->unsignedBigInteger('FK2_submissionId')->nullable();
            $table->integer('points_amount');
            $table->string('transaction_type', 50);
            $table->dateTime('transaction_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points_history');
    }
};
