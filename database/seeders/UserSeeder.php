<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['firstName' => 'Alden', 'middleName' => 'Reyes', 'lastName' => 'Santos', 'email' => 'aldensantos@gmail.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-12-11', 'email_verified_at' => '2025-12-11 09:12:00'],
            ['firstName' => 'Maria', 'middleName' => 'Lopez', 'lastName' => 'Cruz', 'email' => 'maria.cruz@yahoo.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-12-07', 'email_verified_at' => '2025-12-07 14:22:11'],
            ['firstName' => 'Joshua', 'middleName' => 'Mendoza', 'lastName' => 'Villanueva', 'email' => 'josh.villanueva@outlook.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-12-03', 'email_verified_at' => '2025-12-03 10:51:03'],
            ['firstName' => 'Lorena', 'middleName' => NULL, 'lastName' => 'Delos Santos', 'email' => 'lorenads@gmail.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-11-29', 'email_verified_at' => '2025-11-29 16:09:18'],
            ['firstName' => 'Carlo', 'middleName' => NULL, 'lastName' => 'Guzman', 'email' => 'carlo.guzman@outlook.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-11-26', 'email_verified_at' => '2025-11-26 13:45:59'],
            ['firstName' => 'Elaine', 'middleName' => 'Soriano', 'lastName' => 'Perez', 'email' => 'elaineperez@yahoo.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-11-23', 'email_verified_at' => '2025-11-23 08:31:44'],
            ['firstName' => 'Ramon', 'middleName' => 'Velasco', 'lastName' => 'Torres', 'email' => 'ramon.torres@gmail.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-11-20', 'email_verified_at' => '2025-11-20 11:10:25'],
            ['firstName' => 'Jenny', 'middleName' => NULL, 'lastName' => 'Castillo', 'email' => 'jenny.castillo@yahoo.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-11-17', 'email_verified_at' => '2025-11-17 15:17:52'],
            ['firstName' => 'Marco', 'middleName' => NULL, 'lastName' => 'Ramos', 'email' => 'marco_ramos@gmail.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-11-14', 'email_verified_at' => '2025-11-14 17:05:33'],
            ['firstName' => 'Nina', 'middleName' => 'Flores', 'lastName' => 'Agustin', 'email' => 'nina.agustin@outlook.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-11-11', 'email_verified_at' => '2025-11-11 08:55:14'],
            ['firstName' => 'Patrick', 'middleName' => NULL, 'lastName' => 'Garcia', 'email' => 'patrickgarcia@gmail.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-11-07', 'email_verified_at' => '2025-11-07 12:23:11'],
            ['firstName' => 'Elaiza', 'middleName' => 'Calaclan', 'lastName' => 'Mondragon', 'email' => 'elaiza.mondragon@yahoo.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-11-04', 'email_verified_at' => '2025-11-04 09:11:22'],
            ['firstName' => 'Kevin', 'middleName' => 'Luis', 'lastName' => 'Rosales', 'email' => 'kevin.rosales@outlook.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-10-30', 'email_verified_at' => '2025-10-30 15:48:55'],
            ['firstName' => 'Angelica', 'middleName' => 'Mae', 'lastName' => 'Valencia', 'email' => 'angelica.valencia@gmail.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-10-26', 'email_verified_at' => '2025-10-26 11:15:09'],
            ['firstName' => 'Bryan', 'middleName' => 'Cruz', 'lastName' => 'Sarmiento', 'email' => 'bryan.sarmiento@yahoo.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-10-23', 'email_verified_at' => '2025-10-23 16:20:18'],
            ['firstName' => 'Lara', 'middleName' => '', 'lastName' => 'Vergara', 'email' => 'lara.vergara@gmail.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-10-19', 'email_verified_at' => '2025-10-19 14:32:29'],
            ['firstName' => 'Jonathan', 'middleName' => 'Reyes', 'lastName' => 'Baltazar', 'email' => 'jonathan.baltazar@outlook.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-10-15', 'email_verified_at' => '2025-10-15 10:10:47'],
            ['firstName' => 'Selena', 'middleName' => 'Mae', 'lastName' => 'Estrada', 'email' => 'selena.estrada@yahoo.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-10-11', 'email_verified_at' => '2025-10-11 09:41:33'],
            ['firstName' => 'David', 'middleName' => 'Catacutan', 'lastName' => 'Reyes', 'email' => 'davidreyes@gmail.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-10-06', 'email_verified_at' => '2025-10-06 12:51:58'],
            ['firstName' => 'Mika', 'middleName' => '', 'lastName' => 'Soriano', 'email' => 'mika.soriano@outlook.com', 'role' => 'user', 'status' => 'active', 'date_registered' => '2025-09-29', 'email_verified_at' => '2025-09-29 16:27:44'],
        ];

        // Natural signup pattern: some days have multiple signups, others have none
        // Pattern: [signups per day] - creates realistic clustering with gaps (sums to ~19 users)
        $signupPattern = [
            0, 0, 1, 2, 0, 1, 3, 0, 1, 0, 2, 1, 0, 1, 2, 0, 0, 1, 2, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0
        ];
        
        $userIndex = 0;
        $totalUsers = count($users);
        
        // Start from 29 days ago (last 30 days inclusive)
        $currentDate = Carbon::today()->subDays(29);
        
        foreach ($signupPattern as $signupsToday) {
            // Assign users to this day
            for ($i = 0; $i < $signupsToday && $userIndex < $totalUsers; $i++) {
                $createdAt = $currentDate->copy()->startOfDay();
                
                User::create(array_merge(
                    $users[$userIndex],
                    [
                        'password' => 'CommuniTAP123', // Plain password; will be auto-hashed
                        'points' => 0, // Points start at 0 - will be earned from completed tasks
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                        'date_registered' => $createdAt->toDateString(),
                        'profile_picture' => null,
                        'remember_token' => null,
                    ]
                ));
                
                $userIndex++;
            }
            
            // Move to next day
            $currentDate->addDay();
            
            // Stop if we've assigned all users
            if ($userIndex >= $totalUsers) {
                break;
            }
        }
        
        // If there are remaining users, assign them to today
        while ($userIndex < $totalUsers) {
            $createdAt = Carbon::today()->startOfDay();
            
            User::create(array_merge(
                $users[$userIndex],
                [
                    'password' => 'CommuniTAP123',
                    'points' => 0, // Points start at 0 - will be earned from completed tasks
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                    'date_registered' => $createdAt->toDateString(),
                    'profile_picture' => null,
                    'remember_token' => null,
                ]
            ));
            
            $userIndex++;
        }
    }
}
