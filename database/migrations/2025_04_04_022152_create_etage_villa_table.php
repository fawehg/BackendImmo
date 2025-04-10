<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etage_villa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('types');
            $table->foreignId('categorie_id')->constrained('categories');
            $table->foreignId('ville_id')->constrained('ville');
            $table->foreignId('delegation_id')->constrained('delegations');
            $table->foreignId('environnement_id')->constrained('environnements');
            $table->string('adresse');
            $table->string('titre');
            $table->text('description');
            $table->decimal('prix', 10, 2);
            $table->integer('superficie');
            $table->integer('numero_etage');
            $table->boolean('acces_independant')->default(false);
            $table->boolean('parking_inclus')->default(false);
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etage_villa');
    }
};
