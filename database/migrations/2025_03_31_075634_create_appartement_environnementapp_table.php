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
     // database/migrations/xxxx_create_appartement_environnementapp_table.php
Schema::create('appartement_environnementapp', function (Blueprint $table) {
    $table->id();
    $table->foreignId('appartement_id')->constrained()->onDelete('cascade');
    $table->foreignId('environnementapp_id')->constrained('environnementapp')->onDelete('cascade');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appartement_environnementapp');
    }
};
