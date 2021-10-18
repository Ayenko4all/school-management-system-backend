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
        User::factory()->create()->assignRoleToUser([RoleEnum::ADMIN]);
        User::factory()->create([
            'email' => 'admin@gmail.com'
        ])->assignRoleToUser([RoleEnum::ADMIN]);
    }
}
