<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = ['nom'];

    public function maisons()
    {
        return $this->hasMany(Maison::class, 'type_transaction_id');
    }
}