<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SessionAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_id')) {
            $remember = $request->cookie('remember_me');
            if ($remember) {
                $user = User::where('remember_token', $remember)->first();
                if ($user) {
                    Auth::login($user, true);
                    Session::put('user_id', $user->id);
                    Session::put('user_name', $user->name);
                    Session::put('user_email', $user->email);
                    Session::put('is_admin', (bool) $user->is_admin);
                    Session::put('role', $user->role ?? ($user->is_admin ? 'Admin' : 'User'));
                    Session::put('admin_profile_photo', $user->is_admin ? $user->profile_photo : null);
                    Session::put('theme', $user->theme ?? 'light');
                    Session::put('user_photo', $user->profile_photo);
                    return $next($request);
                }
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
