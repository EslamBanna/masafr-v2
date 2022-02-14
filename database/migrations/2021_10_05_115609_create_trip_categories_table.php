<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTripCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_categories', function (Blueprint $table) {
            $table->id();
            $table->string('categorie_name');
            $table->string('photo')->nullable();
            $table->string('title')->nullable();
            $table->boolean('only_saudi')->default(0)->comment('this categorey for only sauid people');
            $table->boolean('active')->default(0)->comment('0 not active 1 is active');
            $table->boolean('special_dlivery')->default(0);
            $table->boolean('two_place')->default(0)->comment('0 one place 1 two places');
            $table->boolean('weekly')->default(0)->comment('0 no 1 yes');
            $table->boolean('counter')->default(0)->comment('0 no 1 yes');
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
        Schema::dropIfExists('trip_categories');
    }
}
