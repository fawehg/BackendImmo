<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'travaildemander_id',
        'rate',
        'commentaire',
    ];

    /**
     * Relation avec le client
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la demande de travail
     */
    public function travailDemander()
    {
        return $this->belongsTo(TravailDemander::class, 'travaildemander_id');
    }
}
