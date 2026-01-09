<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Ku',
            'email' => 'adminku@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
