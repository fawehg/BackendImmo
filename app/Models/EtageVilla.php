<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtageVilla extends Model
{
    use HasFactory;

    protected $table = 'etage_villa';

    protected $fillable = [
        'vendeur_id',
        'status', // Add status

        'type_id',
        'categorie_id',
        'ville_id',
        'delegation_id',
        'environnement_id',
        'adresse',
        'titre',
        'description',
        'prix',
        'superficie',
        'numero_etage',
        'acces_independant',
        'parking_inclus',
        'annee_construction',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
        'acces_independant' => 'boolean',
        'parking_inclus' => 'boolean',
        'prix' => 'decimal:2'
    ];

    // Relations
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function vendeur()
    {
        return $this->belongsTo(Vendeur::class);
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

    public function environnement()
    {
        return $this->belongsTo(Environnement::class);
    }
}