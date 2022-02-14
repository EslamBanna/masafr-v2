<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUpdateQeueusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_qeueus', function (Blueprint $table) {
            $table->id();
            $table->integer('person_id');
            $table->boolean('type')->default(0)->comment('0 user 1 masafr');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('id_photo')->nullable();
            $table->string('nationality')->nullable();
            $table->string('national_id_number')->nullable();
            $table->string('reason')->nullable();
            $table->enum('admin_response', [-1, 0, 1])->default('0')->comment('0 does not response yet 1 accepted -1 refuesd');
            $table->boolean('gender')->nullable()->comment('0 women 1 man');
            $table->enum('update_type', [0, 1, 2])->default(0)->comment('0 data 1 tawsek 2 both');
            $table->integer('decision_maker')->nullable();
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
        Schema::dropIfExists('update_qeueus');
    }
}
