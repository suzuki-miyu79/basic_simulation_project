<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Status;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:191', 'unique:' . User::class],
            'password' => ['required', 'confirmed', 'max:191', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Statusレコードを同時に作成
        Status::create([
            'user_id' => $user->id,
            'work_started' => false,
            'work_ended' => false,
            'break_started' => false,
            'break_ended' => false,
        ]);

        event(new Registered($user));

        return redirect('/');
    }
}
