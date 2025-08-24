<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class VendorLoginController extends Controller
{
    public function showLoginForm()
    {
        // kalau sudah login, arahkan langsung
        if (Auth::check() && Auth::user()->role === 'vendor') {
            return redirect()->route('vendor.documents.index');
        }
        return view('auth.vendor-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'npwp'     => ['required','string','min:8'],
            'password' => ['required','string','min:6'],
            'remember' => ['nullable','boolean'],
        ]);

        // normalize NPWP -> digit-only
        $npwp = preg_replace('/\D/','', $request->npwp);
        $remember = $request->boolean('remember');

        // Throttle: key per IP + NPWP
        $key = 'vendor-login:'.Str::lower($request->ip()).':'.$npwp;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withErrors(['npwp'=>"Terlalu banyak percobaan. Coba lagi dalam {$seconds}s."])
                ->withInput($request->only('npwp'));
        }

        // Attempt pakai npwp + role vendor
        $ok = Auth::attempt([
            'npwp'     => $npwp,
            'role'     => 'vendor',
            'password' => $request->password,
        ], $remember);

        if (!$ok) {
            RateLimiter::hit($key, 60); // block bertambah 60 detik per gagal
            return back()
                ->withErrors(['npwp'=>'NPWP atau password salah.'])
                ->withInput($request->only('npwp'));
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        return redirect()->route('vendor.documents.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('vendor.login.form');
    }
}
