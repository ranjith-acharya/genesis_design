<?php

use Illuminate\Database\Seeder;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Statics\Statics::FILE_CATEGORIES as $file){
            \App\FileType::create([
                'name' => $file,
                'description' => "This is just a placeholder"
            ]);
        }
    }
}
