<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    public function domaines()
    {
        return $this->hasMany(Domaine::class);
    }
}
