<?php

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
         $this->call(PermissionTableSeeder::class);
         $this->call(UserSeeder::class);
         $this->call(FileTypeSeeder::class);
         $this->call(ProjectSeeder::class);
         $this->call(EquipmentSeeder::class);
         $this->call(DesignTypeSeeder::class);
    }
}
