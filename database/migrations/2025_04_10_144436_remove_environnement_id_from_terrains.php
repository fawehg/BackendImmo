<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveEnvironnementIdFromTerrains extends Migration
{
    /**
     * Exécutez la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terrains', function (Blueprint $table) {
            // Supprimez la contrainte de clé étrangère si elle existe
            $table->dropForeign(['environnement_id']);
            // Supprimez la colonne
            $table->dropColumn('environnement_id');
        });
    }

    /**
     * Annulez la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terrains', function (Blueprint $table) {
            // Vous pouvez ajouter à nouveau la colonne et la contrainte ici si nécessaire
            $table->foreignId('environnement_id')->constrained('environnements');
        });
    }
}
