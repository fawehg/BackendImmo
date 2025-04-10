<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Environnement extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];
public function maisons()
{
    return $this->belongsToMany(Maison::class);
}
}
