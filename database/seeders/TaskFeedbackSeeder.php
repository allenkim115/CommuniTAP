<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\TaskAssignment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TaskFeedbackSeeder extends Seeder
{
    public function run(): void
    {
        // Use completed assignments so feedback aligns with finished work
        $completedAssignments = TaskAssignment::with(['task', 'user'])
            ->where('status', 'completed')
            ->get();

        if ($completedAssignments->isEmpty()) {
            $this->command->warn('No completed assignments found. Run TaskAssignmentSeeder first.');
            return;
        }

        $comments = [
            'Great task, learned a lot while helping out.',
            'Well organized and instructions were clear.',
            'Challenging but rewarding experience.',
            'Had fun collaborating with the team.',
            'Would love to volunteer again for similar tasks.',
            'Some parts were tough but overall manageable.',
            'Resources were provided and made the job easier.',
            'Communication with coordinators was smooth.',
            'Task goals were clear and achievable.',
            'Happy to see the community impact.',
        ];

        // Create feedback for a realistic subset of completed assignments
        $sampledAssignments = $completedAssignments->shuffle()->take(min(40, $completedAssignments->count()));

        foreach ($sampledAssignments as $assignment) {
            // Avoid duplicate feedback for the same user-task pair
            $existing = Feedback::where('FK1_userId', $assignment->userId)
                ->where('FK2_taskId', $assignment->taskId)
                ->first();

            if ($existing) {
                continue;
            }

            $completedAt = $assignment->completed_at ?: Carbon::now()->subDays(rand(1, 5));

            Feedback::create([
                'FK1_userId' => $assignment->userId,
                'FK2_taskId' => $assignment->taskId,
                'rating' => rand(35, 50) / 10, // 3.5 - 5.0
                'comment' => $comments[array_rand($comments)],
                'feedback_date' => $completedAt,
            ]);
        }
    }
}


