<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ferme_infrastructure', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ferme_id')->constrained('fermes')->onDelete('cascade');
            $table->foreignId('infrastructure_id')->constrained('infrastructure_fermes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ferme_infrastructure');
    }
};
