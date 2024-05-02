<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class TravailDemander extends Model
{
    protected $table = 'travaildemander'; // Assurez-vous que le nom de la table correspond à celui que vous avez défini dans votre migration

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    public function ouvrier()
    {
        return $this->belongsTo(User::class, 'ouvrier_id'); // Ajoutez la relation avec l'ouvrier (utilisateur)
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
