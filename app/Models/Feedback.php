<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $primaryKey = 'feedbackId';

    protected $fillable = [
        'FK1_userId',
        'FK2_taskId',
        'rating',
        'comment',
        'feedback_date',
    ];

    protected $casts = [
        'rating' => 'integer',
        'feedback_date' => 'datetime',
    ];

    /**
     * Get the task that this feedback belongs to
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'FK2_taskId', 'taskId');
    }

    /**
     * Get the user that this feedback belongs to
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'FK1_userId', 'userId');
    }
}
