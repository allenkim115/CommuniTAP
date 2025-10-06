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
        if ($redemption->status !== 'pending') {
            return back()->with('error', 'Redemption already processed.');
        }

        $redemption->status = 'approved';
        $redemption->approval_date = now();
        $redemption->save();

        return back()->with('status', 'Redemption approved.');
    }

    public function reject(RewardRedemption $redemption)
    {
        if ($redemption->status !== 'pending') {
            return back()->with('error', 'Redemption already processed.');
        }

        // Return quantity to reward
        $reward = $redemption->reward;
        if ($reward) {
            $reward->increment('QTY');
        }

        // Refund points to user
        $user = $redemption->user;
        if ($user && $reward) {
            $user->points += $reward->points_cost;
            $user->save();
        }

        $redemption->status = 'rejected';
        $redemption->approval_date = now();
        $redemption->save();

        return back()->with('status', 'Redemption rejected and reversed.');
    }
}


