<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('qraved_resto_mapping_id', false, true)->index();
            $table->string('name');
            $table->string('alias')->nullable();
            $table->text('address')->nullable();
            $table->string('contact', 20)->nullable();
            $table->bigInteger('qr_id', false, true)->index()->nullable();
            $table->bigInteger('created_by', false, true)->index();
            $table->bigInteger('updated_by', false, true)->index();
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
        Schema::dropIfExists('restaurants');
    }
}
