<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    // Define fillable fields based on your database schema
    protected $fillable = ['url', 'imageable_id', 'imageable_type'];

    /**
     * Define a polymorphic relationship to the parent model (e.g., Maison, Appartement).
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}