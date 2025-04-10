<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaisonImage extends Model
{
    use HasFactory;

    protected $fillable = ['maison_id', 'path'];

    public function maison()
    {
        return $this->belongsTo(Maison::class);
    }
}