<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Notification as DbNotification;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Send Web Push when a database notification is created, if enabled.
        DbNotification::created(function (DbNotification $n) {
            try {
                $user = User::find($n->user_id);
                if (!$user) return;

                // Ensure has subscription
                if (method_exists($user, 'pushSubscriptions') && $user->pushSubscriptions()->count() === 0) {
                    return;
                }

                // Check preferences
                $pref = NotificationPreference::where('user_id', $user->id)->first();
                $type = strtolower($n->type ?? '');
                $allowed = true;
                if ($pref) {
                    $map = [
                        'received' => $pref->approved,
                        'revised' => $pref->revised,
                        'rejected' => $pref->rejected,
                        'feedback' => $pref->feedback,
                        'new_journal' => $pref->new_journal,
                        'progress' => $pref->new_journal,
                        'new_journal_batch' => $pref->new_journal,
                    ];
                    if (array_key_exists($type, $map)) {
                        $allowed = (bool)$map[$type];
                    }
                }
                if (!$allowed) return;

                // Use webpush channel only if installed
                if (class_exists(\App\Notifications\JournalPushNotification::class)) {
                    $url = $n->journal_id ? url('/user/journals/'.$n->journal_id) : url('/');
                    $title = 'Notifikasi Jurnal';
                    $body = $n->message ?? ucfirst($n->type ?? 'Notifikasi baru');
                    $user->notify(new \App\Notifications\JournalPushNotification($title, $body, $url, [
                        'journal_id' => $n->journal_id,
                        'type' => $n->type,
                    ]));
                }
            } catch (\Throwable $e) {
                Log::debug('Push notification skipped: '.$e->getMessage());
            }
        });
    }
}
