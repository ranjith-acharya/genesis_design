<?php

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Statics\Statics::PROJECT_TYPES as $projectType){
            \App\ProjectType::create([
                'name' => $projectType
            ]);
        }
    }
}
