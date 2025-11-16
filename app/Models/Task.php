<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use HasFactory;

    protected $primaryKey = 'taskId';

    protected $fillable = [
        'FK1_userId',
        'title',
        'description',
        'task_type',
        'points_awarded',
        'status',
        'creation_date',
        'approval_date',
        'due_date',
        'start_time',
        'end_time',
        'location',
        'max_participants',
        'published_date'
    ];

    protected $casts = [
        'creation_date' => 'datetime',
        'approval_date' => 'datetime',
        'due_date' => 'datetime',
        'published_date' => 'datetime',
        'points_awarded' => 'integer',
    ];

    /**
     * Get the user assigned to this task (for backward compatibility)
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'FK1_userId', 'userId');
    }

    /**
     * Get all assignments for this task
     */
    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class, 'taskId', 'taskId');
    }

    /**
     * Get all users assigned to this task
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_assignments', 'taskId', 'userId', 'taskId', 'userId')
                    ->withPivot('status', 'assigned_at', 'submitted_at', 'completed_at')
                    ->withTimestamps();
    }

    /**
     * Get the number of users assigned to this task
     */
    public function getAssignedUsersCountAttribute()
    {
        return $this->assignments()->count();
    }

    /**
     * Check if a specific user is assigned to this task
     */
    public function isAssignedTo($userId)
    {
        return $this->assignments()->where('userId', $userId)->exists();
    }

    /**
     * Check if this task can accept more users
     */
    public function canAcceptMoreUsers()
    {
        // User-uploaded tasks can only have one user
        if ($this->task_type === 'user_uploaded') {
            return $this->assignments()->count() === 0;
        }
        
        // Daily and One-Time tasks can have multiple users
        if ($this->max_participants === null) {
            return true;
        }

        return $this->assignments()->count() < $this->max_participants;
    }

    /**
     * Scope for pending tasks
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for non-pending tasks (completed, approved, etc.)
     */
    public function scopeNotPending($query)
    {
        return $query->where('status', '!=', 'pending');
    }

    /**
     * Scope for published tasks (available for joining)
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for available tasks (published and unassigned)
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'published')->whereNull('FK1_userId');
    }

    /**
     * Scope for tasks by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('task_type', $type);
    }

    /**
     * Scope for tasks by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for tasks whose deadline (date + optional end_time) is in the past
     */
    public function scopeExpired($query)
    {
        // If end_time exists, use DATE(due_date) + end_time; otherwise use due_date directly
        return $query->where(function ($q) {
            $q->where(function ($q2) {
                $q2->whereNotNull('end_time')
                   ->whereRaw("CONCAT(DATE(due_date), ' ', end_time) < NOW()");
            })
            ->orWhere(function ($q3) {
                $q3->whereNull('end_time')
                   ->whereNotNull('due_date')
                   ->where('due_date', '<', now());
            });
        });
    }

    /**
     * Scope for tasks whose deadline has not passed yet
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->where(function ($q2) {
                $q2->whereNotNull('end_time')
                   ->whereRaw("CONCAT(DATE(due_date), ' ', end_time) >= NOW()");
            })
            ->orWhere(function ($q3) {
                $q3->whereNull('end_time')
                   ->where(function ($q4) {
                        $q4->whereNull('due_date')
                           ->orWhere('due_date', '>=', now());
                   });
            });
        });
    }

    /**
     * Check if task is available for joining
     */
    public function isAvailable()
    {
        return $this->status === 'published' && is_null($this->FK1_userId);
    }

    /**
     * Determine if the task is expired based on due_date and end_time
     */
    public function isExpired(): bool
    {
        if (is_null($this->due_date)) {
            return false;
        }

        if (!is_null($this->end_time)) {
            $deadline = \Carbon\Carbon::parse($this->due_date->toDateString() . ' ' . $this->end_time);
            return now()->gt($deadline);
        }

        return now()->gt($this->due_date);
    }

    /**
     * Mark all expired tasks and their active assignments as completed
     */
    public static function completeExpiredTasks(): int
    {
        $updatedCount = 0;

        $expiredTasks = static::expired()
            ->whereIn('status', ['published', 'assigned', 'submitted', 'approved', 'pending'])
            ->get();

        foreach ($expiredTasks as $task) {
            // Complete task-level status
            $task->status = 'completed';
            $task->save();
            $updatedCount++;

            // Complete any active assignments
            foreach ($task->assignments()->whereIn('status', ['assigned', 'submitted'])->get() as $assignment) {
                $assignment->status = 'completed';
                $assignment->completed_at = now();
                $assignment->save();
            }
        }

        return $updatedCount;
    }

    /**
     * Check if task can be edited by user
     */
    public function canBeEditedBy($user)
    {
        return $this->FK1_userId === $user->userId && 
               $this->task_type === 'user_uploaded' && 
               in_array($this->status, ['pending', 'rejected']);
    }

    /**
     * Check if task can be deleted by user
     */
    public function canBeDeletedBy($user)
    {
        return $this->FK1_userId === $user->userId && 
               $this->task_type === 'user_uploaded' && 
               in_array($this->status, ['pending', 'rejected']);
    }

    /**
     * Get all nominations for this task
     */
    public function nominations()
    {
        return $this->hasMany(TapNomination::class, 'FK1_taskId', 'taskId');
    }

    /**
     * Check if this task is a daily task
     */
    public function isDailyTask()
    {
        return $this->task_type === 'daily';
    }

    /**
     * Check if all participants have completed their assignments
     */
    public function areAllParticipantsCompleted(): bool
    {
        $totalAssignments = $this->assignments()->count();
        
        // If there are no assignments, return false
        if ($totalAssignments === 0) {
            return false;
        }
        
        // Check if all assignments are completed
        $completedAssignments = $this->assignments()->where('status', 'completed')->count();
        
        return $completedAssignments === $totalAssignments;
    }

    /**
     * Automatically mark task as completed if all participants have completed their assignments
     * This can happen even before the due date
     */
    public function markAsCompletedIfAllParticipantsDone(): bool
    {
        // Only mark as completed if task is not already completed and not inactive
        if ($this->status === 'completed' || $this->status === 'inactive') {
            return false;
        }
        
        // Check if all participants have completed
        if ($this->areAllParticipantsCompleted()) {
            $this->status = 'completed';
            $this->save();
            return true;
        }
        
        return false;
    }
}
