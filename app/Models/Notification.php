<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notificationId';

    protected $fillable = [
        'FK1_userId',
        'type',
        'message',
        'status',
        'created_date',
        'data',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'data' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Notification $notification): void {
            if (!$notification->created_date) {
                $notification->created_date = now();
            }

            if (!$notification->status) {
                $notification->status = 'unread';
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'FK1_userId', 'userId');
    }

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function markAsRead(): void
    {
        if ($this->status !== 'read') {
            $this->status = 'read';
            $this->save();
        }
    }
}

