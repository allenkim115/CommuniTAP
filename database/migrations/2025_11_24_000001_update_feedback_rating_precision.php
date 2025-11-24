<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Upgrade the rating column to support half-star precision.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE feedback MODIFY COLUMN rating DECIMAL(3,1) NOT NULL');
    }

    /**
     * Revert the rating column to its original integer type.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE feedback MODIFY COLUMN rating INT NOT NULL');
    }
};

