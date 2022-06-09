<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        crypto_secrets();
        return view('auth.login')
        ->with('crypto_salt', session('crypto_salt'))
        ->with('crypto_iv', session('crypto_iv'))
        ->with('crypto_key', session('crypto_key'))
        ->with('crypto_key_size', session('crypto_key_size'))
        ->with('crypto_iterations', session('crypto_iterations'));
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (! auth()->user()->is_active) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'email' => trans('Your account is blocked. Please contact to site administrator'),
            ]);
        }

        set_auth_permissions();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
