<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CaracteristiqueBureau extends Model
{
    protected $fillable = ['nom'];
    protected $table = 'caracteristique_bureaux'; // Spécifiez explicitement le nom correct

    public function bureaux(): BelongsToMany
    {
        return $this->belongsToMany(Bureau::class, 'bureau_caracteristiques');
    }
}