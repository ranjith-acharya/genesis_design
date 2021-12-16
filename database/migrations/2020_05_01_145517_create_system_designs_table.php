<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('system_designs', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat('price');
            $table->enum('status', \App\Statics\Statics::DESIGN_STATUSES)->default(\App\Statics\Statics::DESIGN_STATUS_REQUESTED);
            $table->json('fields')->nullable()->default(null);
            $table->string('stripe_payment_code')->nullable()->default(null);
            $table->dateTime('payment_date')->nullable()->default(null);

            $table->foreignId('project_id');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreignId('system_design_type_id');
            $table->foreign('system_design_type_id')->references('id')->on('system_design_types');

            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_designs');
    }
}
