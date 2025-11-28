<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration:
     * - Drops user_roles table (junction table)
     * - Drops roles table
     * - Converts users.role from VARCHAR to ENUM('user', 'admin')
     */
    public function up(): void
    {
        // Drop user_roles table first (has foreign key to roles)
        Schema::dropIfExists('user_roles');

        // Drop roles table
        Schema::dropIfExists('roles');

        // Convert users.role from VARCHAR to ENUM
        // First, ensure all existing values are either 'user' or 'admin'
        DB::statement("UPDATE users SET role = 'user' WHERE role NOT IN ('user', 'admin')");

        // Change column type to ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'admin') NOT NULL DEFAULT 'user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert users.role back to VARCHAR
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(30) NOT NULL DEFAULT 'user'");

        // Recreate roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id('roleId');
            $table->string('roleName', 30);
            $table->timestamps();
        });

        // Recreate user_roles table
        Schema::create('user_roles', function (Blueprint $table) {
            $table->foreignId('FK1_userId')->constrained('users', 'userId')->onDelete('cascade');
            $table->foreignId('FK2_roleId')->constrained('roles', 'roleId')->onDelete('cascade');
            $table->primary(['FK1_userId', 'FK2_roleId']);
            $table->timestamps();
        });
    }
};
