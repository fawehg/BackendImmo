<?php

// app/Models/Caracteristique.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Caracteristique extends Model
{
    protected $fillable = ['nom'];
    
    public function bureaux(): BelongsToMany
    {
        return $this->belongsToMany(Bureau::class, 'bureau_caracteristiques');
    }
}