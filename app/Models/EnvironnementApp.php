<?php

// app/Models/EnvironnementApp.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class EnvironnementApp extends Model
{
    protected $table = 'environnementapp';
    protected $fillable = ['nom'];
    public function appartements()
    {
        return $this->belongsToMany(Appartement::class, 'appartement_environnement', 'environnement_id', 'appartement_id');
    }
}