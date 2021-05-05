<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQravedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qraved_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('qraved_user_mapping_id', false, true)->unique();
            $table->string('qraved_user_token', 150)->index()->nullable();
            $table->string('email')->index()->nullable();
            $table->string('contact', 20)->nullable();
            $table->char('gender', 1)->nullable();
            $table->date('birth_date')->nullable();
            $table->text('interest')->nullable();
            $table->string('job')->nullable();
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
        Schema::dropIfExists('qraved_users');
    }
}
