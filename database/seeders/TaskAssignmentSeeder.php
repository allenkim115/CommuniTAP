<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskAssignment;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TaskAssignmentSeeder extends Seeder
{
    public function run()
    {
        // Get all users with role 'user' from UserSeeder
        $users = User::where('role', 'user')->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }
        
        // Get sample images from the sample_pics folder
        $samplePicsPath = public_path('images/sample_pics');
        $sampleImages = [];
        
        if (File::exists($samplePicsPath)) {
            $files = File::files($samplePicsPath);
            foreach ($files as $file) {
                // Only include image files
                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $sampleImages[] = $file->getPathname();
                }
            }
        }
        
        if (empty($sampleImages)) {
            $this->command->warn('No sample images found in public/images/sample_pics. Photos will not be added to submissions.');
        }
        
        // Ensure task-submissions directory exists
        $taskSubmissionsPath = storage_path('app/public/task-submissions');
        if (!File::exists($taskSubmissionsPath)) {
            File::makeDirectory($taskSubmissionsPath, 0755, true);
        }
        
        // Only assign to admin tasks from TaskSeeder (FK1_userId is null)
        // User-uploaded tasks are handled by UserUploadedTaskSeeder
        // Don't assign to inactive or pending tasks
        $tasks = Task::whereIn('status', ['published', 'completed', 'uncompleted'])
            ->whereNull('FK1_userId') // Only admin tasks from TaskSeeder (not user-uploaded)
            ->get();
        
        if ($tasks->isEmpty()) {
            $this->command->warn('No eligible admin tasks found. Please run TaskSeeder first.');
            return;
        }
        
        // Process tasks and create assignments
        // After creating assignments, update task status based on assignments
        foreach ($tasks as $task) {
            // Check if task is expired
            $isExpired = $task->due_date && Carbon::parse($task->due_date)->isPast();
            
            // Create assignments for most tasks to make it look natural
            // Reduce uncompleted tasks by ensuring most tasks get assignments and completions
            if ($isExpired) {
                // 95% chance of creating assignments for expired tasks (reduced from 85%)
                // This reduces tasks with no participants (uncompleted)
                if (rand(1, 100) > 95) {
                    continue;
                }
            } else {
                // Active tasks: 95% chance of having assignments (increased from 90%)
                if (rand(1, 100) > 95) {
                    continue;
                }
            }
            
            // Determine how many users to assign (respect max_participants)
            $maxAssignments = $task->max_participants ?? 50;
            $currentAssignments = $task->assignments()->count();
            $availableSlots = max(0, $maxAssignments - $currentAssignments);
            
            if ($availableSlots === 0) {
                continue;
            }
            
            // Determine number of assignments
            if ($isExpired) {
                // Expired tasks: 2-6 assignments (more realistic distribution)
                $numAssignments = rand(2, min($availableSlots, 6));
            } else {
                // Active tasks: 1-5 assignments
                $numAssignments = rand(1, min($availableSlots, 5));
            }
            
            // Get random users, ensuring we don't assign the same user twice to the same task
            $availableUsers = $users->shuffle()->take($numAssignments);
            
            // Track assignment index for completed tasks (ensure at least one is completed for expired tasks)
            $assignmentIndex = 0;
            $hasCompletedAssignment = false;
            $totalAssignments = 0;
            
            foreach ($availableUsers as $user) {
                // Check if user is already assigned to this task
                if ($task->isAssignedTo($user->userId)) {
                    continue;
                }
                
                // Prevent assigning user to their own task (per controller logic)
                if ($task->FK1_userId && $task->FK1_userId === $user->userId) {
                    continue;
                }
                
                // Ensure task was published before assignment (per controller logic)
                if (!$task->published_date) {
                    continue;
                }
                
                // Determine assignment status based on task status and date
                $taskDueDate = $task->due_date ? Carbon::parse($task->due_date) : null;
                $taskPublishedDate = $task->published_date ? Carbon::parse($task->published_date) : null;
                $now = Carbon::now();
                
                // Calculate assignment date (after task was published, before or on due date)
                // Per controller logic: users can only join published tasks, so assigned_at must be >= published_date
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
                    // Should not happen due to check above, but handle gracefully
                    $assignedAt = $task->creation_date ? Carbon::parse($task->creation_date)->addDays(rand(0, 3)) : now();
                }
                
                // Determine assignment status based on task dates and expiration
                // Initial assignment always starts as 'assigned' with progress 'accepted' (per join() method)
                // Then transitions to submitted -> completed or uncompleted
                $assignmentStatus = 'assigned';
                $submittedAt = null;
                $completedAt = null;
                $progress = 'accepted';
                $photos = null;
                $completionNotes = null;
                
                // Check if task is expired (due_date passed)
                $isExpired = $taskDueDate && Carbon::parse($taskDueDate)->isPast();
                
                // Determine assignment status based on task expiration
                // All tasks start as 'published', so we use expiration to determine status
                if ($isExpired) {
                    // Expired tasks: create mix of completed and uncompleted assignments
                    // Increased completion rate to reduce uncompleted tasks
                    // Ensure at least one assignment is completed if this is the last assignment
                    $isLastAssignment = ($assignmentIndex === $numAssignments - 1);
                    $rand = rand(1, 100);
                    
                    // If no completed assignments yet and this is the last one, force completion
                    if (!$hasCompletedAssignment && $isLastAssignment) {
                        $rand = 1; // Force completion (will be <= 80)
                    }
                    
                    // 80% completed, 20% assigned (will become uncompleted) - increased from 60/40
                    if ($rand <= 80) {
                        // Completed assignment - admin approved before deadline
                        $assignmentStatus = 'completed';
                        $progress = 'submitted_proof';
                        $submittedAt = $assignedAt->copy()->addDays(rand(1, 3));
                        $completedAt = $submittedAt->copy()->addHours(rand(1, 24));
                        $hasCompletedAssignment = true;
                        
                        // Add photos for completed assignments (2-3 photos required)
                        if (!empty($sampleImages)) {
                            $numPhotos = rand(2, 3); // At least 2 photos, max 3
                            $selectedPhotos = array_rand($sampleImages, min($numPhotos, count($sampleImages)));
                            if (!is_array($selectedPhotos)) {
                                $selectedPhotos = [$selectedPhotos];
                            }
                            
                            $photos = [];
                            $photoCounter = 0;
                            $uniqueId = uniqid('submission_', true);
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
                        $completionNotes = 'Task completed successfully. All requirements have been met.';
                    } else {
                        // Assigned but not completed - user joined but didn't complete before deadline
                        // This will make the task uncompleted (has participants but no completed assignments)
                        $assignmentStatus = 'assigned';
                        $progress = ['accepted', 'on_the_way', 'working'][array_rand(['accepted', 'on_the_way', 'working'])];
                    }
                    $assignmentIndex++;
                    $totalAssignments++;
                } else {
                    // Task is not expired (still active) - assignments can be in various stages
                    // No pending submissions - only completed or assigned
                    // Increased completion rate for active tasks too
                    $rand = rand(1, 100);
                    if ($rand <= 40) {
                        // Completed: user finished early and admin approved
                        $assignmentStatus = 'completed';
                        $progress = 'submitted_proof';
                        $submittedAt = $assignedAt->copy()->addDays(rand(1, 2));
                        $completedAt = $submittedAt->copy()->addHours(rand(1, 12));
                        $hasCompletedAssignment = true;
                        
                        // Add photos for completed assignments (2-3 photos required)
                        if (!empty($sampleImages)) {
                            $numPhotos = rand(2, 3); // At least 2 photos, max 3
                            $selectedPhotos = array_rand($sampleImages, min($numPhotos, count($sampleImages)));
                            if (!is_array($selectedPhotos)) {
                                $selectedPhotos = [$selectedPhotos];
                            }
                            
                            $photos = [];
                            $photoCounter = 0;
                            $uniqueId = uniqid('submission_', true);
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
                        $completionNotes = 'Task completed successfully. All requirements have been met.';
                    } else {
                        // Assigned: user joined but hasn't completed yet
                        $assignmentStatus = 'assigned';
                        // Progress can vary: accepted, on_the_way, working
                        $progress = ['accepted', 'on_the_way', 'working'][array_rand(['accepted', 'on_the_way', 'working'])];
                    }
                }
                
                $assignment = TaskAssignment::create([
                    'taskId' => $task->taskId,
                    'userId' => $user->userId,
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
                
                // Track completed assignments for expired tasks
                if ($isExpired && $assignmentStatus === 'completed') {
                    $hasCompletedAssignment = true;
                }
                
                // Award points to user if assignment is completed (matching controller logic)
                if ($assignmentStatus === 'completed' && $task->points_awarded > 0) {
                    // Refresh user to get current points
                    $user->refresh();
                    // Award points (respecting points cap of 500)
                    $user->addPoints($task->points_awarded);
                }
                
                $assignmentIndex++;
            }
            
            // For expired tasks, ensure at least one assignment is completed
            // If no completed assignments were created, convert the last assigned one to completed
            if ($isExpired && !$hasCompletedAssignment) {
                $task->refresh();
                $assignments = $task->assignments()->where('status', 'assigned')->get();
                if ($assignments->isNotEmpty()) {
                    $lastAssignment = $assignments->last();
                    
                    // Convert to completed
                    $submittedAt = $lastAssignment->assigned_at->copy()->addDays(rand(1, 3));
                    $completedAt = $submittedAt->copy()->addHours(rand(1, 24));
                    
                    // Add photos if available
                    $photos = null;
                    if (!empty($sampleImages)) {
                        $numPhotos = rand(2, 3);
                        $selectedPhotos = array_rand($sampleImages, min($numPhotos, count($sampleImages)));
                        if (!is_array($selectedPhotos)) {
                            $selectedPhotos = [$selectedPhotos];
                        }
                        
                        $photos = [];
                        $photoCounter = 0;
                        $uniqueId = uniqid('submission_', true);
                        foreach ($selectedPhotos as $index) {
                            $sourceFile = $sampleImages[$index];
                            $extension = pathinfo($sourceFile, PATHINFO_EXTENSION);
                            $filename = $uniqueId . '_' . $photoCounter . '.' . $extension;
                            $destinationPath = $taskSubmissionsPath . '/' . $filename;
                            
                            File::copy($sourceFile, $destinationPath);
                            $photos[] = 'task-submissions/' . $filename;
                            $photoCounter++;
                        }
                    }
                    
                    $lastAssignment->update([
                        'status' => 'completed',
                        'progress' => 'submitted_proof',
                        'submitted_at' => $submittedAt,
                        'completed_at' => $completedAt,
                        'photos' => $photos,
                        'completion_notes' => 'Task completed successfully. All requirements have been met.',
                        'updated_at' => $completedAt,
                    ]);
                    
                    // Award points
                    $lastUser = User::find($lastAssignment->userId);
                    if ($lastUser && $task->points_awarded > 0) {
                        $lastUser->refresh();
                        $lastUser->addPoints($task->points_awarded);
                    }
                }
            }
            
            // After creating assignments, update task status based on assignments
            // This matches the real business logic: tasks become completed/uncompleted based on assignments
            $task->refresh();
            $assignments = $task->assignments()->get();
            
            if ($assignments->isEmpty()) {
                // No assignments - keep as published (or mark as uncompleted if expired)
                if ($task->due_date && Carbon::parse($task->due_date)->isPast()) {
                    $task->update(['status' => 'uncompleted']);
                }
                // Otherwise stays published
            } else {
                // Check assignment statuses
                $completedCount = $assignments->where('status', 'completed')->count();
                $submittedCount = $assignments->where('status', 'submitted')->count();
                $uncompletedCount = $assignments->where('status', 'uncompleted')->count();
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
                    }
                    // Otherwise stays published (users are still working on it)
                }
            }
        }
    }
}
