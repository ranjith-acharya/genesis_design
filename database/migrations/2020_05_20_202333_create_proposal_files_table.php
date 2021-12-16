<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('content_type');
            $table->enum('type', \App\Statics\Statics::PROPOSAL_FILE_TYPES);

            $table->foreignId('proposal_id');
            $table->foreign('proposal_id')->references('id')->on('proposals');

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
        Schema::dropIfExists('proposal_files');
    }
}
