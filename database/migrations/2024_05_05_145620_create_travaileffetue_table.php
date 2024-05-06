<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravaileffetueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travaileffetue', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_travail_demande');
            $table->boolean('validation')->default(false);
            $table->timestamps();

            // Déclaration des clés étrangères
            $table->foreign('id_client')->references('id')->on('clients');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_travail_demande')->references('id')->on('travaildemander');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travaileffetue');
    }
}
