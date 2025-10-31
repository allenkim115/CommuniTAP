<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\RewardRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        // Generate a coupon code and auto-approve the redemption
        $couponCode = strtoupper(Str::random(10));

        $redemption = RewardRedemption::create([
            'FK1_rewardId' => $reward->rewardId,
            'FK2_userId' => $user->userId,
            'redemption_date' => now(),
            'status' => 'approved',
            'approval_date' => now(),
            'admin_notes' => 'Coupon: ' . $couponCode,
        ]);

        // Reserve quantity immediately to avoid oversubscription
        $reward->decrement('QTY');

        // Deduct points immediately (can be reverted if rejected by admin)
        $user->points = max(0, $user->points - $reward->points_cost);
        $user->save();

        return redirect()->route('rewards.mine')->with('status', 'Redemption successful. Your coupon: ' . $couponCode);
    }

    public function myRedemptions()
    {
        $redemptions = RewardRedemption::with('reward')
            ->where('FK2_userId', Auth::user()->userId)
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('rewards.mine', compact('redemptions'));
    }
}


