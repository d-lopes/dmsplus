<?php

use App\User;
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
        if (User::where('email', '=', 'admin@test.org')->count() == 0) {
            factory(User::class)->create([
                'name' => 'admin',
                'email' => 'admin@test.org',
                'password' => Hash::make('admin'),
            ]);
        }
        
    }
}
