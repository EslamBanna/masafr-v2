<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAdvertisingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisings', function (Blueprint $table) {
            $table->id();
            $table->text('subject');
            $table->string('link')->nullable();
            $table->integer('site_after_announcement')->default(1);
            $table->boolean('all_days')->default(0);
            // $table->boolean('all_users_masafrs')->default(0);
            $table->integer('appear_time')->default(5);
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->integer('daily_repeat')->default(2);
            $table->boolean('active')->default(1);
            $table->string('image')->nullable();
            $table->integer('animation_type');
            $table->boolean('masafr_appear')->default(1);
            $table->boolean('user_appear')->default(1);
            $table->integer('person_target')->comment('0 is all users 1 is all masafrs 2 is all visitors 3 is specifics');
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
        Schema::dropIfExists('advertisings');
    }
}
