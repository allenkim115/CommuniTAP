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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id('rewardId');
            $table->string('sponsor_name', 50);
            $table->string('reward_name', 100);
            $table->text('description');
            $table->integer('points_cost');
            $table->integer('QTY');
            $table->string('status', 30)->default('active');
            $table->dateTime('created_date');
            $table->dateTime('last_update_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
