<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\RewardRedemption;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RewardController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

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

        // Check if user has already redeemed this specific reward today
        $alreadyRedeemedToday = RewardRedemption::where('FK2_userId', $user->userId)
            ->where('FK1_rewardId', $reward->rewardId)
            ->whereDate('redemption_date', Carbon::today())
            ->exists();

        if ($alreadyRedeemedToday) {
            return back()->with('error', 'You have already redeemed this reward today. Please try again tomorrow or choose a different reward.');
        }

        // Check daily redemption limit (2 rewards per day)
        $todayRedemptions = RewardRedemption::where('FK2_userId', $user->userId)
            ->whereDate('redemption_date', Carbon::today())
            ->count();

        if ($todayRedemptions >= 2) {
            return back()->with('error', 'You have reached the daily redemption limit of 2 rewards. Please try again tomorrow.');
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

        $this->notificationService->notify(
            $user,
            'reward_redeemed',
            "You redeemed \"{$reward->reward_name}\".",
            [
                'url' => route('rewards.mine'),
                'description' => "Coupon code: {$couponCode}. Present this to claim your reward.",
            ]
        );

        $admins = \App\Models\User::where('role', 'admin')->where('status', 'active')->get(['userId']);
        if ($admins->isNotEmpty()) {
            $this->notificationService->notifyMany(
                $admins,
                'reward_claim_submitted',
                "{$user->firstName} {$user->lastName} claimed the reward \"{$reward->reward_name}\".",
                [
                    'url' => route('admin.rewards.index'),
                    'description' => "Coupon code: {$couponCode}. Ensure fulfilment is tracked.",
                ]
            );
        }

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


