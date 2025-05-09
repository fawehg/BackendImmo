<?php

// app/Models/Appartement.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appartement extends Model
{
    protected $fillable = [
        'vendeur_id',
        'status', // Add status

        'type_transaction_id',
        'categorie_id',
        'ville_id',
        'delegation_id',
        'adresse',
        'titre',
        'description',
        'prix',
        'superficie',
        'superficie_couvert',
        'etage',
        'meuble',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
        'meuble' => 'boolean'
    ];

    public function environnementsApp()
    {
        return $this->belongsToMany(EnvironnementApp::class, 'appartement_environnementapp', 'appartement_id', 'environnementapp_id');
    }
    public function vendeur()
    {
        return $this->belongsTo(Vendeur::class);
    }

    public function typeTransaction()
    {
        return $this->belongsTo(Type::class, 'type_transaction_id');
    }

    public function categorie()
    {   
        return $this->belongsTo(Categorie::class);
    }

    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }

    public function delegation()
    {
        return $this->belongsTo(Delegation::class);
    }
    
}