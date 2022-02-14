<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRollbackRequestMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rollback_request_money', function (Blueprint $table) {
            $table->id();
            $table->integer('request_id');
            $table->integer('amount')->default(0);
            $table->string('bank_name')->nullable();
            $table->date('request_time')->nullable();
            $table->integer('account_number');
            $table->text('description')->nullable();
            $table->integer('status')->default(0);
            $table->integer('decision_maker');
            $table->string('trans_msg')->nullable();
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
        Schema::dropIfExists('rollback_request_money');
    }
}
