<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Maximum points a user can have
     */
    public const POINTS_CAP = 500;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'userId';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstName',
        'middleName',
        'lastName',
        'email',
        'password',
        'role',
        'status',
        'points',
        'date_registered',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if this user is the first user in the system
     *
     * @return bool
     */
    public function isFirstUser(): bool
    {
        return $this->userId === 1;
    }

    /**
     * Check if this user is the first user in the system (alternative method)
     *
     * @return bool
     */
    public static function isFirstUserInSystem(): bool
    {
        return static::count() === 0;
    }

    /**
     * Get the user's full name
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        $name = $this->firstName;
        if ($this->middleName) {
            $name .= ' ' . $this->middleName;
        }
        $name .= ' ' . $this->lastName;
        return $name;
    }

    /**
     * Get the user's name (alias for full name)
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->getFullNameAttribute();
    }

    /**
     * Get all task assignments for this user
     */
    public function taskAssignments()
    {
        return $this->hasMany(TaskAssignment::class, 'userId', 'userId');
    }

    /**
     * Get all tasks assigned to this user through assignments
     */
    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_assignments', 'userId', 'taskId', 'userId', 'taskId')
                    ->withPivot('status', 'assigned_at', 'submitted_at', 'completed_at', 'photos', 'completion_notes')
                    ->withTimestamps();
    }

    /**
     * Get tasks where user is assigned and status is ongoing (assigned or submitted)
     * Excludes uncompleted tasks
     */
    public function ongoingTasks()
    {
        return $this->assignedTasks()->wherePivotIn('status', ['assigned', 'submitted']);
    }

    /**
     * Get uncompleted tasks for this user
     */
    public function uncompletedTasks()
    {
        return $this->assignedTasks()->wherePivot('status', 'uncompleted');
    }

    /**
     * Get tasks where user is assigned and status is completed
     */
    public function completedTasks()
    {
        return $this->assignedTasks()->wherePivot('status', 'completed');
    }

    /**
     * Get nominations made by this user
     */
    public function nominationsMade()
    {
        return $this->hasMany(TapNomination::class, 'FK2_nominatorId', 'userId');
    }

    /**
     * Get nominations received by this user
     */
    public function nominationsReceived()
    {
        return $this->hasMany(TapNomination::class, 'FK3_nomineeId', 'userId');
    }

    /**
     * Reward redemptions initiated by this user.
     */
    public function rewardRedemptions()
    {
        return $this->hasMany(RewardRedemption::class, 'FK2_userId', 'userId');
    }

    /**
     * Get pending nominations received by this user
     */
    public function pendingNominations()
    {
        return $this->nominationsReceived()->pending();
    }

    /**
     * Get incident reports made by this user
     */
    public function incidentReportsMade()
    {
        return $this->hasMany(UserIncidentReport::class, 'FK1_reporterId', 'userId');
    }

    /**
     * Get incident reports made against this user
     */
    public function incidentReportsReceived()
    {
        return $this->hasMany(UserIncidentReport::class, 'FK2_reportedUserId', 'userId');
    }

    /**
     * Get incident reports moderated by this user (admin only)
     */
    public function incidentReportsModerated()
    {
        return $this->hasMany(UserIncidentReport::class, 'FK4_moderatorId', 'userId');
    }

    /**
     * Get all system notifications for the user.
     */
    public function systemNotifications()
    {
        return $this->hasMany(Notification::class, 'FK1_userId', 'userId')->latest('created_at');
    }

    /**
     * Get unread system notifications for the user.
     */
    public function systemUnreadNotifications()
    {
        return $this->systemNotifications()->where('status', 'unread');
    }

    /**
     * Get unread system notifications count attribute.
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->systemUnreadNotifications()->count();
    }

    /**
     * Get the latest notifications limited.
     *
     * @param  int  $limit
     * @return \Illuminate\Support\Collection<int, \App\Models\Notification>
     */
    public function latestNotifications(int $limit = 5): Collection
    {
        return $this->systemNotifications()->limit($limit)->get();
    }

    /**
     * Add points to user, respecting the points cap.
     * Returns an array with 'added' (points actually added) and 'capped' (boolean).
     *
     * @param  int  $points
     * @return array{added: int, capped: bool}
     */
    public function addPoints(int $points): array
    {
        if ($points <= 0) {
            return ['added' => 0, 'capped' => false];
        }

        $currentPoints = $this->points;
        $maxAllowed = self::POINTS_CAP;
        
        // If already at or above cap, no points can be added
        if ($currentPoints >= $maxAllowed) {
            return ['added' => 0, 'capped' => true];
        }

        // Calculate how many points can actually be added
        $pointsToAdd = min($points, $maxAllowed - $currentPoints);
        
        // Add the points
        $this->increment('points', $pointsToAdd);
        
        // Return info about whether it was capped
        $wasCapped = $pointsToAdd < $points;
        
        return [
            'added' => $pointsToAdd,
            'capped' => $wasCapped
        ];
    }

    /**
     * Check if user has reached the points cap
     *
     * @return bool
     */
    public function hasReachedPointsCap(): bool
    {
        return $this->points >= self::POINTS_CAP;
    }

    /**
     * Get the profile picture URL
     *
     * @return string|null
     */
    public function getProfilePictureUrlAttribute(): ?string
    {
        if ($this->profile_picture && Storage::disk('public')->exists($this->profile_picture)) {
            return Storage::url($this->profile_picture);
        }
        return null;
    }

    /**
     * Get the user's initials for avatar
     *
     * @return string
     */
    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->firstName, 0, 1) . substr($this->lastName, 0, 1));
    }
}
