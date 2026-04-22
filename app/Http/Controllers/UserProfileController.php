<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Journal;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = User::findOrFail($userId);
        
        $totalJournals = Journal::where('user_id', $userId)->count();
        $journalsThisMonth = Journal::where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $receivedJournals = Journal::where('user_id', $userId)
            ->where('received_by_admin', true)
            ->count();
        $pendingJournals = $totalJournals - $receivedJournals;

        $unreadNotifications = Notification::forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = Notification::forUser($userId)->unread()->count();

        $lastActivity = DB::table('sessions')
            ->where('user_id', $userId)
            ->max('last_activity');
        $lastLoginAt = $lastActivity ? Carbon::createFromTimestamp($lastActivity) : null;
        $accountStatus = $user->email_verified_at ? 'Aktif' : 'Nonaktif';

        return view('user.profile.index', compact(
            'user',
            'totalJournals',
            'journalsThisMonth',
            'receivedJournals',
            'pendingJournals',
            'unreadNotifications',
            'unreadCount',
            'lastLoginAt',
            'accountStatus'
        ));
    }

    public function edit()
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = User::findOrFail($userId);

        $unreadNotifications = Notification::forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $unreadCount = Notification::forUser($userId)->unread()->count();

        return view('user.profile.edit', compact('user', 'unreadNotifications', 'unreadCount'));
    }

    public function update(Request $request)
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nip' => 'required|string|max:50|unique:users,nip,' . $user->id,
            'division' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->nip = $request->nip;
        $user->division = $request->division;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->birth_date = $request->birth_date;
        $user->gender = $request->gender;
        $user->save();

        // Update session
        Session::put('user_name', $user->name);
        Session::put('user_email', $user->email);

        return redirect()->route('user.profile.index')
            ->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect.')
                ->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user.profile.index')
            ->with('success', 'Password updated successfully!');
    }

    public function updatePhoto(Request $request)
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Session expired'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'profile_photo' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            
            if ($user->profile_photo && !str_starts_with($user->profile_photo, 'http')) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            $fileName = 'profile_' . $user->id . '_' . time() . '.jpg';
            $path = $file->storeAs('profile-photos', $fileName, 'public');
            
            $user->profile_photo = $path;
            $user->save();

            Session::put('user_photo', $user->profile_photo);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile photo updated successfully!',
                    'photo_url' => asset('storage/' . $path)
                ]);
            }
        }

        return redirect()->route('user.profile.index')
            ->with('success', 'Profile photo updated successfully!');
    }
}
