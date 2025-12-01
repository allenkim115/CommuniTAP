<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.rewards.index', compact('rewards'));
    }

    public function create()
    {
        return view('admin.rewards.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sponsor_name' => ['required','string','min:10','max:50'],
            'reward_name' => ['required','string','min:10','max:100'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'description' => ['required','string','min:10'],
            'points_cost' => ['required','integer','min:1'],
            'QTY' => ['required','integer','min:0'],
            'status' => ['nullable','in:active,inactive'],
        ]);

        $data['status'] = $data['status'] ?? 'active';
        $data = $this->enforceInventoryStatus($data);
        $data['created_date'] = now();
        $data['last_update_date'] = now();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('rewards', 'public');
            $data['image_path'] = $path;
        }

        Reward::create($data);

        return redirect()->route('admin.rewards.index')->with('status', "Reward '{$request->reward_name}' has been created successfully and is now available for users to redeem.");
    }

    public function edit(Reward $reward)
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    public function update(Request $request, Reward $reward)
    {
        $data = $request->validate([
            'sponsor_name' => ['required','string','min:10','max:50'],
            'reward_name' => ['required','string','min:10','max:100'],
            'image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'description' => ['required','string','min:10'],
            'points_cost' => ['required','integer','min:1'],
            'QTY' => ['required','integer','min:0'],
            'status' => ['required','in:active,inactive'],
        ]);

        $data = $this->enforceInventoryStatus($data);
        $data['last_update_date'] = now();
        if ($request->hasFile('image')) {
            if ($reward->image_path) {
                Storage::disk('public')->delete($reward->image_path);
            }
            $path = $request->file('image')->store('rewards', 'public');
            $data['image_path'] = $path;
        }

        $reward->update($data);

        return redirect()->route('admin.rewards.index')->with('status', "Reward '{$reward->reward_name}' has been updated successfully. All changes have been saved.");
    }

    public function destroy(Reward $reward)
    {
        $reward->delete();
        $rewardName = $reward->reward_name;
        return redirect()->route('admin.rewards.index')->with('status', "Reward '{$rewardName}' has been deleted successfully from the system.");
    }

    private function enforceInventoryStatus(array $data): array
    {
        if (array_key_exists('QTY', $data) && (int) $data['QTY'] === 0) {
            $data['status'] = 'inactive';
        }

        return $data;
    }
}


