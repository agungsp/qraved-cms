<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeQravedUserIdFieldToQravedUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qraved_users', function (Blueprint $table) {
            $table->string('qraved_user_mapping_id', 10)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qraved_users', function (Blueprint $table) {
            $table->bigInteger('qraved_user_mapping_id', false, true)->change();
        });
    }
}
