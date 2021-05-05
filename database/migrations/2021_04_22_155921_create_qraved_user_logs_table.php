<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQravedUserLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qraved_user_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('qraved_user_id', false, true)->index();
            $table->string('action');
            $table->bigInteger('user_answer_id', false, true)->index()->nullable();
            $table->bigInteger('restaurant_id', false, true)->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qraved_user_logs');
    }
}
