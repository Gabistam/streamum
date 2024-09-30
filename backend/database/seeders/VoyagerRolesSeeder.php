<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Role;

class VoyagerRolesSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrator']
        );

        Role::firstOrCreate(
            ['name' => 'user'],
            ['display_name' => 'Normal User']
        );
    }
}