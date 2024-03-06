<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domaine extends Model
{
    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }
}

