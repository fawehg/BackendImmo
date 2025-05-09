<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maison extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendeur_id',        'status', // Add status

        'is_approved', // Add this

        'type_transaction_id',
        'categorie_id',
        'ville_id',
        'delegation_id',
        'adresse',
        'titre',
        'description',
        'prix',
        'superficie',
        'nombre_chambres',
        'nombre_pieces',
        'annee_construction',
        'meuble',
        'environnement_id',
        'images',
    ];
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    protected $casts = [
        'meuble' => 'boolean',
        'images' => 'array',
    ];

    public function typeTransaction()
    {
        return $this->belongsTo(Type::class, 'type_transaction_id');
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