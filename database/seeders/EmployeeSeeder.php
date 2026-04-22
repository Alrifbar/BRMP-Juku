<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            ['Eva Yuliana, S.Kom.', '198801102025212059', 'Penata Layanan Operasional'],
            ['Tigia Eloka Kailaku, S.Si., M.M.', '198809032025212053', 'Penata Layanan Operasional'],
            ['Tri Yogi Adi Wigati, S.Psi.', '199504162025212076', 'Penata Layanan Operasional'],
            ['Rini Rospiani Iswari', '198003052025212039', 'Operator Layanan Operasional'],
            ['Ahmad Ridwan', '198012132025211039', 'Operator Layanan Operasional'],
            ['Ahmad Sofyan', '197702072025211040', 'Operator Layanan Operasional'],
            ['Marjuki', '198805082025211019', 'Operator Layanan Operasional'],
            ['Toni Hendrik', '197308012025211037', 'Operator Layanan Operasional'],
            ['Sumardi', '198808032025211097', 'Operator Layanan Operasional'],
            ['Hermawan', '198605272025211069', 'Operator Layanan Operasional'],
            ['Widiyatno', '198708222025211051', 'Operator Layanan Operasional'],
            ['Dedem Danuatmadja', '198209132025211057', 'Operator Layanan Operasional'],
            ['Hoerudin', '197907152025211096', 'Operator Layanan Operasional'],
            ['Dindin Saepudin', '198604192025211075', 'Operator Layanan Operasional'],
            ['Sahim', '197804082025211082', 'Pengelola Umum Operasional'],
        ];

        foreach ($employees as $data) {
            $fullName = $data[0];
            $nip = $data[1];
            $division = $data[2];

            // Generate email: namalengkap tanpa gelar dan tanpa spasi
            $cleanName = preg_replace('/,.*$/', '', $fullName); // Hapus gelar
            $emailName = strtolower(str_replace(' ', '', $cleanName));
            $email = $emailName . '@brmpph.com';

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $fullName,
                    'nip' => $nip,
                    'division' => $division,
                    'password' => Hash::make('brmpph#22'),
                    'role' => 'pegawai',
                    'is_admin' => false,
                    'provider' => 'local'
                ]
            );
        }
    }
}
