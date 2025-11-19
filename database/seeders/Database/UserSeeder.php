<?php

namespace Database\Seeders\Database;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Herry Sya',
                'email' => 'herrysans12@gmail.com',
                'password' => Hash::make('password'),
            ]
        ];

        foreach($users as $user){
            User::create($user);
        }
    }
}
