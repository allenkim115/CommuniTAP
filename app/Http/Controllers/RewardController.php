<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\RewardRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::active()
            ->where('QTY', '>', 0)
            ->orderBy('reward_name')
            ->get();
        return view('rewards.index', compact('rewards'));
    }

    public function redeem(Request $request, Reward $reward)
    {
        $user = Auth::user();

        if (!$reward->isAvailable()) {
            return back()->with('error', 'This reward is not available.');
        }

        if ($user->points < $reward->points_cost) {
            return back()->with('error', 'Not enough points to redeem.');
        }

        $redemption = RewardRedemption::create([
            'FK1_rewardId' => $reward->rewardId,
            'FK2_userId' => $user->userId,
            'redemption_date' => now(),
            'status' => 'pending',
        ]);

        // Reserve quantity immediately to avoid oversubscription
        $reward->decrement('QTY');

        // Deduct points immediately (can be reverted if rejected by admin)
        $user->points = max(0, $user->points - $reward->points_cost);
        $user->save();

        return redirect()->route('rewards.mine')->with('status', 'Redemption requested. Awaiting admin approval.');
    }

    public function myRedemptions()
    {
        $redemptions = RewardRedemption::with('reward')
            ->where('FK2_userId', Auth::user()->userId)
            ->latest()
            ->get();

        return view('rewards.mine', compact('redemptions'));
    }
}


