<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('lastName')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return redirect()->route('admin.users.index')->with('error', 'Admins cannot create new users.');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('admin.users.index')->with('error', 'Admins cannot create new users.');
    }

    public function edit(User $user)
    {
        return redirect()->route('admin.users.show', $user)->with('error', 'Admins cannot edit user profiles. Viewing only.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        return redirect()->route('admin.users.show', $user)->with('error', 'Admins cannot edit user profiles.');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function suspend(User $user): RedirectResponse
    {
        $user->update(['status' => 'suspended']);
        return back()->with('status', 'User suspended successfully.');
    }

    public function reactivate(User $user): RedirectResponse
    {
        $user->update(['status' => 'active']);
        return back()->with('status', 'User reactivated successfully.');
    }

    
}


