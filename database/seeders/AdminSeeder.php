<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $login = config('app.login');
        $pwd = config('app.pwd');
        $admin = User::create([
            'email' => $login,
            'password' => Hash::make($pwd),
            'phone' => '',
            'first_name' => 'Admin',
            'last_name' => 'ADMIN',
            'company_name' => '',
            'siret'  => '',
            'address' => '',
            'city' => '',
            'zip_code' => '',
            'country' => 'FRANCE',
            'verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // client profile 
        $client = User::create([
            'email' => 'client@saii.fr',
            'password' => Hash::make("abc12345"),
            'phone' => '+33 7 54 12 47 84',
            'first_name' => 'John',
            'last_name' => 'DOE',
            'company_name' => 'Amazon',
            'siret'  => '12345123451234',
            'address' => '14 Rue bellevile',
            'city' => 'Lyon',
            'zip_code' => '69003',
            'country' => 'FRANCE',
            'code' => 'ABCDABCD',
            'verified_at' => now(),
        ]);
        $client->assignRole('client');
    }
}
