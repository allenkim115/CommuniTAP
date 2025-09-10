<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    use HasFactory;

    protected $primaryKey = 'assignmentId';

    protected $fillable = [
        'taskId',
        'userId',
        'status',
        'assigned_at',
        'submitted_at',
        'completed_at',
        'photos',
        'completion_notes',
        'rejection_count',
        'rejection_reason',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
        'photos' => 'array',
    ];

    /**
     * Get the task that this assignment belongs to
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'taskId', 'taskId');
    }

    /**
     * Get the user that this assignment belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'userId');
    }

    /**
     * Scope for assigned status
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    /**
     * Scope for submitted status
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope for completed status
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}