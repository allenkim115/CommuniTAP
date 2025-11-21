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
        'progress',
        'assigned_at',
        'submitted_at',
        'completed_at',
        'reminder_sent_at',
        'photos',
        'completion_notes',
        'rejection_count',
        'rejection_reason',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'photos' => 'array',
    ];

    /**
     * Get progress steps and current index helper
     */
    public function getProgressSteps(): array
    {
        $steps = ['accepted', 'on_the_way', 'working', 'done', 'submitted_proof'];
        $currentIndex = array_search($this->progress, $steps, true);
        if ($currentIndex === false) {
            $currentIndex = 0;
        }
        return [
            'steps' => $steps,
            'currentIndex' => $currentIndex,
        ];
    }

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

    /**
     * Scope for uncompleted status
     */
    public function scopeUncompleted($query)
    {
        return $query->where('status', 'uncompleted');
    }
}