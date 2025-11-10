<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardRedemption;
use App\Models\Reward;

class RewardRedemptionController extends Controller
{
    public function index()
    {
        $redemptions = RewardRedemption::with(['reward', 'user'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.rewards.redemptions', compact('redemptions'));
    }

    public function approve(RewardRedemption $redemption)
    {
        return back()->with('status', 'Redemptions are now auto-approved. No manual action required.');
    }

    public function reject(RewardRedemption $redemption)
    {
        return back()->with('error', 'Manual rejection is disabled. Redemptions are fulfilled automatically.');
    }
}


