<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = RoleEnum::toArray();

        foreach ($roles as $key => $role) {
            Role::create(['name'=> $role]);
        }

        Role::findByName(RoleEnum::ADMIN)->givePermissionTo(Permission::all());
        Role::findByName(RoleEnum::TEACHER)->syncPermissions([5,6,7]);
        Role::findByName(RoleEnum::STUDENT)->syncPermissions([8,9,10]);
    }
}
