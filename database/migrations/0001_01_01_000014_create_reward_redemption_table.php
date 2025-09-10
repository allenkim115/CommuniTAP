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
        Schema::create('reward_redemption', function (Blueprint $table) {
            $table->id('redemptionId');
            $table->foreignId('FK1_rewardId')->constrained('rewards', 'rewardId')->onDelete('cascade');
            $table->foreignId('FK2_userId')->constrained('users', 'userId')->onDelete('cascade');
            $table->dateTime('redemption_date');
            $table->string('status', 30)->default('pending');
            $table->dateTime('approval_date')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_redemption');
    }
};
