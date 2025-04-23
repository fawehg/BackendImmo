<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maison extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'environnement_id', // Doit être présent

        'images',
    ];

    protected $casts = [
        'meuble' => 'boolean',
        'images' => 'array',
    ];



    public function vendeur()
    {
        return $this->belongsTo(Vendeur::class);
    }

    public function type()
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

    public function images()
    {
        return $this->hasMany(MaisonImage::class);
    }

    public function environnements()
    {
        return $this->belongsToMany(Environnement::class);
    }
}