<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        $user = User::create([
            'first_name' => "admin",
            'last_name' => "user",
            'email' => 'ranjithacharya997@gmail.com',
            'stripe_id' => 'cus_HHKUeiuUvcZOp3',
            'default_payment_method' => 'card_1Gj7RMGjCH117sQywdaaSGKL',
            'phone' => \Illuminate\Support\Str::random(10),
            'role' => \App\Statics\Statics::USER_TYPE_ADMIN,
            'password' => Hash::make('password')
        ]);

        $role = Role::create(['name' => 'admin']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);

//        User
        // DB::table('companies')->insert([
        //     'name' => 'Test Company'
        // ]);
        // $customer = User::create([
        //     'first_name' => "customer",
        //     'last_name' => "user",
        //     'stripe_id' => "cus_HHKW1WP0v2x7rA",
        //     'email' => 'mailexample000@gmail.com',
        //     'company' => 'Test Company',
        //     'default_payment_method' => 'pm_1Gilr6GjCH117sQyZIRESIVd',
        //     'phone' => \Illuminate\Support\Str::random(10),
        //     'role' => \App\Statics\Statics::USER_TYPE_CUSTOMER,
        //     'password' => Hash::make('password'),
        //     'company_id' => 1
        // ]);

        // $role = Role::create(['name' => 'customer']);
        // $role->givePermissionTo(['project-list', 'project-create', 'project-edit', 'project-delete', 'design-list', 'design-create', 'design-edit', 'design-delete']);
        // $customer->assignRole('customer');

        //        engineer
        // DB::table('users')->insert([
        //     'first_name' => "engineer",
        //     'last_name' => "user",
        //     'email' => 'nikhil.jagtap@rapipay.com',
        //     'phone' => \Illuminate\Support\Str::random(10),
        //     'role' => \App\Statics\Statics::USER_TYPE_ENGINEER,
        //     'password' => Hash::make('password')
        // ]);
    }
}
