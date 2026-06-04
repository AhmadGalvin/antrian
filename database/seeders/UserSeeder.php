<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Superadmin (no branch)
        User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SUPERADMIN,
            'branch_id' => null,
        ]);

        // Create users for each branch
        $branches = Branch::all();
        $counter = 0;

        foreach ($branches as $branch) {
            $counter++;

            // Teller for each branch (Loket 1)
            User::create([
                'name' => "Teller {$branch->name}",
                'username' => "teller{$counter}",
                'password' => Hash::make('password'),
                'role' => User::ROLE_TELLER,
                'branch_id' => $branch->id,
                'counter_number' => 1,
            ]);

            // CS for each branch (Loket 2)
            User::create([
                'name' => "CS {$branch->name}",
                'username' => "cs{$counter}",
                'password' => Hash::make('password'),
                'role' => User::ROLE_CS,
                'branch_id' => $branch->id,
                'counter_number' => 2,
            ]);

            // Admin for each branch (Loket 3)
            User::create([
                'name' => "Admin {$branch->name}",
                'username' => "admin{$counter}",
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'branch_id' => $branch->id,
                'counter_number' => 3,
            ]);

            // Kiosk account for each branch
            User::create([
                'name' => "Kiosk {$branch->name}",
                'username' => "kiosk{$counter}",
                'password' => Hash::make('password'),
                'role' => User::ROLE_KIOSK,
                'branch_id' => $branch->id,
            ]);
        }
    }
}

