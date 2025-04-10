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
        Schema::table('villas', function (Blueprint $table) {
            $table->renameColumn('etages', 'etage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('villas', function (Blueprint $table) {
            $table->renameColumn('etage', 'etages');
        });
    }
};