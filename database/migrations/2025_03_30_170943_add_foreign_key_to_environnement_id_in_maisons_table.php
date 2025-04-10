<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToEnvironnementIdInMaisonsTable extends Migration
{
    public function up()
    {
        Schema::table('maisons', function (Blueprint $table) {
            // Ajouter la clé étrangère
            $table->foreign('environnement_id')
                ->references('id')
                ->on('environnements')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('maisons', function (Blueprint $table) {
            // Supprimer la clé étrangère
            $table->dropForeign(['environnement_id']);
        });
    }
}
