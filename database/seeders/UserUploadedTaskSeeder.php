<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class UserUploadedTaskSeeder extends Seeder
{
    public function run()
    {
        // Get all users with role 'user' (creators)
        $users = User::where('role', 'user')->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }
        
        $locations = ['Pig Vendor', 'Ermita Proper', 'Kastilaan', 'Sitio Bato', 'YHC', 'Eyeseekers', 'Panagdait', 'Kawit'];
        
        // Personal user-uploaded task templates - individual tasks people need help with
        $userTaskTemplates = [
            [
                'title' => 'Help Moving Furniture',
                'description' => 'I need help moving some furniture to my new apartment. Need 2-3 people to help carry heavy items. Will provide refreshments.',
                'points' => 25,
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
            ],
            [
                'title' => 'Pet Sitting While Away',
                'description' => 'Going out of town for the weekend. Need someone to feed and walk my dog twice a day. Dog is friendly and well-behaved.',
                'points' => 30,
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
            ],
            [
                'title' => 'Help with Grocery Shopping',
                'description' => 'I\'m recovering from surgery and need help with grocery shopping. Someone to accompany me or do the shopping for me.',
                'points' => 20,
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
            ],
            [
                'title' => 'Yard Work and Gardening',
                'description' => 'Need help cleaning up my backyard and planting some flowers. I have all the tools, just need an extra pair of hands.',
                'points' => 25,
                'start_time' => '07:00:00',
                'end_time' => '10:00:00',
            ],
            [
                'title' => 'Tutoring for Math Homework',
                'description' => 'My child needs help with math homework. Looking for someone patient and good with kids to help explain concepts.',
                'points' => 30,
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
            ],
            [
                'title' => 'Help Organizing Garage',
                'description' => 'My garage is a mess and I need help organizing it. Need someone to help sort, clean, and organize items.',
                'points' => 20,
                'start_time' => '08:00:00',
                'end_time' => '11:00:00',
            ],
            [
                'title' => 'Cooking for Family Gathering',
                'description' => 'Hosting a family gathering and need help preparing food. Looking for someone who can help cook and set up.',
                'points' => 35,
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
            ],
            [
                'title' => 'Help with Computer Setup',
                'description' => 'Just bought a new computer and need help setting it up, installing software, and transferring files from old computer.',
                'points' => 25,
                'start_time' => '13:00:00',
                'end_time' => '16:00:00',
            ],
            [
                'title' => 'Babysitting for Date Night',
                'description' => 'Need a responsible person to watch my 5-year-old for a few hours while I go on a date. Child is well-behaved.',
                'points' => 30,
                'start_time' => '18:00:00',
                'end_time' => '21:00:00',
            ],
            [
                'title' => 'Help Painting Room',
                'description' => 'Want to repaint my bedroom. Need help with prep work, painting, and cleanup. I\'ll provide all materials.',
                'points' => 30,
                'start_time' => '09:00:00',
                'end_time' => '14:00:00',
            ],
            [
                'title' => 'Assistance with Laundry',
                'description' => 'I have a large pile of laundry and need help washing, drying, and folding. My washing machine is working fine.',
                'points' => 15,
                'start_time' => '10:00:00',
                'end_time' => '13:00:00',
            ],
            [
                'title' => 'Help Cleaning House',
                'description' => 'Need help deep cleaning my house - vacuuming, mopping, dusting, and organizing. Will provide cleaning supplies.',
                'points' => 25,
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
            ],
            [
                'title' => 'Ride to Doctor Appointment',
                'description' => 'I don\'t have a car and need a ride to my doctor\'s appointment. Will pay for gas and parking.',
                'points' => 20,
                'start_time' => '10:00:00',
                'end_time' => '12:00:00',
            ],
            [
                'title' => 'Help Assembling Furniture',
                'description' => 'Bought new furniture from IKEA and need help assembling it. I have the tools, just need someone to help read instructions and assemble.',
                'points' => 25,
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
            ],
            [
                'title' => 'Help with Car Maintenance',
                'description' => 'Need help changing oil and checking tire pressure. I have the supplies, just need someone who knows what they\'re doing.',
                'points' => 20,
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
            ],
        ];
        
        // Get sample images for submissions
        $samplePicsPath = public_path('images/sample_pics');
        $sampleImages = [];
        
        if (File::exists($samplePicsPath)) {
            $files = File::files($samplePicsPath);
            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $sampleImages[] = $file->getPathname();
                }
            }
        }
        
        // Ensure task-submissions directory exists
        $taskSubmissionsPath = storage_path('app/public/task-submissions');
        if (!File::exists($taskSubmissionsPath)) {
            File::makeDirectory($taskSubmissionsPath, 0755, true);
        }
        
        // Create user-uploaded tasks spanning 30 days
        // Flow: pending → approved (completed/uncompleted) OR rejected
        
        // Distribute tasks to ensure each user creates at least one task
        $usersArray = $users->values()->all(); // Convert to indexed array
        $totalUsers = count($usersArray);
        $totalTasks = 30;
        
        // Create distribution: each user gets at least 1 task, remaining tasks distributed randomly
        $creatorDistribution = [];
        for ($i = 0; $i < $totalUsers; $i++) {
            $creatorDistribution[] = $i; // Each user gets at least 1 task
        }
        
        // Distribute remaining tasks (30 - 19 = 11 tasks)
        $remainingTasks = $totalTasks - $totalUsers;
        for ($i = 0; $i < $remainingTasks; $i++) {
            $creatorDistribution[] = rand(0, $totalUsers - 1); // Random user gets additional task
        }
        
        // Shuffle to randomize the order
        shuffle($creatorDistribution);
        
        for ($day = 0; $day < 30; $day++) {
            $taskDate = Carbon::now()->subDays($day);
            $creationDate = $taskDate->copy()->subDays(rand(3, 7)); // Created 3-7 days before due date
            
            // Select user from distribution (ensures each user gets at least one task)
            $creatorIndex = $creatorDistribution[$day];
            $creator = $usersArray[$creatorIndex];
            
            // Select random task template
            $taskTemplate = $userTaskTemplates[array_rand($userTaskTemplates)];
            $location = $locations[array_rand($locations)];
            $uniqueTitle = $taskTemplate['title'] . ' - ' . $location;
            
            // Determine status: pending, rejected, or approved (will be determined by assignments)
            // Distribution: 70% approved (will become completed/uncompleted based on assignments), 20% pending, 10% rejected
            $statusRand = rand(1, 100);
            $isApproved = false;
            if ($statusRand <= 70) {
                $status = 'pending'; // Start as pending, but will be approved (has approval_date) and become completed/uncompleted
                $isApproved = true;
            } elseif ($statusRand <= 90) {
                $status = 'pending'; // Still waiting for approval
                $isApproved = false;
            } else {
                $status = 'rejected'; // Rejected by admin
                $isApproved = false;
            }
            
            // Set dates based on status
            $approvalDate = null;
            $publishedDate = null;
            
            if ($isApproved) {
                // Approved tasks: approval_date and published_date set (approved by admin)
                $approvalDate = $creationDate->copy()->addDays(rand(1, 3)); // Approved 1-3 days after creation
                $publishedDate = $approvalDate; // Published immediately upon approval
            } elseif ($status === 'rejected') {
                // Rejected: no approval_date or published_date
                $approvalDate = null;
                $publishedDate = null;
            }
            // pending (not approved): no approval_date or published_date
            
            $task = Task::create([
                'FK1_userId' => $creator->userId, // Creator's userId
                'title' => $uniqueTitle,
                'description' => $taskTemplate['description'],
                'task_type' => 'user_uploaded',
                'points_awarded' => $taskTemplate['points'],
                'status' => $status,
                'creation_date' => $creationDate,
                'approval_date' => $approvalDate,
                'due_date' => $taskDate,
                'start_time' => $taskTemplate['start_time'],
                'end_time' => $taskTemplate['end_time'],
                'location' => $location,
                'max_participants' => 1, // User-uploaded tasks can only have 1 participant
                'published_date' => $publishedDate,
                'deactivated_at' => null,
                'created_at' => $creationDate,
                'updated_at' => $taskDate,
            ]);
            
            // Only create assignments for approved tasks (those with approval_date set)
            // User-uploaded tasks can only have ONE participant (not the creator)
            if ($isApproved) {
                // Determine if task is expired
                $isExpired = $taskDate && Carbon::parse($taskDate)->isPast();
                
                // Create assignments following TaskAssignmentSeeder flow
                // For expired tasks: 85% chance of having assignments
                // For active tasks: 90% chance of having assignments
                $shouldHaveParticipant = $isExpired ? (rand(1, 100) <= 85) : (rand(1, 100) <= 90);
                
                if ($shouldHaveParticipant) {
                    // Get a random user who is NOT the creator
                    $availableParticipants = $users->where('userId', '!=', $creator->userId);
                    
                    if ($availableParticipants->isNotEmpty()) {
                        $participant = $availableParticipants->random();
                        
                        // Calculate assignment date (following TaskAssignmentSeeder logic)
                        // Assignment happens between published_date and due_date
                        $taskDueDate = $taskDate ? Carbon::parse($taskDate) : null;
                        $taskPublishedDate = $publishedDate ? Carbon::parse($publishedDate) : null;
                        
                        if ($taskPublishedDate) {
                            if ($taskDueDate) {
                                // Assignment happens between published_date and due_date
                                $daysBetween = max(0, $taskPublishedDate->diffInDays($taskDueDate));
                                if ($daysBetween > 0) {
                                    $assignedAt = $taskPublishedDate->copy()->addDays(rand(0, min($daysBetween, 7)));
                                } else {
                                    // If published_date equals due_date, assign on that date
                                    $assignedAt = $taskPublishedDate->copy();
                                }
                                // Ensure assigned_at doesn't exceed due_date
                                if ($assignedAt->gt($taskDueDate)) {
                                    $assignedAt = $taskDueDate->copy();
                                }
                            } else {
                                // No due date, assign within 7 days of publishing
                                $assignedAt = $taskPublishedDate->copy()->addDays(rand(0, 7));
                            }
                        } else {
                            // Should not happen, but handle gracefully
                            $assignedAt = $creationDate->copy()->addDays(rand(0, 3));
                        }
                        
                        // Determine assignment status following TaskAssignmentSeeder flow
                        // Initial assignment always starts as 'assigned' with progress 'accepted'
                        $assignmentStatus = 'assigned';
                        $submittedAt = null;
                        $completedAt = null;
                        $progress = 'accepted';
                        $photos = null;
                        $completionNotes = null;
                        
                        // Determine assignment status based on task expiration (same as TaskAssignmentSeeder)
                        if ($isExpired) {
                            // Expired tasks: 60% completed, 40% assigned (will become uncompleted)
                            $rand = rand(1, 100);
                            if ($rand <= 60) {
                                // Completed assignment
                                $assignmentStatus = 'completed';
                                $progress = 'submitted_proof';
                                $submittedAt = $assignedAt->copy()->addDays(rand(1, 3));
                                $completedAt = $submittedAt->copy()->addHours(rand(1, 24));
                                
                                // Add photos for completed assignments (2-3 photos required)
                                if (!empty($sampleImages)) {
                                    $numPhotos = rand(2, 3); // At least 2 photos, max 3
                                    $selectedPhotos = array_rand($sampleImages, min($numPhotos, count($sampleImages)));
                                    if (!is_array($selectedPhotos)) {
                                        $selectedPhotos = [$selectedPhotos];
                                    }
                                    
                                    $photos = [];
                                    $photoCounter = 0;
                                    $uniqueId = uniqid('submission_', true); // Same prefix as TaskAssignmentSeeder
                                    foreach ($selectedPhotos as $index) {
                                        $sourceFile = $sampleImages[$index];
                                        $extension = pathinfo($sourceFile, PATHINFO_EXTENSION);
                                        $filename = $uniqueId . '_' . $photoCounter . '.' . $extension;
                                        $destinationPath = $taskSubmissionsPath . '/' . $filename;
                                        
                                        // Copy the image to task-submissions directory
                                        File::copy($sourceFile, $destinationPath);
                                        
                                        // Store the path relative to storage/app/public
                                        $photos[] = 'task-submissions/' . $filename;
                                        $photoCounter++;
                                    }
                                }
                                
                                // Add completion notes
                                $completionNotes = 'Task completed successfully. Approved by task creator.';
                                
                                // Award points to user if assignment is completed (matching TaskAssignmentSeeder logic)
                                $participant->refresh();
                                $participant->addPoints($task->points_awarded);
                            } else {
                                // Assigned but not completed - user joined but didn't complete before deadline
                                $assignmentStatus = 'assigned';
                                $progress = ['accepted', 'on_the_way', 'working'][array_rand(['accepted', 'on_the_way', 'working'])];
                            }
                        } else {
                            // Task is not expired (still active) - assignments can be in various stages
                            // No pending submissions - only completed or assigned
                            $rand = rand(1, 100);
                            if ($rand <= 30) {
                                // Completed: user finished early and creator approved
                                $assignmentStatus = 'completed';
                                $progress = 'submitted_proof';
                                $submittedAt = $assignedAt->copy()->addDays(rand(1, 2));
                                $completedAt = $submittedAt->copy()->addHours(rand(1, 12));
                                
                                // Add photos for completed assignments (2-3 photos required)
                                if (!empty($sampleImages)) {
                                    $numPhotos = rand(2, 3); // At least 2 photos, max 3
                                    $selectedPhotos = array_rand($sampleImages, min($numPhotos, count($sampleImages)));
                                    if (!is_array($selectedPhotos)) {
                                        $selectedPhotos = [$selectedPhotos];
                                    }
                                    
                                    $photos = [];
                                    $photoCounter = 0;
                                    $uniqueId = uniqid('submission_', true); // Same prefix as TaskAssignmentSeeder
                                    foreach ($selectedPhotos as $index) {
                                        $sourceFile = $sampleImages[$index];
                                        $extension = pathinfo($sourceFile, PATHINFO_EXTENSION);
                                        $filename = $uniqueId . '_' . $photoCounter . '.' . $extension;
                                        $destinationPath = $taskSubmissionsPath . '/' . $filename;
                                        
                                        // Copy the image to task-submissions directory
                                        File::copy($sourceFile, $destinationPath);
                                        
                                        // Store the path relative to storage/app/public
                                        $photos[] = 'task-submissions/' . $filename;
                                        $photoCounter++;
                                    }
                                }
                                
                                // Add completion notes
                                $completionNotes = 'Task completed successfully. Approved by task creator.';
                                
                                // Award points to user if assignment is completed (matching TaskAssignmentSeeder logic)
                                $participant->refresh();
                                $participant->addPoints($task->points_awarded);
                            } else {
                                // Assigned: user joined but hasn't completed yet
                                $assignmentStatus = 'assigned';
                                // Progress can vary: accepted, on_the_way, working
                                $progress = ['accepted', 'on_the_way', 'working'][array_rand(['accepted', 'on_the_way', 'working'])];
                            }
                        }
                        
                        TaskAssignment::create([
                            'taskId' => $task->taskId,
                            'userId' => $participant->userId,
                            'status' => $assignmentStatus,
                            'progress' => $progress,
                            'assigned_at' => $assignedAt,
                            'submitted_at' => $submittedAt,
                            'completed_at' => $completedAt,
                            'reminder_sent_at' => null,
                            'photos' => $photos,
                            'completion_notes' => $completionNotes,
                            'rejection_count' => 0,
                            'rejection_reason' => null,
                            'created_at' => $assignedAt,
                            'updated_at' => $completedAt ?? $submittedAt ?? $assignedAt,
                        ]);
                    }
                }
                
                // After creating assignments, update task status based on assignments (following TaskAssignmentSeeder flow)
                $task->refresh();
                $assignments = $task->assignments()->get();
                
                if ($assignments->isEmpty()) {
                    // No assignments - mark as uncompleted if expired
                    if ($task->due_date && Carbon::parse($task->due_date)->isPast()) {
                        $task->update(['status' => 'uncompleted']);
                    }
                    // Otherwise stays approved (but user-uploaded tasks should be completed/uncompleted, so mark as uncompleted)
                    else {
                        $task->update(['status' => 'uncompleted']);
                    }
                } else {
                    // Check assignment statuses
                    $completedCount = $assignments->where('status', 'completed')->count();
                    $assignedCount = $assignments->where('status', 'assigned')->count();
                    
                    // If task is expired (due_date passed), determine final status
                    $isExpired = $task->due_date && Carbon::parse($task->due_date)->isPast();
                    
                    if ($isExpired) {
                        // Task expired - mark as completed if at least one completed, otherwise uncompleted
                        if ($completedCount > 0) {
                            $task->update(['status' => 'completed']);
                            
                            // For completed tasks, mark any remaining assigned assignments as uncompleted
                            // (they didn't complete before deadline)
                            foreach ($assignments->where('status', 'assigned') as $assignment) {
                                $assignment->update(['status' => 'uncompleted']);
                            }
                        } else {
                            // Task is uncompleted - has participants but none completed
                            // Mark all assigned assignments as uncompleted
                            foreach ($assignments->where('status', 'assigned') as $assignment) {
                                $assignment->update(['status' => 'uncompleted']);
                            }
                            $task->update(['status' => 'uncompleted']);
                        }
                    } else {
                        // Task not expired yet
                        // If all assignments are completed, mark task as completed
                        if ($completedCount > 0 && $completedCount === $assignments->count()) {
                            $task->update(['status' => 'completed']);
                        } else {
                            // Otherwise mark as uncompleted (user-uploaded tasks don't stay as approved/published)
                            $task->update(['status' => 'uncompleted']);
                        }
                    }
                }
            }
        }
    }
}

