<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColAnswerToQuestionBanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_banks', function (Blueprint $table) {
            $table->renameColumn('multiple_choice', 'answer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_banks', function (Blueprint $table) {
            $table->renameColumn('answer', 'multiple_choice');
        });
    }
}
