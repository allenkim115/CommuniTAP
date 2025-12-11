<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class TruncateTablesSeeder extends Seeder
{
    /**
     * Truncate all tables except preserve admin user
     */
    public function run(): void
    {
        // Get admin user before truncating
        $adminUser = User::where('role', 'admin')->first();
        
        if (!$adminUser) {
            $this->command->warn('No admin user found. Skipping truncation to preserve data.');
            return;
        }
        
        $adminData = [
            'userId' => $adminUser->userId,
            'firstName' => $adminUser->firstName,
            'middleName' => $adminUser->middleName,
            'lastName' => $adminUser->lastName,
            'email' => $adminUser->email,
            'password' => $adminUser->password,
            'role' => $adminUser->role,
            'status' => $adminUser->status,
            'points' => $adminUser->points,
            'date_registered' => $adminUser->date_registered,
            'email_verified_at' => $adminUser->email_verified_at,
            'profile_picture' => $adminUser->profile_picture,
            'remember_token' => $adminUser->remember_token,
            'created_at' => $adminUser->created_at,
            'updated_at' => $adminUser->updated_at,
        ];
        
        $this->command->info('Preserving admin user: ' . $adminUser->email);
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        try {
            // Get all table names
            $tables = DB::select('SHOW TABLES');
            $databaseName = DB::getDatabaseName();
            $tableKey = 'Tables_in_' . $databaseName;
            
            // List of tables to truncate (excluding migrations table)
            $tablesToTruncate = [];
            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                // Skip migrations table
                if ($tableName !== 'migrations') {
                    $tablesToTruncate[] = $tableName;
                }
            }
            
            // Truncate all tables
            foreach ($tablesToTruncate as $table) {
                try {
                    DB::table($table)->truncate();
                    $this->command->info("Truncated table: {$table}");
                } catch (\Exception $e) {
                    $this->command->warn("Could not truncate table {$table}: " . $e->getMessage());
                }
            }
            
            // Re-create admin user
            User::create($adminData);
            $this->command->info('Admin user restored successfully.');
            
        } catch (\Exception $e) {
            $this->command->error('Error during truncation: ' . $e->getMessage());
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
        
        $this->command->info('All tables truncated. Admin user preserved.');
    }
}

