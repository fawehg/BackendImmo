<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneToVendeursTable extends Migration
{
    public function up()
    {
        Schema::table('vendeurs', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('adresse');
        });
    }

    public function down()
    {
        Schema::table('vendeurs', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
}