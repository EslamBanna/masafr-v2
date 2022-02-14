<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateFatoorahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fatoorahs', function (Blueprint $table) {
            $table->id();
            $table->integer('fatoorah_list_id');
            $table->string('subject');
            $table->integer('value')->default(0);
            $table->boolean('is_fee_insurance')->default(0)->comment('0 is fatoorah subject 1 is fee insurance subject');
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
        Schema::dropIfExists('fatoorahs');
    }
}
