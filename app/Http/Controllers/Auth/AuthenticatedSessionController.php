<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        if ($user->hasRole('kasir')) {
            // Cek apakah sudah absen hari ini
            $today = \Carbon\Carbon::today();
            $attendance = \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();

            if (!$attendance) {
                return redirect()->route('attendance.index')->with('success', 'Halo! Silakan absen masuk dulu ya.');
            }
        }

        // PERBAIKAN: Tambahkan pesan 'success' saat redirect
        return redirect()->intended(route('dashboard', absolute: false))
            ->with('success', 'Selamat Datang! Anda berhasil masuk.');
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
