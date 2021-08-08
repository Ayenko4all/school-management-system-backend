<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();

        User::factory()->create([
            'email' => 'siteOwner@gmail.com'
        ])->assignRoleToUser([RoleEnum::SITEADMIN]);

        User::factory()->create([
            'email' => 'schoolOwner@gmail.com'
        ])->assignRoleToUser([RoleEnum::SCHOOLOWNER]);
        User::factory()->create([
            'email' => 'superadmin@gmail.com'
        ])->assignRoleToUser([RoleEnum::SUPERADMIN]);
    }
}
