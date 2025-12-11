<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TapNomination;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Carbon\Carbon;

class TapNominationSeeder extends Seeder
{
    public function run()
    {
        // Get all regular users from UserSeeder (non-admin, role = 'user')
        $users = User::where('role', 'user')
            ->where('status', 'active')
            ->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }
        
        // Get all daily tasks from TaskSeeder (admin tasks: FK1_userId is null, task_type = 'daily')
        // We'll filter tasks based on nomination date (tasks that were published at that time)
        $allDailyTasks = Task::where('task_type', 'daily')
            ->whereNull('FK1_userId') // Admin tasks from TaskSeeder (not user-uploaded)
            ->get();
        
        if ($allDailyTasks->isEmpty()) {
            $this->command->warn('No daily tasks found. Please run TaskSeeder first.');
            return;
        }
        
        // Get users who have completed daily tasks (they can nominate)
        // We'll create nominations spanning the last 30 days
        $nominationsCreated = 0;
        $maxNominations = 50; // Create up to 50 nominations
        
        for ($day = 0; $day < 30 && $nominationsCreated < $maxNominations; $day++) {
            $nominationDate = Carbon::now()->subDays($day);
            $todayStart = $nominationDate->copy()->startOfDay();
            $todayEnd = $nominationDate->copy()->endOfDay();
            
            // Get users who completed daily tasks on this day (from TaskAssignmentSeeder)
            // They can nominate - must have completed a daily task on THIS DATE
            $eligibleNominators = TaskAssignment::whereHas('task', function($query) {
                    $query->where('task_type', 'daily')
                          ->whereNull('FK1_userId'); // Only admin tasks from TaskSeeder
                })
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$todayStart, $todayEnd])
                ->whereIn('userId', $users->pluck('userId')) // Only users from UserSeeder
                ->with('user', 'task')
                ->get();
            
            if ($eligibleNominators->isEmpty()) {
                continue; // No eligible nominators for this day
            }
            
            // For each eligible nominator, get their completed task assignment
            // They can nominate someone for a task that was published at the time of nomination
            foreach ($eligibleNominators as $assignment) {
                $nominator = $assignment->user;
                $completedTask = $assignment->task;
                
                if (!$nominator || $nominator->role !== 'user' || $nominator->status !== 'active') {
                    continue;
                }
                
                // Random chance: 60% of eligible nominators will create nominations
                if (rand(1, 100) > 60) {
                    continue;
                }
                
                // Get available daily tasks that were published at the time of nomination
                // Task must have been published on or before the nomination date
                // Task must not have had completed assignments at the time of nomination
                $availableTasks = $allDailyTasks->filter(function($task) use ($nominationDate) {
                    // Task must have been published on or before nomination date
                    if (!$task->published_date) {
                        return false;
                    }
                    
                    $taskPublishedDate = Carbon::parse($task->published_date);
                    if ($taskPublishedDate->gt($nominationDate)) {
                        return false; // Task wasn't published yet at nomination time
                    }
                    
                    // Task must not have had completed assignments at the time of nomination
                    // Check assignments that were completed before or on nomination date
                    $hasCompletedAssignmentsBeforeNomination = $task->assignments()
                        ->where('status', 'completed')
                        ->where('completed_at', '<=', $nominationDate->endOfDay())
                        ->exists();
                    
                    if ($hasCompletedAssignmentsBeforeNomination) {
                        return false;
                    }
                    
                    // Task must not be at max participants (check assignments at nomination time)
                    if ($task->max_participants !== null) {
                        $assignmentsAtNominationTime = $task->assignments()
                            ->where('assigned_at', '<=', $nominationDate->endOfDay())
                            ->count();
                        
                        if ($assignmentsAtNominationTime >= $task->max_participants) {
                            return false;
                        }
                    }
                    
                    return true;
                });
                
                if ($availableTasks->isEmpty()) {
                    continue; // No available tasks for this nominator on this date
                }
                
                // Select a random task
                $task = $availableTasks->random();
                
                // Get available nominees (users from UserSeeder who can be nominated)
                // Check if they were assigned to this task at the time of nomination
                $availableNominees = $users->filter(function($user) use ($nominator, $task, $nominationDate) {
                    // Cannot nominate yourself
                    if ($user->userId === $nominator->userId) {
                        return false;
                    }
                    
                    // Users from UserSeeder are already filtered (role = 'user', status = 'active')
                    // But double-check to be safe
                    if ($user->role !== 'user' || $user->status !== 'active') {
                        return false;
                    }
                    
                    // Cannot nominate users already assigned to this task at nomination time
                    $wasAssignedAtNominationTime = $task->assignments()
                        ->where('userId', $user->userId)
                        ->where('assigned_at', '<=', $nominationDate->endOfDay())
                        ->exists();
                    
                    if ($wasAssignedAtNominationTime) {
                        return false;
                    }
                    
                    // Check if there's already a nomination for this task-user pair (any status)
                    $existingNomination = TapNomination::where('FK1_taskId', $task->taskId)
                        ->where('FK2_nominatorId', $nominator->userId)
                        ->where('FK3_nomineeId', $user->userId)
                        ->exists();
                    
                    if ($existingNomination) {
                        return false;
                    }
                    
                    // Check for reciprocal nominations (if nominee has nominated nominator)
                    $reciprocalNomination = TapNomination::where('FK1_taskId', $task->taskId)
                        ->where('FK2_nominatorId', $user->userId)
                        ->where('FK3_nomineeId', $nominator->userId)
                        ->exists();
                    
                    if ($reciprocalNomination) {
                        return false;
                    }
                    
                    return true;
                });
                
                if ($availableNominees->isEmpty()) {
                    continue; // No available nominees
                }
                
                // Select a random nominee
                $nominee = $availableNominees->random();
                
                // Determine nomination status
                // Distribution: 70% accepted, 30% declined (no pending nominations)
                $statusRand = rand(1, 100);
                
                if ($statusRand <= 70) {
                    $status = 'accepted';
                } else {
                    $status = 'declined';
                }
                
                // Create nomination on the date when the user completed their daily task
                // Use the completion time as the nomination time (or shortly after)
                $completionTime = Carbon::parse($assignment->completed_at);
                $nominationDateTime = $completionTime->copy()->addHours(rand(1, 6)); // 1-6 hours after completion
                
                // Ensure nomination is on the same day as completion
                if ($nominationDateTime->day !== $completionTime->day) {
                    $nominationDateTime = $completionTime->copy()->addHours(rand(1, min(6, 23 - $completionTime->hour)));
                }
                
                $nomination = TapNomination::create([
                    'FK1_taskId' => $task->taskId,
                    'FK2_nominatorId' => $nominator->userId,
                    'FK3_nomineeId' => $nominee->userId,
                    'nomination_date' => $nominationDateTime, // Nomination happens on completion date
                    'status' => $status,
                    'created_at' => $nominationDateTime,
                    'updated_at' => $nominationDateTime,
                ]);
                
                // Award 1 point to nominator for creating nomination
                $nominator->refresh();
                $nominator->addPoints(1);
                
                // If accepted, create task assignment (following TaskAssignmentSeeder logic)
                if ($status === 'accepted') {
                    // Check if nominee is already assigned (shouldn't happen, but safety check)
                    if (!$task->isAssignedTo($nominee->userId)) {
                        // Assignment date calculation (following TaskAssignmentSeeder logic)
                        // Assignment happens after task was published, before or on due date
                        $taskDueDate = $task->due_date ? Carbon::parse($task->due_date) : null;
                        $taskPublishedDate = $task->published_date ? Carbon::parse($task->published_date) : null;
                        
                        // Assignment happens after nomination (1-6 hours later)
                        // Use the nomination datetime (which is based on completion time)
                        $assignedAt = $nominationDateTime->copy()->addHours(rand(1, 6));
                        
                        if ($taskPublishedDate) {
                            // Ensure assigned_at is after published_date (per TaskAssignmentSeeder logic)
                            if ($assignedAt->lt($taskPublishedDate)) {
                                $assignedAt = $taskPublishedDate->copy()->addHours(rand(0, 6));
                            }
                        }
                        
                        // Ensure assigned_at doesn't exceed task due_date if set
                        if ($taskDueDate && $assignedAt->gt($taskDueDate)) {
                            $assignedAt = $taskDueDate->copy();
                        }
                        
                        // Create task assignment (following TaskAssignmentSeeder format)
                        TaskAssignment::create([
                            'taskId' => $task->taskId,
                            'userId' => $nominee->userId,
                            'status' => 'assigned',
                            'progress' => 'accepted', // Initial progress when accepting nomination
                            'assigned_at' => $assignedAt,
                            'reminder_sent_at' => null,
                            'photos' => null,
                            'completion_notes' => null,
                            'rejection_count' => 0,
                            'rejection_reason' => null,
                            'created_at' => $assignedAt,
                            'updated_at' => $assignedAt,
                        ]);
                    }
                    
                    // Award 1 point to nominee for accepting (respecting points cap)
                    $nominee->refresh();
                    $nominee->addPoints(1);
                    
                    // Update nomination updated_at to reflect acceptance time
                    $acceptanceTime = $nominationDateTime->copy()->addHours(rand(1, 6));
                    $nomination->update(['updated_at' => $acceptanceTime]);
                }
                
                $nominationsCreated++;
                
                // Limit nominations per day to keep it realistic
                if ($nominationsCreated >= $maxNominations) {
                    break 2; // Break out of both loops
                }
            }
        }
        
        $this->command->info("Created {$nominationsCreated} Tap & Pass nominations.");
    }
}

