<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terrain extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'types_terrains_id',
        'types_sols_id',
        'surface_constructible',
        'permis_construction',
        'cloture',
        'images',
    ];

    // Relations (à adapter selon les tables associées)
    public function type()
    {
        return $this->belongsTo(Type::class);
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

    public function typesTerrain()
    {
        return $this->belongsTo(TypesTerrain::class);
    }

    public function typesSol()
    {
        return $this->belongsTo(TypesSol::class);
    }
}
