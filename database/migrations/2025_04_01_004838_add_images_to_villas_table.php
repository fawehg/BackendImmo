<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->json('images')->nullable(); // Ajout de la colonne JSON
        });
    }

    public function down()
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->dropColumn('images'); // Suppression en cas de rollback
        });
    }
};
