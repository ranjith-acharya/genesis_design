<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('street_1')->nullable()->default(null);
            $table->string('street_2')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->string('state')->nullable()->default(null);
            $table->string('zip')->nullable()->default(null);
            $table->string('country')->nullable()->default(null);
            $table->string('latitude')->nullable()->default(null);
            $table->string('longitude')->nullable()->default(null);
            $table->enum('status', \App\Statics\Statics::STATUSES)->default(\App\Statics\Statics::STATUS_IN_ACTIVE);
            $table->enum('project_status', \App\Statics\Statics::PROJECT_STATUSES)->default(\App\Statics\Statics::PROJECT_STATUS_NOT_ASSIGNED);

            $table->foreignId('customer_id');
            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreignId('engineer_id')->nullable()->default(null);
            $table->foreign('engineer_id')->references('id')->on('users');
            $table->foreignId('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreignId('project_type_id');
            $table->foreign('project_type_id')->references('id')->on('project_types');

            $table->dateTime('assigned_date')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
