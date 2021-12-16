<?php

use Illuminate\Database\Seeder;

class DesignTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 0;
        foreach(\App\Statics\Statics::DESIGN_TYPES as $designType){
            $count++;
            $design = new \App\SystemDesignType();
            $design->name = $designType;
            $design->save();

            $price = new \App\SystemDesignPrice();
            $price->price =  $count * 10;
            $price->change_price =  $count * 10.2;
            $design->prices()->save($price);
        }
    }
}
