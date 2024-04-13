<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domaine extends Model
{
    protected $fillable = ['nom_domaine'];

    public function specialites()
    {
        return $this->hasMany(Specialite::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}