<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Villa extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id', 'categorie_id', 'ville_id', 'delegation_id', 
        'adresse', 'titre', 'description', 'prix', 'superficie',
        'chambres', 'pieces', 'annee_construction', 'meuble','environnement_id',
        'jardin', 'piscine', 'etages', 'superficie_jardin',
        'piscine_privee', 'garage', 'cave', 'terrasse'  ,'images'

    ];

    protected $casts = [
        'images' => 'array',
        'meuble' => 'boolean'
    ];
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    
    public function environnement()
    {
        return $this->belongsTo(Environnement::class);
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
