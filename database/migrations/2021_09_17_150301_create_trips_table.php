<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->integer('masafr_id');
            // Hint: in every trip type there are it's own data take care...
            $table->integer('type_of_trips')->comment('the main categorie');
            $table->integer('type_of_services')->comment('subsection to the categorie');
            $table->boolean('only_women')->default(0)->comment('0 any one, 1 only womens');
            $table->string('from_place')->nullable();
            $table->string('from_longitude')->nullable();
            $table->string('from_latitude')->nullable();
            $table->string('to_place')->nullable();
            $table->string('to_longitude')->nullable();
            $table->string('to_latitude')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('description')->nullable();
            $table->boolean('active')->default(1)->comment('0 not active , 1 active');
            $table->boolean('on_progress')->default(0)->comment('0 no one(user) take it , 1 some one take it and it is in progress');
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
        Schema::dropIfExists('trips');
    }
}
