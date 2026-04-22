<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('user_id')) {
            $remember = $request->cookie('remember_me');
            if ($remember) {
                $u = User::where('remember_token', $remember)->first();
                if ($u) {
                    Auth::login($u, true);
                    Session::put('user_id', $u->id);
                    Session::put('user_name', $u->name);
                    Session::put('user_email', $u->email);
                    Session::put('is_admin', (bool) $u->is_admin);
                    Session::put('role', $u->role ?? ($u->is_admin ? 'Admin' : 'User'));
                    Session::put('admin_profile_photo', $u->is_admin ? $u->profile_photo : null);
                    Session::put('theme', $u->theme ?? 'light');
                    Session::put('user_photo', $u->profile_photo);
                }
            }
            if (!Session::has('user_id')) {
                return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
            }
        }

        $userId = Session::get('user_id');
        $user = User::find($userId);
        if (!$user || !$user->is_admin) {
            return redirect()->route('login')->with('error', 'Anda tidak memiliki akses ke halaman admin.');
        }

        return $next($request);
    }
}
