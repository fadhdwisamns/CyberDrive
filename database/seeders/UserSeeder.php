<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus data user lama agar tidak duplikat saat seeding ulang
        User::truncate();

        // 1. Buat User Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@softui.com',
            'password' => Hash::make('secret'), // passwordnya: secret
            'role' => 'admin',
            'storage_quota' => 53687091200, // Kuota 50 GB untuk admin
        ]);

        // 2. Buat User Karyawan 1
        User::create([
            'name' => 'Karyawan Satu',
            'email' => 'karyawan1@softui.com',
            'password' => Hash::make('secret'), // passwordnya: secret
            'role' => 'karyawan',
            'storage_quota' => 2147483648, // Kuota default 2 GB
        ]);

        // 3. Buat User Karyawan 2
        User::create([
            'name' => 'Karyawan Dua',
            'email' => 'karyawan2@softui.com',
            'password' => Hash::make('secret'), // passwordnya: secret
            'role' => 'karyawan',
            // Kuota akan menggunakan nilai default dari migrasi (1 GB)
        ]);
    }
}
