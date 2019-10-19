<?php

use App\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@cms.com',
            'mobile' => '9898989898',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'role' => 'Admin',
            'address' => '',
            'remember_token' => Str::random(10),
        ]);

        $this->command->info('Here is your admin details to login:');
        $this->command->warn('Username is "admin@cms.com"');
        $this->command->warn('Password is "password"');
    }

}
