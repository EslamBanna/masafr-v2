<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMessageObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_objects', function (Blueprint $table) {
            $table->id();
            $table->integer('message_id');
            $table->integer('sender_type')->comment('0 from user to masafr, 1 from masafr to user, and else is th chat notifications');
            $table->text('subject')->nullable();
            $table->string('attach')->nullable();
            $table->boolean('user_seen')->default(0);
            $table->boolean('masafr_seen')->default(0);
            $table->enum('private_msg', [0, 1, 2])->default(0)->comment('0 not private 1 private to user 2 private to masafr');
            $table->integer('code')->comment('the code of thr private msg');
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
        Schema::dropIfExists('message_objects');
    }
}
