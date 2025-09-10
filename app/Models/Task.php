<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return true;
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
     * Check if task is available for joining
     */
    public function isAvailable()
    {
        return $this->status === 'published' && is_null($this->FK1_userId);
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
}
