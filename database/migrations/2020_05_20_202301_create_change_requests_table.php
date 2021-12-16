<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_requests', function (Blueprint $table) {
            $table->id();
            $table->longText('description');
            $table->longText('engineer_note')->nullable()->default(null);
            $table->unsignedFloat('price')->nullable()->default(null);
            $table->enum('status', \App\Statics\Statics::CHANGE_REQUEST_STATUSES);
            $table->string('stripe_payment_code')->nullable()->default(null);

            $table->dateTime('payment_date')->nullable()->default(null);
            // $table->foreignId('proposal_id');
            // $table->foreign('proposal_id')->references('id')->on('proposals');
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
        Schema::dropIfExists('change_requests');
    }
}
