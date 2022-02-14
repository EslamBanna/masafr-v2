<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRollbackRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rollback_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('masafr_id');
            $table->integer('request_id');
            $table->boolean('type')->default(0)->comment('type send,,, 0 user 1 masafr');
            $table->string('msg')->nullable();
            $table->boolean('response')->default(0);
            $table->text('reason')->nullable();
            $table->boolean('escalation')->default(0);
            $table->integer('decision')->default(0);
            $table->integer('complain_id');
            $table->integer('decision_maker');
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
        Schema::dropIfExists('rollback_requests');
    }
}
