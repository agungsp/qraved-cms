<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionIdToQravedUserLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qraved_user_logs', function (Blueprint $table) {
            $table->bigInteger('question_id', false, true)->nullable()->after('restaurant_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qraved_user_logs', function (Blueprint $table) {
            $table->dropColumn('question_id');
        });
    }
}
