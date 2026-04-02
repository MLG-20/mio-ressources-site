<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');
        $name     = env('ADMIN_NAME', 'Super Admin');

        if (!$email || !$password) {
            $this->command->error('ADMIN_EMAIL et ADMIN_PASSWORD doivent être définis dans .env');
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'      => $name,
                'password'  => Hash::make($password),
                'role'      => 'admin',
                'user_type' => 'admin',
            ]
        );

        $this->command->info("Super admin créé : {$email}");
    }
}
