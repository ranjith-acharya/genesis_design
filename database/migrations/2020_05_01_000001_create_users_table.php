<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('company')->nullable()->default(null);
            $table->string('stripe_id')->nullable();
            $table->string('default_payment_method')->nullable();
            //$table->string('role')->nullable();
            $table->enum('role', \App\Statics\Statics::USER_TYPES)->default(\App\Statics\Statics::USER_TYPE_CUSTOMER);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->foreignId('company_id')->nullable()->default(null);
            $table->foreign('company_id')->references('id')->on('companies');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
