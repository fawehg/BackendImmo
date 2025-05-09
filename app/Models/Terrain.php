<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terrain extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendeur_id',        'status', // Add status

        'type_id',
        'categorie_id',
        'ville_id',
        'delegation_id',
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

    protected $casts = [
        'images' => 'array',
    ];

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

    public function type_terrain()
    {
        return $this->belongsTo(TypeTerrain::class, 'types_terrains_id');
    }

    public function type_sol()
    {
        return $this->belongsTo(TypeSol::class, 'types_sols_id');
    }
}
