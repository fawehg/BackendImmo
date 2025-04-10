<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fermes', function (Blueprint $table) {
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
            $table->foreignId('orientation_id')->constrained('orientation_fermes');
            $table->foreignId('environnement_id')->constrained('environnement_fermes');
            $table->json('images')->nullable(); // Stocke les chemins des images
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fermes');
    }
};

