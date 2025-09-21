<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TapNomination;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;

class TapNominationSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get some users and daily tasks
        $users = User::where('role', '!=', 'admin')->take(3)->get();
        $dailyTasks = Task::where('task_type', 'daily')->where('status', 'published')->take(3)->get();

        if ($users->count() >= 2 && $dailyTasks->count() >= 1) {
            // Create a sample nomination
            TapNomination::create([
                'FK1_taskId' => $dailyTasks->first()->taskId,
                'FK2_nominatorId' => $users->first()->userId,
                'FK3_nomineeId' => $users->skip(1)->first()->userId,
                'nomination_date' => now()->subHours(2),
                'status' => 'pending'
            ]);

            // Create a completed task assignment for the first user to enable nomination
            if ($dailyTasks->count() >= 2) {
                TaskAssignment::create([
                    'taskId' => $dailyTasks->skip(1)->first()->taskId,
                    'userId' => $users->first()->userId,
                    'status' => 'completed',
                    'assigned_at' => now()->subDays(1),
                    'completed_at' => now()->subHours(5)
                ]);
            }
        }
    }
}
