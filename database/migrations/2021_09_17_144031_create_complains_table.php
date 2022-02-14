<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('masafr_id');
            $table->integer('related_trip')->nullable();
            $table->integer('related_request_service')->nullable();
            $table->integer('related_chat');
            $table->boolean('status')->default(1)->comment('0 close 1 open');
            $table->boolean('solved')->default(0);
            $table->boolean('user_negative')->default(0);
            $table->boolean('masafr_negative')->default(0);
            $table->boolean('complainant')->default(0)->comment('0 from user 1 from masafr');
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('complains');
    }
}
