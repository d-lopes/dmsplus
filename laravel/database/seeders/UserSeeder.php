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
        $adminEmail = env('ADMIN_EMAIL', 'admin@test.org');

        if (User::where('email', '=', $adminEmail)->count() == 0) {
    
            $adminUser = env('ADMIN_USER', 'admin');
            $adminPassword = env('ADMIN_PASSWORD', 'admin');

            factory(User::class)->create([
                'name' => $adminUser,
                'email' => $adminEmail,
                'password' => Hash::make($adminPassword),
            ]);
        }
        
    }
}
