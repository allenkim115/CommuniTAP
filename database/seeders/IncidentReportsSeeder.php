<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserIncidentReport;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;

class IncidentReportsSeeder extends Seeder
{
    public function run()
    {
        // Get all regular users from UserSeeder (non-admin, role = 'user')
        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->get();
        
        if ($users->count() < 2) {
            $this->command->warn('Not enough users found. Need at least 2 users to create incident reports. Please run UserSeeder first.');
            return;
        }
        
        // Get admin user for moderation (from AdminUserSeeder)
        $adminUser = User::where('role', 'admin')
            ->where('status', 'active')
            ->first();
        
        if (!$adminUser) {
            $this->command->warn('No admin user found. Please run AdminUserSeeder first.');
        }
        
        // Get tasks from TaskSeeder and UserUploadedTaskSeeder
        // Tasks must be in published, approved, or completed status
        $tasks = Task::whereIn('status', ['published', 'approved', 'completed'])
            ->get();
        
        if ($tasks->isEmpty()) {
            $this->command->warn('No eligible tasks found. Please run TaskSeeder and UserUploadedTaskSeeder first.');
            return;
        }
        
        // Incident types from UserIncidentReport model
        $incidentTypes = [
            'non_participation',
            'abuse',
            'spam',
            'inappropriate_content',
            'harassment',
            'other',
        ];
        
        // Sample descriptions for each incident type
        $incidentDescriptions = [
            'non_participation' => [
                'User did not show up for the assigned task without prior notice.',
                'User accepted the task but failed to complete it by the deadline.',
                'User was assigned but never participated in the task activities.',
                'User abandoned the task midway without informing anyone.',
            ],
            'abuse' => [
                'User displayed aggressive and disrespectful behavior during the task.',
                'User used offensive language and inappropriate remarks.',
                'User showed hostile attitude towards other participants.',
                'User engaged in verbal abuse during task coordination.',
            ],
            'spam' => [
                'User posted irrelevant and repetitive messages in task communications.',
                'User sent multiple unnecessary messages disrupting the workflow.',
                'User posted promotional content unrelated to the task.',
                'User engaged in spamming behavior in task-related discussions.',
            ],
            'inappropriate_content' => [
                'User shared inappropriate content during task-related communications.',
                'User posted content that violates community guidelines.',
                'User shared offensive material in task discussions.',
                'User posted content that is not suitable for the community platform.',
            ],
            'harassment' => [
                'User engaged in persistent unwanted contact and harassment.',
                'User made inappropriate advances towards other participants.',
                'User sent threatening messages to other users.',
                'User engaged in bullying behavior during task activities.',
            ],
            'other' => [
                'User violated task guidelines and community standards.',
                'User engaged in behavior that disrupted task completion.',
                'User failed to follow instructions and caused issues.',
                'User displayed unprofessional conduct during the task.',
            ],
        ];
        
        // Sample evidence texts
        $evidenceTexts = [
            'Screenshots of the conversation are available.',
            'Multiple witnesses can confirm the incident.',
            'Evidence has been documented and saved.',
            'The incident was reported by multiple participants.',
            'Clear evidence of the violation exists.',
            null, // Some reports may not have evidence
        ];
        
        // Create incident reports spanning the last 30 days
        $reportsCreated = 0;
        $maxReports = 40; // Create up to 40 reports
        
        for ($day = 0; $day < 30 && $reportsCreated < $maxReports; $day++) {
            $reportDate = Carbon::now()->subDays($day);
            
            // Create 1-2 reports per day (realistic distribution)
            $reportsPerDay = rand(0, 2);
            
            for ($i = 0; $i < $reportsPerDay && $reportsCreated < $maxReports; $i++) {
                // Select random reporter (from UserSeeder)
                $reporter = $users->random();
                
                // Select random reported user (different from reporter, from UserSeeder)
                $reportedUser = $users->where('userId', '!=', $reporter->userId)->random();
                
                // Select random task (from TaskSeeder or UserUploadedTaskSeeder)
                $task = $tasks->random();
                
                // Select random incident type
                $incidentType = $incidentTypes[array_rand($incidentTypes)];
                
                // Check if reporter has already reported this user for the same incident type within 7 days
                $existingReport = UserIncidentReport::where('FK1_reporterId', $reporter->userId)
                    ->where('FK2_reportedUserId', $reportedUser->userId)
                    ->where('incident_type', $incidentType)
                    ->where('created_at', '>=', $reportDate->copy()->subDays(7))
                    ->first();
                
                if ($existingReport) {
                    continue; // Skip to avoid duplicate reports
                }
                
                // Select random description for this incident type
                $descriptions = $incidentDescriptions[$incidentType] ?? $incidentDescriptions['other'];
                $description = $descriptions[array_rand($descriptions)];
                
                // Select random evidence (or null)
                $evidence = $evidenceTexts[array_rand($evidenceTexts)];
                
                // Determine status and moderation details
                // Distribution: 30% pending, 20% under_review, 35% resolved, 15% dismissed
                $statusRand = rand(1, 100);
                $status = 'pending';
                $moderatorId = null;
                $moderatorNotes = null;
                $actionTaken = null;
                $moderationDate = null;
                
                if ($statusRand <= 30) {
                    // Pending - not yet reviewed
                    $status = 'pending';
                } elseif ($statusRand <= 50) {
                    // Under review - admin is reviewing
                    $status = 'under_review';
                    if ($adminUser) {
                        $moderatorId = $adminUser->userId;
                        $moderationDate = $reportDate->copy()->addDays(rand(1, 3));
                    }
                } elseif ($statusRand <= 85) {
                    // Resolved - admin has taken action
                    $status = 'resolved';
                    if ($adminUser) {
                        $moderatorId = $adminUser->userId;
                        $moderationDate = $reportDate->copy()->addDays(rand(2, 5));
                        
                        // Determine action taken
                        $actionRand = rand(1, 100);
                        if ($actionRand <= 40) {
                            $actionTaken = 'warning';
                            $moderatorNotes = 'Warning issued. User has been notified and points have been deducted.';
                            
                            // Deduct 10 points from reported user (following controller logic)
                            $reportedUser->refresh();
                            $reportedUser->points = max(0, $reportedUser->points - 10);
                            $reportedUser->save();
                        } elseif ($actionRand <= 60) {
                            $actionTaken = 'suspension';
                            $moderatorNotes = 'User account has been suspended due to violation of community guidelines.';
                            
                            // Suspend the reported user (following controller logic)
                            $reportedUser->refresh();
                            $reportedUser->status = 'suspended';
                            $reportedUser->save();
                        } else {
                            $actionTaken = 'no_action';
                            $moderatorNotes = 'Report reviewed. No further action required at this time.';
                        }
                    }
                } else {
                    // Dismissed - report was dismissed
                    $status = 'dismissed';
                    if ($adminUser) {
                        $moderatorId = $adminUser->userId;
                        $moderationDate = $reportDate->copy()->addDays(rand(2, 5));
                        $moderatorNotes = 'Report dismissed after review. Insufficient evidence or no violation found.';
                        $actionTaken = 'no_action';
                    }
                }
                
                // Create the incident report
                $report = UserIncidentReport::create([
                    'FK1_reporterId' => $reporter->userId,
                    'FK2_reportedUserId' => $reportedUser->userId,
                    'FK3_taskId' => $task->taskId,
                    'incident_type' => $incidentType,
                    'description' => $description,
                    'evidence' => $evidence,
                    'status' => $status,
                    'FK4_moderatorId' => $moderatorId,
                    'moderator_notes' => $moderatorNotes,
                    'action_taken' => $actionTaken,
                    'moderation_date' => $moderationDate,
                    'report_date' => $reportDate->copy()->addHours(rand(8, 20)), // Random time during the day
                    'created_at' => $reportDate,
                    'updated_at' => $moderationDate ?? $reportDate,
                ]);
                
                $reportsCreated++;
            }
        }
        
        $this->command->info("Created {$reportsCreated} incident reports.");
    }
}

