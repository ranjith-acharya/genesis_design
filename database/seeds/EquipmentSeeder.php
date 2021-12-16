<?php

use App\Statics\Statics;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Statics::EQUIPMENT_TYPES as $type){
            \App\Equipment::create([
                'name' => $type . "One",
                'type' => $type,
                'model' => $type . " Model One",
            ]);
        }

        foreach (Statics::EQUIPMENT_TYPES as $type){
            \App\Equipment::create([
                'name' => $type . "Two",
                'type' => $type,
                'model' => $type . " Model Two",
            ]);
        }
    }
}
