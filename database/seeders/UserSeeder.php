<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('admin123');
        $now = Carbon::now();

        $users = [

                ['namaLengkap' => 'Admin Baru Magang',
                'email'       => 'adminM@test.com',
                'password'    => $password,
                'noTelp'      => '081234567890',
                'fotoProfil'  => null,
                'role'        => 'admin',
                'created_at'  => $now,
                'updated_at'  => $now,],

                ['namaLengkap' => 'cs 1',
                'email'       => 'cs1@test.com',
                'password'    => Hash::make('cs123'),
                'noTelp'      => '081234567890',
                'fotoProfil'  => null,
                'role'        => 'customer',
                'created_at'  => $now,
                'updated_at'  => $now,],
        ];
        DB::table('user')->insert($users);
    }
}
