<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

//        Admin
        DB::table('users')->insert([
            'first_name' => "admin",
            'last_name' => "user",
            'email' => 'nikhiljagtap702@gmail.com',
            'stripe_id' => 'cus_HHKUeiuUvcZOp3',
            'default_payment_method' => 'card_1Gj7RMGjCH117sQywdaaSGKL',
            'phone' => \Illuminate\Support\Str::random(10),
            'role' => \App\Statics\Statics::USER_TYPE_ADMIN,
            'password' => Hash::make('password')
        ]);

//        User
        DB::table('companies')->insert([
            'name' => 'Test Company'
        ]);
        DB::table('users')->insert([
            'first_name' => "customer",
            'last_name' => "user",
            'stripe_id' => "cus_HHKW1WP0v2x7rA",
            'email' => 'nikhiljagtap959@gmail.com',
            'company' => 'Test Company',
            'default_payment_method' => 'pm_1Gilr6GjCH117sQyZIRESIVd',
            'phone' => \Illuminate\Support\Str::random(10),
            'role' => \App\Statics\Statics::USER_TYPE_CUSTOMER,
            'password' => Hash::make('password'),
            'company_id' => 1
        ]);

        //        engineer
        DB::table('users')->insert([
            'first_name' => "engineer",
            'last_name' => "user",
            'email' => 'nikhil.jagtap@rapipay.com',
            'phone' => \Illuminate\Support\Str::random(10),
            'role' => \App\Statics\Statics::USER_TYPE_ENGINEER,
            'password' => Hash::make('password')
        ]);
    }
}
