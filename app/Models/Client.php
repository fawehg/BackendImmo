<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Client extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'ville',
        'adresse',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Méthode pour obtenir l'identifiant JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Méthode pour ajouter des claims personnalisés au token JWT
    public function getJWTCustomClaims()
    {
        return [];
    }
}