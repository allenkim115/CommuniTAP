<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Debug: Log the incoming request data
        \Log::info('Registration request data:', $request->all());
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request headers: ' . json_encode($request->headers->all()));
        
        $request->validate([
            'firstName' => ['required', 'string', 'max:100'],
            'middleName' => ['nullable', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'lowercase', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Debug: Log the validated data
        \Log::info('firstName value: ' . $request->input('firstName'));
        \Log::info('lastName value: ' . $request->input('lastName'));
        \Log::info('middleName value: ' . $request->input('middleName'));

        // Check if this is the first user - if so, make them admin
        $isFirstUser = User::count() === 0;
        
        if ($isFirstUser) {
            \Log::info('First user registration - assigning admin role to: ' . $request->email);
        } else {
            \Log::info('Regular user registration - assigning user role to: ' . $request->email);
        }
        
        $userData = [
            'firstName' => $request->firstName,
            'middleName' => $request->middleName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $isFirstUser ? 'admin' : 'user', // First user becomes admin, others become regular users
            'status' => 'active', // Default status
            'points' => 0, // Default points
            'date_registered' => now(),
        ];

        // Debug: Log the user data being created
        \Log::info('User data to create:', array_merge($userData, ['password' => 'HIDDEN']));

        $user = User::create($userData);

        event(new Registered($user));

        // For admins (first user or any admin), auto-verify their email and log them in
        // Admins don't need email verification
        if ($user->isAdmin()) {
            $user->markEmailAsVerified();
            Auth::login($user);
            session()->flash('status', 'Welcome! You have been registered as the system administrator.');
            return redirect(route('dashboard', absolute: false));
        }

        // For regular users, send verification email and redirect to verification notice
        Auth::login($user);
        session()->flash('status', 'Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?');
        return redirect(route('verification.notice'));
    }
}
