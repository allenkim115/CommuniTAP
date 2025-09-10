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
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'firstName' => ['required','string','max:100'],
            'middleName' => ['nullable','string','max:100'],
            'lastName' => ['required','string','max:100'],
            'email' => ['required','email','max:100','unique:users,email'],
            'password' => ['required','string','min:8'],
            'role' => ['required','in:user,admin'],
        ]);

        $validated['date_registered'] = now()->toDateString();
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('admin.users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'firstName' => ['required','string','max:100'],
            'middleName' => ['nullable','string','max:100'],
            'lastName' => ['required','string','max:100'],
            'email' => ['required','email','max:100','unique:users,email,'.$user->userId.',userId'],
            'role' => ['required','in:user,admin'],
        ]);

        $user->update($validated);
        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
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


