<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bureau extends Model
{
    protected $table = 'bureaux'; // Spécifie explicitement le nom de la table

    protected $fillable = [
        'vendeur_id',        'status', // Add status

        'titre', 'description', 'prix', 'superficie', 'superficie_couverte',
        'nombre_bureaux', 'nombre_toilettes', 'adresse', 'images',
        'type_id', 'categorie_id', 'ville_id', 'delegation_id', 'environnement_id'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
    public function vendeur()
    {
        return $this->belongsTo(Vendeur::class);
    }
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function ville(): BelongsTo
    {
        return $this->belongsTo(Ville::class);
    }

    public function delegation(): BelongsTo
    {
        return $this->belongsTo(Delegation::class);
    }

    public function environnement(): BelongsTo
    {
        return $this->belongsTo(Environnement::class);
    }

    public function caracteristiques()
    {
        return $this->belongsToMany(CaracteristiqueBureau::class, 'bureau_caracteristiques', 'bureau_id', 'caracteristique_id');
    }
}