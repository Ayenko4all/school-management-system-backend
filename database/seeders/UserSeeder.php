<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Options\DefaultRole;
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
        User::factory()->create()->assignRoleToUser([DefaultRole::STUDENT]);
        User::factory()->create([
            'email' => 'admin@gmail.com'
        ])->assignRoleToUser([DefaultRole::ADMIN]);
    }
}
