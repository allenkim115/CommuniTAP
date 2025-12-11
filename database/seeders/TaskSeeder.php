<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $locations = ['Pig Vendor', 'Ermita Proper', 'Kastilaan', 'Sitio Bato', 'YHC', 'Eyeseekers', 'Panagdait', 'Kawit',];
        
        $statuses = ['completed', 'uncompleted', 'inactive', 'published'];
        
        // Admin tasks only: daily and one_time
        $taskTypes = ['daily', 'one_time'];
        
        // Realistic community task templates
        $dailyTasks = [
            [
                'title' => 'Community Cleanup Drive',
                'description' => 'Help keep our community clean by participating in daily cleanup activities. Collect trash, sweep streets, and maintain public areas.',
                'points' => 15,
                'start_time' => '06:00:00',
                'end_time' => '08:00:00',
            ],
            [
                'title' => 'Street Sweeping',
                'description' => 'Daily street sweeping to maintain cleanliness in our neighborhood. Bring your own broom and help keep the streets clean.',
                'points' => 10,
                'start_time' => '07:00:00',
                'end_time' => '09:00:00',
            ],
            [
                'title' => 'Garbage Segregation',
                'description' => 'Assist in proper garbage segregation at the collection point. Help educate residents about proper waste management.',
                'points' => 12,
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
            ],
            [
                'title' => 'Park Maintenance',
                'description' => 'Daily maintenance of community parks. Water plants, pick up litter, and ensure the park is welcoming for everyone.',
                'points' => 15,
                'start_time' => '06:30:00',
                'end_time' => '08:30:00',
            ],
            [
                'title' => 'Traffic Assistance',
                'description' => 'Help manage traffic during peak hours near schools and busy intersections. Ensure safe passage for pedestrians.',
                'points' => 20,
                'start_time' => '07:00:00',
                'end_time' => '09:00:00',
            ],
        ];
        
        $oneTimeTasks = [
            [
                'title' => 'Community Feeding Program',
                'description' => 'Join us in organizing a feeding program for children and elderly members of the community. Help prepare and distribute nutritious meals.',
                'points' => 50,
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
            ],
            [
                'title' => 'Donation Drive for Families',
                'description' => 'Organize and collect donations of food, clothing, and essential items for families in need. Help sort and distribute donations.',
                'points' => 45,
                'start_time' => '09:00:00',
                'end_time' => '15:00:00',
            ],
            [
                'title' => 'Medical Mission',
                'description' => 'Assist healthcare professionals in providing free medical check-ups, consultations, and basic health services to community members.',
                'points' => 60,
                'start_time' => '07:00:00',
                'end_time' => '16:00:00',
            ],
            [
                'title' => 'Tree Planting Activity',
                'description' => 'Participate in our community tree planting initiative. Help plant seedlings and contribute to environmental conservation.',
                'points' => 40,
                'start_time' => '06:00:00',
                'end_time' => '10:00:00',
            ],
            [
                'title' => 'Blood Donation Drive',
                'description' => 'Support our blood donation drive by helping with registration, providing refreshments, and encouraging community members to donate blood.',
                'points' => 55,
                'start_time' => '08:00:00',
                'end_time' => '14:00:00',
            ],
            [
                'title' => 'Senior Citizens Day',
                'description' => 'Organize activities and provide assistance for senior citizens. Help with games, meals, and companionship for our elderly community members.',
                'points' => 50,
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
            ],
            [
                'title' => 'Children\'s Reading Program',
                'description' => 'Volunteer to read stories and conduct educational activities for children. Help promote literacy and learning in our community.',
                'points' => 45,
                'start_time' => '10:00:00',
                'end_time' => '14:00:00',
            ],
            [
                'title' => 'Community Market Day',
                'description' => 'Assist in organizing a community market day where local vendors can sell their products. Help with setup, crowd management, and cleanup.',
                'points' => 40,
                'start_time' => '06:00:00',
                'end_time' => '12:00:00',
            ],
            [
                'title' => 'Disaster Preparedness Workshop',
                'description' => 'Help organize and facilitate a disaster preparedness workshop. Assist with setup, registration, and distribution of emergency kits.',
                'points' => 50,
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
            ],
            [
                'title' => 'Community Health Fair',
                'description' => 'Support health fair activities including health screenings, vaccinations, and health education. Help with registration and crowd management.',
                'points' => 55,
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
            ],
        ];
        
        // Create tasks spanning 30 days
        // Admin task flow: created -> approved -> published -> (completed/uncompleted/inactive)
        // Track used templates to avoid exact duplicates
        $usedTemplates = [];
        
        for ($day = 0; $day < 30; $day++) {
            $taskDate = Carbon::now()->subDays($day);
            $creationDate = $taskDate->copy()->subDays(rand(2, 5));
            
            // Mix of daily and one-time tasks
            $taskType = $taskTypes[array_rand($taskTypes)];
            
            // Select template with variety - add location or date to make unique
            if ($taskType === 'daily') {
                $templateIndex = array_rand($dailyTasks);
                $taskTemplate = $dailyTasks[$templateIndex];
            } else {
                $templateIndex = array_rand($oneTimeTasks);
                $taskTemplate = $oneTimeTasks[$templateIndex];
            }
            
            // Add location to title to make it unique and realistic
            $location = $locations[array_rand($locations)];
            $uniqueTitle = $taskTemplate['title'] . ' - ' . $location;
            
            // Determine initial status - some will be inactive (deactivated by admin)
            // Distribution: 70% published (will be updated by TaskAssignmentSeeder), 15% inactive, 15% other
            $statusRand = rand(1, 100);
            if ($statusRand <= 70) {
                // Most tasks start as published - status will be updated by TaskAssignmentSeeder
                $status = 'published';
            } elseif ($statusRand <= 85) {
                // 15% inactive - tasks that were published but then deactivated by admin
                $status = 'inactive';
            } else {
                // 15% published (will be updated later)
                $status = 'published';
            }
            
            // Admin tasks must be approved before publishing
            // Flow: creation_date -> approval_date -> published_date
            $approvalDate = $creationDate->copy()->addDays(rand(0, 2)); // Approved 0-2 days after creation
            $publishedDate = $approvalDate->copy()->addDays(rand(0, 1)); // Published 0-1 days after approval
            
            // Set dates based on status
            $finalApprovalDate = null;
            $finalPublishedDate = null;
            $deactivatedAt = null;
            
            if ($status === 'inactive') {
                // Inactive tasks were published first, then deactivated by admin
                $finalApprovalDate = $approvalDate;
                $finalPublishedDate = $publishedDate;
                // Deactivated 1-7 days after publishing
                $deactivatedAt = $publishedDate->copy()->addDays(rand(1, 7));
            } elseif ($status === 'published') {
                // Published tasks have been approved and published
                $finalApprovalDate = $approvalDate;
                $finalPublishedDate = $publishedDate;
            }
            
            Task::create([
                'FK1_userId' => null, // Admin tasks are not assigned to specific users initially
                'title' => $uniqueTitle,
                'description' => $taskTemplate['description'],
                'task_type' => $taskType,
                'points_awarded' => $taskTemplate['points'],
                'status' => $status,
                'creation_date' => $creationDate,
                'approval_date' => $finalApprovalDate,
                'due_date' => $taskDate,
                'start_time' => $taskTemplate['start_time'],
                'end_time' => $taskTemplate['end_time'],
                'location' => $location,
                'max_participants' => $taskType === 'daily' ? rand(10, 30) : rand(15, 50),
                'published_date' => $finalPublishedDate,
                'deactivated_at' => $deactivatedAt,
                'created_at' => $creationDate,
                'updated_at' => $taskDate,
            ]);
        }
    }
}
