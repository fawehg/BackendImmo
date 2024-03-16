<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'domaines',
        'specialites',
        'city',
        'date',
        'time',
        'description',
        'image',
    ];

 
}
