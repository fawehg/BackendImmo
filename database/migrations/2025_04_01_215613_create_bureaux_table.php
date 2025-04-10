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
Schema::create('bureaux', function (Blueprint $table) {
    $table->id();
    $table->string('titre');
    $table->text('description');
    $table->decimal('prix', 10, 2);
    $table->integer('superficie');
    $table->integer('nombre_bureaux');
    $table->integer('nombre_toilettes');
    $table->integer('superficie_couverte')->nullable();
    $table->string('adresse');
    
    // Clés étrangères
    $table->foreignId('type_id')->constrained('types');
    $table->foreignId('categorie_id')->constrained('categories');
    $table->foreignId('ville_id')->constrained('ville');
    $table->foreignId('delegation_id')->constrained('delegations');
    $table->foreignId('environnement_id')->constrained('environnements');
    
    $table->json('images')->nullable(); // Stockera les chemins des images
    
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bureaux');
    }
};
