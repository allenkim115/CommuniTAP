<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TapNomination extends Model
{
    use HasFactory;

    protected $primaryKey = 'nominationId';

    protected $fillable = [
        'FK1_taskId',
        'FK2_nominatorId',
        'FK3_nomineeId',
        'nomination_date',
        'status'
    ];

    protected $casts = [
        'nomination_date' => 'datetime',
    ];

    /**
     * Get the task being nominated for
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'FK1_taskId', 'taskId');
    }

    /**
     * Get the user who made the nomination
     */
    public function nominator()
    {
        return $this->belongsTo(User::class, 'FK2_nominatorId', 'userId');
    }

    /**
     * Get the user being nominated
     */
    public function nominee()
    {
        return $this->belongsTo(User::class, 'FK3_nomineeId', 'userId');
    }

    /**
     * Scope for pending nominations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for accepted nominations
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope for declined nominations
     */
    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    /**
     * Scope for nominations by nominator
     */
    public function scopeByNominator($query, $userId)
    {
        return $query->where('FK2_nominatorId', $userId);
    }

    /**
     * Scope for nominations by nominee
     */
    public function scopeByNominee($query, $userId)
    {
        return $query->where('FK3_nomineeId', $userId);
    }

    /**
     * Scope for daily task nominations only
     */
    public function scopeForDailyTasks($query)
    {
        return $query->whereHas('task', function($q) {
            $q->where('task_type', 'daily');
        });
    }

    /**
     * Check if nomination is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if nomination is accepted
     */
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if nomination is declined
     */
    public function isDeclined()
    {
        return $this->status === 'declined';
    }
}
