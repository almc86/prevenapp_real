<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        $roleName = optional($user->role)->name;

        // Admins live in THIS Laravel app → native session, straight to the panel.
        if ($roleName === 'administrador') {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Every other role belongs to the React/NestJS app. We don't keep a
        // Laravel session for them — instead we mint a single-use handoff code
        // and bounce them to the React app, which exchanges it for its own JWT.
        $code = Str::random(64);

        DB::table('sso_handoff_codes')->insert([
            'code' => $code,
            'user_id' => $user->id,
            'expires_at' => now()->addSeconds(60),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $frontendUrl = rtrim(config('app.frontend_url'), '/');

        return redirect()->away($frontendUrl . '/auth/callback?code=' . $code);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
