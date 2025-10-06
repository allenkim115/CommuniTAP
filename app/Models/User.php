<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
     */
    public function ongoingTasks()
    {
        return $this->assignedTasks()->wherePivotIn('status', ['assigned', 'submitted']);
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
}
