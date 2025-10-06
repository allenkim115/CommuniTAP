<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserIncidentReport extends Model
{
    use HasFactory;

    protected $primaryKey = 'reportId';

    protected $fillable = [
        'FK1_reporterId',
        'FK2_reportedUserId',
        'FK3_taskId',
        'incident_type',
        'description',
        'evidence',
        'status',
        'FK4_moderatorId',
        'moderator_notes',
        'action_taken',
        'moderation_date',
        'report_date',
    ];

    protected $casts = [
        'report_date' => 'datetime',
        'moderation_date' => 'datetime',
    ];

    /**
     * Get the user who reported the incident
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'FK1_reporterId', 'userId');
    }

    /**
     * Get the user who was reported
     */
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'FK2_reportedUserId', 'userId');
    }

    /**
     * Get the task related to the incident (if any)
     */
    public function task()
    {
        return $this->belongsTo(Task::class, 'FK3_taskId', 'taskId');
    }

    /**
     * Get the moderator who handled the report
     */
    public function moderator()
    {
        return $this->belongsTo(User::class, 'FK4_moderatorId', 'userId');
    }

    /**
     * Scope for pending reports
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for under review reports
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    /**
     * Scope for resolved reports
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope for dismissed reports
     */
    public function scopeDismissed($query)
    {
        return $query->where('status', 'dismissed');
    }

    /**
     * Scope for reports by incident type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('incident_type', $type);
    }

    /**
     * Check if report is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if report is under review
     */
    public function isUnderReview()
    {
        return $this->status === 'under_review';
    }

    /**
     * Check if report is resolved
     */
    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    /**
     * Check if report is dismissed
     */
    public function isDismissed()
    {
        return $this->status === 'dismissed';
    }

    /**
     * Get incident type options
     */
    public static function getIncidentTypes()
    {
        return [
            'non_participation' => 'Non-participation in Task',
            'abuse' => 'Abusive Behavior',
            'spam' => 'Spam or Unwanted Content',
            'inappropriate_content' => 'Inappropriate Content',
            'harassment' => 'Harassment',
            'other' => 'Other',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatuses()
    {
        return [
            'pending' => 'Pending Review',
            'under_review' => 'Under Review',
            'resolved' => 'Resolved',
            'dismissed' => 'Dismissed',
        ];
    }

    /**
     * Get action taken options
     */
    public static function getActionsTaken()
    {
        return [
            'warning' => 'Warning Issued',
            'suspension' => 'User Suspended',
            'no_action' => 'No Action Required',
            'dismissed' => 'Report Dismissed',
        ];
    }
}
