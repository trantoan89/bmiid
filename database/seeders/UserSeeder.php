<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'username' => 'admin', 
            'first_name' => 'Admin',
            'last_name' => 'Administrator',
            'role' => 'Admin',
            'contact_number' => '00000000000',
            'email' => 'admin@gmail.com', 
            'password' => Hash::make('admin123'),
        ]);
    }
}
