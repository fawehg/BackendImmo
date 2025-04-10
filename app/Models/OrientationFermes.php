<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrientationFermes extends Model
{
    use HasFactory;

    protected $table = 'orientation_fermes';

    protected $fillable = ['nom'];
}
