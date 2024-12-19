<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        User::create([
            'name' => 'Alif Raihan Nafis',
            'email' => 'alif@gmail.com',
            'occupation' => 'Consultant',
            'phone_number' => '08127770242',
            'password' => Hash::make('alif'),
        ]);

        User::create([
            'name' => 'Christian Johan',
            'email' => 'johan@gmail.com',
            'occupation' => 'Doctor',
            'phone_number' => '0812782121',
            'password' => Hash::make('johan'),
        ]);
    }
}
