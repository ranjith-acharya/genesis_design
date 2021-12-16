<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileTypeProjectType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_type_project_type', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_required')->default(true);

            $table->foreignId('project_type_id');
            $table->foreign('project_type_id')->references('id')->on('project_types');
            $table->foreignId('file_type_id');
            $table->foreign('file_type_id')->references('id')->on('file_types');

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
        Schema::dropIfExists('file_type_project_type');
    }
}
