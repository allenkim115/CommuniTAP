<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $primaryKey = 'rewardId';

    protected $fillable = [
        'sponsor_name',
        'reward_name',
        'image_path',
        'description',
        'points_cost',
        'QTY',
        'status',
        'created_date',
        'last_update_date',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'last_update_date' => 'datetime',
        'points_cost' => 'integer',
        'QTY' => 'integer',
    ];

    public function redemptions()
    {
        return $this->hasMany(RewardRedemption::class, 'FK1_rewardId', 'rewardId');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'active' && $this->QTY > 0;
    }
}


