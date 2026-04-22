<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Journal;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PresentationSeeder extends Seeder
{
    public function run()
    {
        // Create sample admin user if not exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin System',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_admin' => true,
            ]);
        }

        $extraAdmins = [
            ['name' => 'Admin One', 'email' => 'admin1@example.com'],
            ['name' => 'Admin Two', 'email' => 'admin2@example.com'],
            ['name' => 'Admin Three', 'email' => 'admin3@example.com'],
            ['name' => 'Admin Four', 'email' => 'admin4@example.com'],
            ['name' => 'Admin Five', 'email' => 'admin5@example.com'],
        ];
        foreach ($extraAdmins as $adm) {
            if (!User::where('email', $adm['email'])->exists()) {
                User::create([
                    'name' => $adm['name'],
                    'email' => $adm['email'],
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'is_admin' => true,
                ]);
            }
        }

        // Create sample pegawai users if not exists
        $pegawaiData = [
            ['name' => 'Ahmad Wijaya', 'email' => 'ahmad@company.com', 'division' => 'IT'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@company.com', 'division' => 'HR'],
            ['name' => 'Budi Santoso', 'email' => 'budi@company.com', 'division' => 'Finance'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@company.com', 'division' => 'Marketing'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko@company.com', 'division' => 'Operations'],
            ['name' => 'Fitri Handayani', 'email' => 'fitri@company.com', 'division' => 'Sales'],
            ['name' => 'Hadi Kurniawan', 'email' => 'hadi@company.com', 'division' => 'Customer Service'],
            ['name' => 'Indah Permata', 'email' => 'indah@company.com', 'division' => 'Procurement'],
        ];

        $users = [];
        foreach ($pegawaiData as $data) {
            if (!User::where('email', $data['email'])->exists()) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password'),
                    'role' => 'pegawai',
                    'is_admin' => false,
                    'division' => $data['division'],
                ]);
                $users[] = $user;
            } else {
                $users[] = User::where('email', $data['email'])->first();
            }
        }

        // Create sample journals for the past 30 days
        $journalTemplates = [
            'Pengerjaan fitur authentication system',
            'Meeting dengan client untuk diskusi project',
            'Review dan testing module payment',
            'Optimasi database query untuk performance',
            'Update documentation API',
            'Bug fixing pada aplikasi mobile',
            'Design UI/UX untuk halaman dashboard',
            'Integrasi payment gateway',
            'Backup dan maintenance server',
            'Training tim untuk teknologi baru',
            'Analisis requirement untuk project baru',
            'Presentasi progress project ke management',
            'Code review dan refactoring',
            'Setup development environment',
            'Deployment ke staging server',
        ];

        foreach ($users as $user) {
            // Create 5-15 journals per user for the past 30 days
            $journalCount = rand(5, 15);
            
            for ($i = 0; $i < $journalCount; $i++) {
                $daysAgo = rand(0, 30);
                $journalDate = Carbon::now()->subDays($daysAgo);
                
                Journal::create([
                    'user_id' => $user->id,
                    'no' => 'JRN-' . date('Ymd') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                    'title' => 'Daily Report - ' . $journalDate->format('d M Y'),
                    'uraian_pekerjaan' => $journalTemplates[array_rand($journalTemplates)],
                    'content' => 'Melakukan pekerjaan rutin harian sesuai dengan tugas yang diberikan. Progress saat ini berjalan dengan baik dan sesuai timeline yang telah ditetapkan.',
                    'created_at' => $journalDate,
                    'updated_at' => $journalDate,
                    'received_by_admin' => rand(0, 1) === 1, // Randomly mark some as received
                ]);
            }
        }

        $this->command->info('Sample data for presentation created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Users: ahmad@company.com / password (and others)');
    }
}
