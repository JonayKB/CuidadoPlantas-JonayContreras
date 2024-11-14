<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repository\RolRepository;
use App\Repository\UserRepository;
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        /*
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verified' => false,
        ]);
        */
        $userRepository = new UserRepository();
        $rolRepository = new RolRepository();
        if (app()->runningUnitTests()) {
            $userRepository->setTestMode();
            $rolRepository->setTestMode();
        }
        $user = new User($request->all());
        $user = $userRepository->save($user);
        $defaultRol = $rolRepository->findById(1);
        $user->roles()->attach($defaultRol);
        $user = $userRepository->save($user);



        event(new Registered($user));



        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
