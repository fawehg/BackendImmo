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
   // database/migrations/xxxx_create_bureau_caracteristiques_table.php
Schema::create('bureau_caracteristiques', function (Blueprint $table) {
    $table->id();
    $table->foreignId('bureau_id')->constrained('bureaux')->onDelete('cascade');
    $table->foreignId('caracteristique_id')->constrained('caracteristique_bureaux')->onDelete('cascade');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bureau_caracteristiques');
    }
};
