<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEnvironnementIdColumnInMaisonsTable extends Migration
{
    public function up()
    {
        Schema::table('maisons', function (Blueprint $table) {
            // Modifier la colonne pour qu'elle soit de type BIGINT
            $table->unsignedBigInteger('environnement_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('maisons', function (Blueprint $table) {
            // Revenir à JSON si nécessaire
            $table->json('environnement_id')->nullable()->change();
        });
    }
}


