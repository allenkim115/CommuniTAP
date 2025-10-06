<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardRedemption extends Model
{
    use HasFactory;

    protected $primaryKey = 'redemptionId';

    protected $table = 'reward_redemption';

    protected $fillable = [
        'FK1_rewardId',
        'FK2_userId',
        'redemption_date',
        'status',
        'approval_date',
        'admin_notes',
    ];

    protected $casts = [
        'redemption_date' => 'datetime',
        'approval_date' => 'datetime',
    ];

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'FK1_rewardId', 'rewardId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'FK2_userId', 'userId');
    }
}


