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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:'.User::class],
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

        Auth::login($user);

        // Set a session message about the user's role
        if ($isFirstUser) {
            session()->flash('status', 'Welcome! You have been registered as the system administrator.');
        } else {
            session()->flash('status', 'Welcome! Your account has been created successfully.');
        }

        return redirect(route('dashboard', absolute: false));
    }
}
