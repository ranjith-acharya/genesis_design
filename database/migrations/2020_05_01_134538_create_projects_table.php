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
            $table->string('street_1');
            $table->string('street_2')->nullable()->default(null);
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('country');
            $table->string('latitude');
            $table->string('longitude');
            $table->enum('status', \App\Statics\Statics::PROJECT_STATUSES)->default(\App\Statics\Statics::PROJECT_STATUS_PENDING);

            $table->foreignId('customer_id');
            $table->foreign('customer_id')->references('id')->on('users');
            $table->foreignId('engineer_id')->nullable()->default(null);
            $table->foreign('engineer_id')->references('id')->on('users');
            $table->foreignId('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreignId('project_type_id');
            $table->foreign('project_type_id')->references('id')->on('project_types');

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
