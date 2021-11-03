<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'supervisor', 'employee'];

        foreach ($roles as $key => $role) {
            Role::create([
                'name' => $role
            ]);
        }
    }
}
