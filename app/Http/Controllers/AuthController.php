<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Mencari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Sesuai request: Tidak ada auto-register
                return redirect()->route('login')->with('error', 'Akun Gmail Anda belum terdaftar. Silakan hubungi Admin.');
            }

            // Validasi provider: Harus 'google'
            if ($user->provider !== 'google') {
                return redirect()->route('login')->with('error', 'Gunakan login manual untuk akun ini.');
            }

            // Update google_id dan avatar jika ada perubahan
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            // Login user secara langsung
            Auth::login($user, true);

            // Set session data untuk compatibility
            $this->setSessionData($user);

            // Set remember cookie
            $token = Str::random(60);
            $user->remember_token = $token;
            $user->save();
            $cookie = cookie('remember_me', $token, 60 * 24 * 90);

            return redirect()->to($this->resolveRedirectPath($user))->with('success', 'Login Google Berhasil!')->withCookie($cookie);

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }

    public function showLoginForm(Request $request)
    {
        $remember = $request->cookie('remember_me');
        if ($remember) {
            $user = User::where('remember_token', $remember)->first();
            if ($user) {
                Auth::login($user, true);
                $this->setSessionData($user);
                return redirect()->to($this->resolveRedirectPath($user));
            }
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $user = User::where('email', $credentials['email'])->first();

            if ($user) {
                // Validasi provider: Harus 'local'
                if ($user->provider === 'google') {
                    return back()->with('error', 'Gunakan login Google untuk akun ini.')->withInput();
                }

                if (Hash::check($credentials['password'], $user->password)) {
                    $remember = $request->boolean('remember', true);
                    Auth::login($user, $remember);
                    $this->setSessionData($user);
                    if ($remember) {
                        $token = Str::random(60);
                        $user->remember_token = $token;
                        $user->save();
                        $cookie = cookie('remember_me', $token, 60 * 24 * 90);
                        return redirect()->to($this->resolveRedirectPath($user))->with('success', 'Welcome back!')->withCookie($cookie);
                    }

                    return redirect()->to($this->resolveRedirectPath($user))->with('success', 'Welcome back!');
                }
            }

            return back()->with('error', 'Email atau password salah.')->withInput();
                
        } catch (\Exception $e) {
            return back()->with('error', 'Login failed. Please try again.')->withInput();
        }
    }

    private function setSessionData($user)
    {
        Session::flush();
        Session::put('user_id', $user->id);
        Session::put('user_name', $user->name);
        Session::put('user_email', $user->email);
        Session::put('is_admin', (bool) $user->is_admin);
        Session::put('role', $user->role ?? ($user->is_admin ? 'Admin' : 'User'));
        Session::put('admin_profile_photo', $user->is_admin ? $user->profile_photo : null);
        Session::put('user_photo', $user->profile_photo);
        Session::put('theme', $user->theme ?? 'light');
        Session::regenerate();
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->remember_token = null;
            $user->save();
        }

        Session::flush();
        $request->session()->regenerateToken();
        $request->session()->invalidate();

        $forget = cookie()->forget('remember_me');
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.')->withCookie($forget);
    }

    private function resolveRedirectPath(\App\Models\User $user): string
    {
        $pref = $user->default_page ?: 'dashboard';
        if ($user->is_admin) {
            // Admin: gunakan dashboard atau notifikasi
            if ($pref === 'notifications') {
                return route('admin.notifications.index');
            }
            return route('admin.dashboard');
        }
        // User
        return match ($pref) {
            'journals' => route('user.journals.index'),
            'notifications' => route('user.notifications.index'),
            default => route('user.home'),
        };
    }
}
