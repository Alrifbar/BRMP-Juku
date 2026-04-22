<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Journal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CleanupUserData extends Command
{
    protected $signature = 'cleanup:user-data {--force : Skip confirmation}';
    protected $description = 'Delete all user journals and accounts with backup';

    public function handle()
    {
        $this->info('=== BRMP JurnalKu - Data Cleanup ===');

        // Create backup
        $this->createBackup();

        // Show current data
        $journalCount = Journal::count();
        $userCount = User::where('is_admin', false)->count();
        $notificationCount = DB::table('notifications')->count();

        $this->info("\n📊 Current Data Count:");
        $this->line("   Journals: {$journalCount}");
        $this->line("   Non-admin Users: {$userCount}");
        $this->line("   Notifications: {$notificationCount}");

        // Confirmation
        if (!$this->option('force')) {
            $this->warn("\n⚠️  WARNING: This will permanently delete ALL user data!");
            $this->line("   - All journals ({$journalCount} records)");
            $this->line("   - All non-admin users ({$userCount} records)");
            $this->line("   - All notifications ({$notificationCount} records)");
            
            if (!$this->confirm('\nDo you want to continue?')) {
                $this->info('❌ Operation cancelled.');
                return 0;
            }
        }

        // Delete data
        $this->deleteData();

        $this->info('✅ Cleanup completed successfully!');
        return 0;
    }

    private function createBackup()
    {
        $this->info('🔄 Creating backup...');
        
        $backupDir = storage_path('app/backup_' . date('Y-m-d_H-i-s'));
        File::makeDirectory($backupDir, 0755, true);

        // Backup journals
        $journals = Journal::with('user')->get()->map(function ($journal) {
            return [
                'id' => $journal->id,
                'user_id' => $journal->user_id,
                'user_name' => $journal->user->name ?? 'Unknown',
                'user_email' => $journal->user->email ?? 'Unknown',
                'no' => $journal->no,
                'tanggal' => $journal->tanggal,
                'title' => $journal->title,
                'uraian_pekerjaan' => $journal->uraian_pekerjaan,
                'dokumen_pekerjaan' => $journal->dokumen_pekerjaan,
                'penilai_kasubang' => $journal->penilai_kasubang,
                'penilai_tu' => $journal->penilai_tu,
                'penilai_katimker' => $journal->penilai_katimker,
                'jenis_katimker' => $journal->jenis_katimker,
                'tags' => $journal->tags,
                'is_private' => $journal->is_private,
                'received_by_admin' => $journal->received_by_admin,
                'received_at' => $journal->received_at,
                'admin_checks' => $journal->admin_checks,
                'created_at' => $journal->created_at,
                'updated_at' => $journal->updated_at,
            ];
        });

        File::put($backupDir . '/journals_backup.json', $journals->toJson(JSON_PRETTY_PRINT));

        // Backup users
        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_admin' => $user->is_admin,
                'profile_photo' => $user->profile_photo,
                'division' => $user->division,
                'phone' => $user->phone,
                'address' => $user->address,
                'birth_date' => $user->birth_date,
                'gender' => $user->gender,
                'email_verified_at' => $user->email_verified_at,
                'task_completed' => $user->task_completed,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        });

        File::put($backupDir . '/users_backup.json', $users->toJson(JSON_PRETTY_PRINT));

        $this->info("✅ Backup created at: {$backupDir}");
        $this->line("📊 Journals backed up: {$journals->count()}");
        $this->line("👥 Users backed up: {$users->count()}");
    }

    private function deleteData()
    {
        $this->info('🗑️  Starting deletion process...');

        DB::transaction(function () {
            // Delete notifications
            $this->line('   Deleting notifications...');
            DB::table('notifications')->delete();

            // Delete journals
            $this->line('   Deleting journals...');
            Journal::truncate();

            // Delete non-admin users
            $this->line('   Deleting non-admin users...');
            User::where('is_admin', false)->delete();
        });

        // Verify deletion
        $remainingJournals = Journal::count();
        $remainingUsers = User::where('is_admin', false)->count();
        $remainingNotifications = DB::table('notifications')->count();

        $this->info("\n📊 Final Data Count:");
        $this->line("   Journals: {$remainingJournals}");
        $this->line("   Non-admin Users: {$remainingUsers}");
        $this->line("   Notifications: {$remainingNotifications}");
    }
}
