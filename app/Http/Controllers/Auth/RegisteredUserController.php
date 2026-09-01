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
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\s\+\-\(\)\.]*$/'],
            'birth_date' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(13)->format('Y-m-d'),
                'after:' . now()->subYears(120)->format('Y-m-d'),
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'birth_date.required' => 'La date de naissance est obligatoire.',
            'birth_date.date' => 'La date de naissance doit être une date valide.',
            'birth_date.before_or_equal' => 'Vous devez avoir au moins 13 ans pour créer un compte.',
            'phone.regex' => 'Le numéro de téléphone n\'a pas un format valide.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
