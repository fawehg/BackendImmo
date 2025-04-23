<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ferme extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id', 'categorie_id', 'ville_id', 'delegation_id',
        'adresse', 'titre', 'description', 'prix', 'superficie',
        'orientation_id', 'environnement_id', 'images'
    ];

    protected $casts = [
        'images' => 'array',
    ];

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

    public function orientation()
    {
        return $this->belongsTo(OrientationFermes::class);
    }

    public function environnement()
    {
        return $this->belongsTo(EnvironnementFerme::class);
    }

    public function infrastructures()
    {
        return $this->belongsToMany(InfrastructureFermes::class, 'ferme_infrastructure', 'ferme_id', 'infrastructure_id');
    }
}
