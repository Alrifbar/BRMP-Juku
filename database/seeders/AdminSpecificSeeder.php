<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSpecificSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Tigia Eloka Kailaku, S.Si., M.M.',
                'nip' => '198809032025212053',
                'division' => 'Penata Layanan Operasional',
                'email' => 'tigiaadminplo@brmpph.com',
                'password' => 'brmpph#22adminPLO'
            ],
            [
                'name' => 'Rini Rospiani Iswari',
                'nip' => '198003052025212039',
                'division' => 'Operator Layanan Operasional',
                'email' => 'riniadminolo@brmpph.com',
                'password' => 'brmpph#22adminOLO'
            ],
        ];

        foreach ($admins as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'nip' => $data['nip'],
                    'division' => $data['division'],
                    'password' => Hash::make($data['password']),
                    'role' => 'admin',
                    'is_admin' => true,
                    'provider' => 'local'
                ]
            );
        }
    }
}
