<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();
        //role seeder with admin & super admin
        $this->call(PermissionSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(LookUpSeeder::class);
        //$this->call(LocationSeed::class);
        $this->call(AditionalFieldSeeder::class);
        $this->call(MenuSeeder::class);
        // Model::reguard(); // Enable mass assignment

    }
}
