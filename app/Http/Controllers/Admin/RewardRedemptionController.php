<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RewardRedemption;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardRedemptionController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    public function index(Request $request)
    {
        $activeTab = $request->get('status', 'pending') === 'claimed' ? 'claimed' : 'pending';
        $search = trim($request->get('search', ''));

        $baseQuery = RewardRedemption::with(['reward', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where(function ($nameQuery) use ($search) {
                        $nameQuery->where('firstName', 'like', "%{$search}%")
                            ->orWhere('lastName', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere(DB::raw("CONCAT(firstName, ' ', lastName)"), 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('created_at', 'desc');

        $claimedQuery = (clone $baseQuery)->where('status', 'claimed');
        $pendingQuery = (clone $baseQuery)->where('status', '!=', 'claimed');

        $tabCounts = [
            'pending' => (clone $pendingQuery)->count(),
            'claimed' => (clone $claimedQuery)->count(),
        ];

        $redemptions = ($activeTab === 'claimed' ? $claimedQuery : $pendingQuery)
            ->paginate(20)
            ->withQueryString();

        return view('admin.rewards.redemptions', compact('redemptions', 'activeTab', 'tabCounts', 'search'));
    }

    public function approve(RewardRedemption $redemption)
    {
        return back()->with('status', 'Redemptions are now auto-approved. No manual action required.');
    }

    public function reject(RewardRedemption $redemption)
    {
        return back()->with('error', 'Manual rejection is disabled. Redemptions are fulfilled automatically.');
    }

    public function claim(RewardRedemption $redemption)
    {
        $redemption->loadMissing('reward', 'user');

        if ($redemption->status === 'claimed') {
            return back()->with('status', 'This reward redemption has already been marked as claimed.');
        }

        $claimer = Auth::user();
        $claimedNote = 'Claimed in person on ' . now()->format('M d, Y g:i A');

        if ($claimer) {
            $fullName = trim(($claimer->firstName ?? '') . ' ' . ($claimer->lastName ?? ''));
            $claimedNote .= ' by ' . ($claimer->name ?? $fullName ?: $claimer->email);
        }

        $redemption->status = 'claimed';
        $redemption->admin_notes = $redemption->admin_notes
            ? $redemption->admin_notes . PHP_EOL . $claimedNote
            : $claimedNote;
        $redemption->save();

        if ($redemption->user) {
            $rewardName = $redemption->reward?->reward_name ?? 'your reward';
            $description = $redemption->coupon_code
                ? "Barangay verified coupon code {$redemption->coupon_code}."
                : 'Barangay verified your reward claim.';

            $this->notificationService->notify(
                $redemption->user,
                'reward_claimed',
                "Reward claimed: \"{$rewardName}\".",
                [
                    'url' => route('rewards.mine'),
                    'description' => $description,
                ]
            );
        }

        return back()->with('status', 'Reward marked as claimed.');
    }
}


