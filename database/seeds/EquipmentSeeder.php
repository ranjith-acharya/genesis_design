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
        $modules = ['Adani', 'Aptos', 'Astronergy', 'Axitec', 'Bluesun', 'Bovlet', 'Canadian Solar', 'Centro Solar', 'CertainTeed', 'Decotech(by GAF Energy)', 'ETSolar', 'Flextronics', 'GCL', 'Goldi', 'Hanwha Q-Cells', 'Hanwha SolarOne', 'Heliene', 'HT-SAAE', 'Hyundai', 'JASolar', 'Jinko Solar', 'Kyocera', 'LG Electronics', 'LONGI', 'Mission Solar', 'Mitsubishi', 'NeoSolar / URE', 'Next Energy Alliance', 'Panasonic', 'Pelmar', 'Phono Solar', 'Prism', 'REC', 'Recom', 'Renesola', 'Risen', 'S-Energy', 'Samsung', 'Seraphim', 'Sharp', 'Silfab', 'Solaria', 'SolarWorld', 'SunPower', 'SunSpark', 'Sunergy California', 'Talesun', 'Tesla', 'Titan', 'Trina', 'URE (NeoSolar)', 'VSUN', 'Waaree', 'Winalco', 'Wuxi Suntech', 'Yingli', 'Znshine', 'Others'];
        $inverters = ['ABB / Fimer', 'AP Systems', 'Chilicon', 'Delta', 'Enphase', 'Fronius', 'Generac', 'Ginlong', 'GroWatt MIN-XH', 'Hoymiles', 'Huawei', 'Lion Energy Sanctuary', 'NEP', 'QHOMEG1', 'Schneider', 'Sol-ARK', 'Solectria', 'Solaredge', 'SMA', 'Solaria', 'Sunbeat Energy', 'Sun Power', 'Titan', 'Others'];
        $rackers = ['EcoFasten Solar', 'Everest', 'Unirac', 'IronRidge', 'IronRidge & Quick Mount PV', 'Pegasus', 'PV Racking', 'Quick Mount PV', 'RoofTech', 'Solar Foundations', 'Solar Warehouse', 'Sola Rack', 'Sun Modo', 'Unirac & Ecolibrium', 'Others'];

        // foreach ($modules as $module){
        //     \App\Equipment::create([
        //         'name' => $module,
        //         'type' => "module",
        //         'model' => "Module Model One",
        //     ]);
        // }
        foreach ($inverters as $inverter){
            \App\Equipment::create([
                'name' => $inverter,
                'type' => "inverter",
                'model' => "Inverter Model One",
            ]);
        }
        foreach ($rackers as $racker){
            \App\Equipment::create([
                'name' => $racker,
                'type' => "racking",
                'model' =>  "Racking Model One",
            ]);
        }

        // foreach (Statics::EQUIPMENT_TYPES as $type){
        //     \App\Equipment::create([
        //         'name' => '',
        //         'type' => $type,
        //         'model' => $type . " Model Two",
        //     ]);
        // }
    }
}
