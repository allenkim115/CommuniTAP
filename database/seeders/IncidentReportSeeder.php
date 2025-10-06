<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserIncidentReport;
use App\Models\User;
use App\Models\Task;

class IncidentReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and tasks for sample data
        $users = User::where('role', 'user')->take(5)->get();
        $tasks = Task::take(3)->get();

        if ($users->count() < 2 || $tasks->count() < 1) {
            $this->command->info('Not enough users or tasks to create sample incident reports.');
            return;
        }

        $incidentTypes = [
            'non_participation',
            'abuse',
            'spam',
            'inappropriate_content',
            'harassment',
            'other'
        ];

        $statuses = ['pending', 'under_review', 'resolved', 'dismissed'];
        $actionsTaken = ['warning', 'suspension', 'no_action', 'dismissed'];

        // Create sample incident reports
        for ($i = 0; $i < 10; $i++) {
            $reporter = $users->random();
            $reportedUser = $users->where('userId', '!=', $reporter->userId)->random();
            $task = $tasks->random();
            $incidentType = $incidentTypes[array_rand($incidentTypes)];
            $status = $statuses[array_rand($statuses)];

            $report = UserIncidentReport::create([
                'FK1_reporterId' => $reporter->userId,
                'FK2_reportedUserId' => $reportedUser->userId,
                'FK3_taskId' => $task->taskId,
                'incident_type' => $incidentType,
                'description' => $this->getSampleDescription($incidentType),
                'evidence' => $this->getSampleEvidence($incidentType),
                'status' => $status,
                'report_date' => now()->subDays(rand(1, 30)),
            ]);

            // If status is not pending, add moderation details
            if ($status !== 'pending') {
                $admin = User::where('role', 'admin')->first();
                $actionTaken = $actionsTaken[array_rand($actionsTaken)];

                $report->update([
                    'FK4_moderatorId' => $admin?->userId,
                    'moderator_notes' => $this->getSampleModeratorNotes($status, $actionTaken),
                    'action_taken' => $actionTaken,
                    'moderation_date' => $report->report_date->addDays(rand(1, 7)),
                ]);
            }
        }

        $this->command->info('Created 10 sample incident reports.');
    }

    private function getSampleDescription($incidentType): string
    {
        $descriptions = [
            'non_participation' => 'This user joined the task but has not been participating or contributing as expected. They have been inactive for several days despite the task deadline approaching.',
            'abuse' => 'This user has been using inappropriate language and making personal attacks against other participants in the task discussion.',
            'spam' => 'This user has been posting irrelevant content and repeatedly sharing promotional links in the task comments.',
            'inappropriate_content' => 'This user has been sharing content that is not appropriate for this platform, including offensive images and inappropriate comments.',
            'harassment' => 'This user has been persistently targeting and harassing other users, sending unwanted messages and making them feel uncomfortable.',
            'other' => 'This user has been exhibiting behavior that violates the community guidelines and needs to be addressed by the moderation team.'
        ];

        return $descriptions[$incidentType] ?? $descriptions['other'];
    }

    private function getSampleEvidence($incidentType): string
    {
        $evidence = [
            'non_participation' => 'Last activity was 5 days ago. No responses to task updates or team communications.',
            'abuse' => 'Screenshots of inappropriate messages and personal attacks in the task discussion thread.',
            'spam' => 'Multiple promotional links shared in comments. Content is not related to the task objectives.',
            'inappropriate_content' => 'Inappropriate images and comments that violate community standards.',
            'harassment' => 'Evidence of persistent unwanted contact and threatening messages.',
            'other' => 'Documentation of various policy violations and inappropriate behavior patterns.'
        ];

        return $evidence[$incidentType] ?? $evidence['other'];
    }

    private function getSampleModeratorNotes($status, $actionTaken): string
    {
        $notes = [
            'under_review' => 'Report is currently under investigation. Gathering additional information from all parties involved.',
            'resolved' => match($actionTaken) {
                'warning' => 'Warning issued to the reported user. They have been informed about the violation and consequences of continued inappropriate behavior.',
                'suspension' => 'User has been suspended due to serious violations of community guidelines. Suspension will be reviewed after 7 days.',
                'no_action' => 'After investigation, no violation was found. The reported behavior was within acceptable community standards.',
                'dismissed' => 'Report was found to be unfounded or malicious. No action taken against the reported user.',
                default => 'Report has been resolved with appropriate action taken.'
            },
            'dismissed' => 'Report was found to be unfounded or did not violate community guidelines. No action required.'
        ];

        return $notes[$status] ?? 'Moderation notes added.';
    }
}
