<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAdminNotificationsOrEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_notifications_or_email', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->default(0)->comment('0 user 1 masafr');
            $table->text('subject');
            $table->string('title')->nullable();
            $table->integer('send_by')->default(0)->comment('0 => notification 1 => window 2 => email 3 => sms');
            $table->integer('type_of_template')->nullable()->default(0)->comment('the kind of template in notifiaction');
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
        Schema::dropIfExists('admin_notifications_or_email');
    }
}
