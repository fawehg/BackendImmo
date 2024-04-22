<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom', 'prenom', 'email', 'ville', 'adresse', 'password', 'profession', 'specialties', 'joursDisponibilite', 'heureDebut', 'heureFin','numeroTelephone', 'image',
    ];
    

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'specialties' => 'array',
        'joursDisponibilite' => 'array',
    ];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class);
    }

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

   
    public function getJWTCustomClaims()
    {
        return [];
    }
}
