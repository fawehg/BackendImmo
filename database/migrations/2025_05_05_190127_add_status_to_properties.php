<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToProperties extends Migration
{
    public function up()
    {
        // Pour la table maisons
        Schema::table('maisons', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
        });

        // Pour la table villas
        Schema::table('villas', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
        });

        // Pour la table appartements
        Schema::table('appartements', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
        });

        // Pour la table bureaux
        Schema::table('bureaux', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
        });

        // Pour la table fermes
        Schema::table('fermes', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
        });

        // Pour la table etage_villa
        Schema::table('etage_villa', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
        });

        // Pour la table terrains
        Schema::table('terrains', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('maisons', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason']);
        });

        Schema::table('villas', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason']);
        });

        Schema::table('appartements', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason']);
        });

        Schema::table('bureaux', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason']);
        });

        Schema::table('fermes', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason']);
        });

        Schema::table('etage_villa', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason']);
        });

        Schema::table('terrains', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason']);
        });
    }
}