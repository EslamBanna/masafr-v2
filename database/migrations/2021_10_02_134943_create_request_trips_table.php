<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRequestTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_trips', function (Blueprint $table) {
            $table->id();
            $table->integer('chat_id');
            $table->integer('request_id');
            $table->integer('trip_id');
            $table->integer('receipt_code')->default(0);
            $table->integer('delivery_code')->default(0);
            $table->enum('offer_status', [-1, 0, 1, 2, 3, 4, 5])->default(0);
            $table->boolean('payment_method')->default(0)->comment('0 is online 1 is cash hand');
            $table->integer('user_mark')->default(0);
            $table->string('user_feedback')->nullable();
            $table->integer('masafr_mark')->default(0);
            $table->string('masafr_feedback')->nullable();
            $table->integer('decision_maker')->default(0);
            $table->string('reasons')->nullable();
            $table->integer('current_status')->default(0)->nullable();
            $table->integer('discounts')->default(0)->nullable();
            $table->integer('website_service')->default(0);
            $table->integer('insurance_hold')->default(0);
            $table->enum('cancel_type', [0, 1, 2])->default('0')->comment('0 not canceled , 1 from user 2 from masafr');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_trips');
    }
}
