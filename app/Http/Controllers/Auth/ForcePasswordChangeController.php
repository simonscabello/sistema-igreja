<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ForcePasswordChangeController extends Controller
{
    /**
     * Show the force password change form.
     */
    public function show(Request $request): Response|RedirectResponse
    {
        // If user is not authenticated, redirect to login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // If user doesn't need to change password, redirect to dashboard
        if (! Auth::user()->must_change_password) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Auth/ForcePasswordChange');
    }

    /**
     * Handle the force password change request.
     */
    public function update(ChangePasswordRequest $request): RedirectResponse
    {
        // If user is not authenticated, redirect to login
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        // If user doesn't need to change password, redirect to dashboard
        if (! $user->must_change_password) {
            return redirect()->route('dashboard');
        }

        // Update password and remove the must_change_password flag
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')->with('success', 'Senha alterada com sucesso! Agora você pode usar o sistema normalmente.');
    }
}
