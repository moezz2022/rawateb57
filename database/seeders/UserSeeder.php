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
        
        User::create([
            'name' => 'Admin User',
            'phone' => '1234567890',
            'email' => 'admin@gmail.com',
            'username' => 'admin@88',
            'main_group' => 1, 
            'sub_group' => 2, 
            'password' => Hash::make('admin@gmail.com'), 
            'role' => 'admin',
            'is_active' => true,
            'two_factor_enabled' => false,
        ]);

    }
}
