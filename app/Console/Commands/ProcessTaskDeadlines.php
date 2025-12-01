<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\TaskAssignment;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessTaskDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:process-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process task deadlines: send 1-hour reminders and mark expired tasks as uncompleted';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('Processing task deadlines...');

        // Step 1: Send notifications for tasks that are 1 hour before deadline
        $this->sendHourBeforeNotifications($notificationService);

        // Step 2: Mark expired task assignments as uncompleted (user-side cleanup + notifications)
        $this->markExpiredTasksAsUncompleted($notificationService);

        // Step 3: Update task-level statuses for all expired tasks (including user-uploaded)
        // This ensures:
        // - Tasks with no participants are marked as "uncompleted" instead of staying "published/live"
        // - Tasks with participants are marked correctly as "completed" or "uncompleted"
        //   based on whether anyone actually finished the task
        $completedUpdates = Task::completeExpiredTasks();
        $this->info("Completed processing for {$completedUpdates} expired task(s) (status updated to completed/uncompleted).");

        // Step 4: Fix any tasks incorrectly left as completed (e.g., no participants or none actually completed)
        $fixedTasks = Task::fixIncorrectlyCompletedTasks();
        $this->info("Fixed {$fixedTasks} incorrectly marked completed task(s).");

        $this->info('Task deadline processing completed.');
    }

    /**
     * Send notifications to users 1 hour before task deadline
     */
    protected function sendHourBeforeNotifications(NotificationService $notificationService): void
    {
        $now = now();

        // Get all active task assignments that haven't received a reminder yet
        $assignments = TaskAssignment::whereIn('status', ['assigned', 'submitted'])
            ->whereNull('reminder_sent_at')
            ->with(['task', 'user'])
            ->get();

        $notifiedCount = 0;
        $checkedCount = 0;

        foreach ($assignments as $assignment) {
            $task = $assignment->task;

            if (!$task || !$task->due_date) {
                continue;
            }

            // Calculate the deadline
            $deadline = $this->getTaskDeadline($task);

            if (!$deadline) {
                continue;
            }

            // Skip if deadline has already passed
            if ($now->gt($deadline)) {
                continue;
            }

            $checkedCount++;

            // Check if the deadline is approximately 1 hour from now
            // Use a wider window (50-70 minutes) to account for 5-minute scheduler intervals
            $timeUntilDeadline = $now->diffInMinutes($deadline, false);

            // Debug output for tasks approaching deadline
            if ($timeUntilDeadline <= 120) {
                $this->line("Task '{$task->title}' (ID: {$task->taskId}) - Deadline in {$timeUntilDeadline} minutes (deadline: {$deadline->format('Y-m-d H:i')})");
            }

            $shouldSendReminder = false;
            $reminderMessage = "";

            // Send notification if deadline is between 50-70 minutes away (1-hour reminder)
            // This wider window ensures the scheduler (running every 5 min) will catch it
            if ($timeUntilDeadline >= 50 && $timeUntilDeadline <= 70) {
                $shouldSendReminder = true;
                $reminderMessage = "Reminder: Your task \"{$task->title}\" is due in 1 hour. Please complete it soon!";
            }
            // If user joined late and deadline is less than 1 hour but more than 15 minutes away, send a late reminder
            elseif ($timeUntilDeadline >= 15 && $timeUntilDeadline < 50) {
                $shouldSendReminder = true;
                $minutes = round($timeUntilDeadline);
                $reminderMessage = "Reminder: Your task \"{$task->title}\" is due in {$minutes} minutes. Please complete it soon!";
            }

            if ($shouldSendReminder) {
                // Send notification
                $notificationService->notify(
                    $assignment->user,
                    'task_deadline_reminder',
                    $reminderMessage,
                    [
                        'url' => route('tasks.show', $task),
                        'taskId' => $task->taskId,
                        'description' => "Task deadline: {$deadline->format('Y-m-d H:i')}",
                    ]
                );

                // Mark reminder as sent
                $assignment->reminder_sent_at = $now;
                $assignment->save();

                $notifiedCount++;
                $this->info("✓ Sent reminder to user {$assignment->user->userId} ({$assignment->user->firstName} {$assignment->user->lastName}) for task: {$task->title} (deadline in {$timeUntilDeadline} min)");
            }
        }

        $this->info("Checked {$checkedCount} task assignment(s), sent {$notifiedCount} deadline reminder(s).");
    }

    /**
     * Mark expired tasks as uncompleted and remove from assigned tasks
     */
    protected function markExpiredTasksAsUncompleted(NotificationService $notificationService): void
    {
        $now = now();

        // Get all active task assignments for expired tasks
        $assignments = TaskAssignment::whereIn('status', ['assigned', 'submitted'])
            ->with(['task', 'user'])
            ->get();

        $uncompletedCount = 0;

        foreach ($assignments as $assignment) {
            $task = $assignment->task;

            if (!$task || !$task->due_date) {
                continue;
            }

            // Calculate the deadline
            $deadline = $this->getTaskDeadline($task);

            if (!$deadline) {
                continue;
            }

            // Check if the deadline has passed
            if ($now->gt($deadline)) {
                // Mark assignment as uncompleted
                $assignment->status = 'uncompleted';
                $assignment->save();

                // Send notification to user
                $notificationService->notify(
                    $assignment->user,
                    'task_marked_uncompleted',
                    "Your task \"{$task->title}\" was not completed within the deadline and has been marked as uncompleted.",
                    [
                        'url' => route('tasks.index'),
                        'taskId' => $task->taskId,
                        'description' => 'The task has been removed from your assigned tasks.',
                    ]
                );

                $uncompletedCount++;
                $this->info("Marked task assignment {$assignment->assignmentId} as uncompleted for task: {$task->title}");
            }
        }

        $this->info("Marked {$uncompletedCount} task assignment(s) as uncompleted.");
    }

    /**
     * Get the task deadline (due_date + end_time if available)
     */
    protected function getTaskDeadline(Task $task): ?Carbon
    {
        if (!$task->due_date) {
            return null;
        }

        if ($task->end_time) {
            // Combine due_date date with end_time
            $deadline = Carbon::parse($task->due_date->toDateString() . ' ' . $task->end_time);
        } else {
            // Use due_date directly
            $deadline = Carbon::parse($task->due_date);
        }

        return $deadline;
    }
}
