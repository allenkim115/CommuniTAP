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
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['FK1_userId']);
            
            // Modify the column to be nullable
            $table->unsignedBigInteger('FK1_userId')->nullable()->change();
            
            // Re-add the foreign key constraint
            $table->foreign('FK1_userId')->references('userId')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['FK1_userId']);
            
            // Make the column not nullable again
            $table->unsignedBigInteger('FK1_userId')->nullable(false)->change();
            
            // Re-add the foreign key constraint
            $table->foreign('FK1_userId')->references('userId')->on('users')->onDelete('cascade');
        });
    }
};
