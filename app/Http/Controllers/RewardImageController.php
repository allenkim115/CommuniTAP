<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Support\Facades\Storage;

class RewardImageController extends Controller
{
    public function show(Reward $reward)
    {
        if (!$reward->image_path || !Storage::disk('public')->exists($reward->image_path)) {
            return redirect()->route('rewards.index')
                ->with('error', 'The reward image is not available.');
        }

        return Storage::disk('public')->response($reward->image_path);
    }
}


