<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = PermissionEnum::toArray();

        foreach ($permissions as $key => $permission) {
            Permission::create(['name'=> $permission]);
        }
    }
}
