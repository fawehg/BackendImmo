<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegation extends Model
{
    use HasFactory;

    // Si ta table a un nom différent, spécifie-le ici
    protected $table = 'delegations';

    // Si tu utilises des colonnes `created_at` et `updated_at`, laisse cette ligne
    public $timestamps = true;

    // Si tu veux spécifier les colonnes remplissables, ajoute ceci
    protected $fillable = ['nom', 'ville_id'];
}