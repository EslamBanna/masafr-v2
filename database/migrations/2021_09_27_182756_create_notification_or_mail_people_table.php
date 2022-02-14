<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateNotificationOrMailPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_or_mail_people', function (Blueprint $table) {
            $table->id();
            // $table->integer('type')->comment('0 user 1 masafr');
            $table->integer('notfication_or_mail_id');
            $table->integer('person_id');
            $table->boolean('showed')->default(0)->comment('0 not yet 1 showed it');
            $table->date('seen_time')->nullable();
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
        Schema::dropIfExists('notification_or_mail_people');
    }
}
