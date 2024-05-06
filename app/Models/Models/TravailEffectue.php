<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravailEffectue extends Model
{
    use HasFactory;

    protected $table = 'travaileffetue';

    protected $fillable = [
        'id_client',
        'id_user',
        'id_travail_demande',
        'validation',
    ];

    /**
     * Relation avec le client
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Relation avec la demande de travail
     */
    public function travailDemande()
    {
        return $this->belongsTo(TravailDemander::class, 'id_travail_demande');
    }
}
