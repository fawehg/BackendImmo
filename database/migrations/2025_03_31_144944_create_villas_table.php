<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('villas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('types');
            $table->foreignId('categorie_id')->constrained('categories');
            $table->foreignId('ville_id')->constrained('ville');
            $table->foreignId('delegation_id')->constrained('delegations');
            $table->string('adresse');
            $table->string('titre');
            $table->text('description');
            $table->decimal('prix', 10, 2);
            $table->integer('superficie');
            $table->integer('chambres');
            $table->integer('pieces');
            $table->integer('annee_construction');
            $table->boolean('meuble')->default(false);
            $table->foreignId('environnement_id')->constrained('environnements'); // Clé étrangère obligatoire

            $table->boolean('jardin')->default(false);
            $table->boolean('piscine')->default(false);
            $table->integer('etages')->nullable();
            $table->integer('superficie_jardin')->nullable();
            $table->boolean('piscine_privee')->default(false);
            $table->boolean('garage')->default(false);
            $table->boolean('cave')->default(false);
            $table->boolean('terrasse')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('villas');
    }
};

