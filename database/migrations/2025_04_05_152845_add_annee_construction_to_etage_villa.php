<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('etage_villa', function (Blueprint $table) {
            $table->integer('annee_construction')->nullable(); // ou sans nullable() si obligatoire
        });
    }
    
    public function down()
    {
        Schema::table('etage_villa', function (Blueprint $table) {
            $table->dropColumn('annee_construction');
        });
    }
    
};
