<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Support\Facades\Storage;

class RewardImageController extends Controller
{
    public function show(Reward $reward)
    {
        if (!$reward->image_path || !Storage::disk('public')->exists($reward->image_path)) {
            abort(404);
        }

        return Storage::disk('public')->response($reward->image_path);
    }
}


