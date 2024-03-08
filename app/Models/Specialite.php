<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    protected $fillable = ['nom_specialite'];

    public function domaine()
    {
        return $this->belongsTo(Domaine::class);
    }
}