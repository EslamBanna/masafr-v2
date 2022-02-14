<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCoponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copons', function (Blueprint $table) {
            $table->id();
            $table->string('gift_picture')->nullable();
            $table->string('copon_name');
            $table->date('copon_start_date')->nullable();
            $table->date('copon_end_date')->nullable();
            $table->integer('amount')->default(0);
            $table->integer('value')->default(0);
            $table->boolean('copon_type')->default(0)->comment('0 is is discount copon 1 is gift copon');
            $table->integer('used')->default(0);
            $table->string('copon_full_amount_err')->nullable();
            $table->string('not_exsist_copon_err')->nullable();
            $table->string('has_used_copon_before_err')->nullable();
            $table->string('copon_success')->nullable();
            $table->string('link')->nullable();
            $table->string('attach')->nullable();
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
        Schema::dropIfExists('copons');
    }
}
