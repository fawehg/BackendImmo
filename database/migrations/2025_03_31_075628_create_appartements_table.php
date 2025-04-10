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
        // database/migrations/xxxx_create_appartements_table.php
Schema::create('appartements', function (Blueprint $table) {
    $table->id();
    $table->foreignId('type_transaction_id')->constrained('types');
    $table->foreignId('categorie_id')->constrained('categories');
    $table->foreignId('ville_id')->constrained('ville');
    $table->foreignId('delegation_id')->constrained('delegations');
    $table->string('adresse');
    $table->string('titre');
    $table->text('description');
    $table->decimal('prix', 10, 2);
    $table->integer('superficie');
    $table->integer('superficie_couvert')->nullable();
    $table->integer('etage')->nullable();
    $table->boolean('meuble')->default(false);
    $table->json('images')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartements');
    }
};
