<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRequestCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_categories', function (Blueprint $table) {
            $table->id();
            $table->string('categorie_name');
            $table->string('photo')->nullable();
            $table->string('title')->nullable();
            $table->boolean('only_saudi')->default(0)->comment('this categorey for only sauid people');
            $table->boolean('payment_method')->default(0)->comment('the payment method 0 => by hand 1 => by payment online');
            $table->boolean('have_insurance')->default(0)->comment('have insurance or not');
            $table->boolean('have_photo')->default(0)->comment('this categories apply user to upload photos or not');
            $table->boolean('two_places')->default(0)->comment('0 one place 1 have two places');
            $table->boolean('two_codes')->default(0)->comment('0 is one code and 1 is 2 codes');
            $table->boolean('active')->default(0)->comment('0 not active 1 is active');
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
        Schema::dropIfExists('request_categories');
    }
}
