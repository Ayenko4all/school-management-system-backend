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
        Role::findByName(RoleEnum::TEACHER)->givePermissionTo(['update-teacher','create-teacher','view-teacher',]);
        Role::findByName(RoleEnum::STUDENT)->givePermissionTo([ 'update-student','create-student','view-student']);
    }
}
