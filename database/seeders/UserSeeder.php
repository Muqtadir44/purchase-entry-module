<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $user = User::firstOrCreate(
            [
                'email' => 'user@test.com'
            ],
            [
                'name' => 'User',
                'password' => bcrypt('password')
            ]
        );

        $user->assignRole('User');
    }
}
