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

        // Step 2: Mark expired tasks as uncompleted
        $this->markExpiredTasksAsUncompleted($notificationService);

        $this->info('Task deadline processing completed.');
    }

    /**
     * Send notifications to users 1 hour before task deadline
     */
    protected function sendHourBeforeNotifications(NotificationService $notificationService): void
    {
        $now = now();
        $oneHourFromNow = $now->copy()->addHour();

        // Get all active task assignments that haven't received a reminder yet
        $assignments = TaskAssignment::whereIn('status', ['assigned', 'submitted'])
            ->whereNull('reminder_sent_at')
            ->with(['task', 'user'])
            ->get();

        $notifiedCount = 0;

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

            // Check if the deadline is approximately 1 hour from now (within a 5-minute window)
            $timeUntilDeadline = $now->diffInMinutes($deadline, false);

            if ($timeUntilDeadline >= 55 && $timeUntilDeadline <= 65) {
                // Send notification
                $notificationService->notify(
                    $assignment->user,
                    'task_deadline_reminder',
                    "Reminder: Your task \"{$task->title}\" is due in 1 hour. Please complete it soon!",
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
                $this->info("Sent reminder to user {$assignment->user->userId} for task: {$task->title}");
            }
        }

        $this->info("Sent {$notifiedCount} deadline reminder(s).");
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
