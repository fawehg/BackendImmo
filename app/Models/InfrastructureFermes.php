<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfrastructureFermes extends Model
{
    use HasFactory;

    protected $table = 'infrastructure_fermes';

    protected $fillable = ['nom'];
    public function fermes()
    {
        return $this->belongsToMany(Ferme::class, 'ferme_infrastructure', 'infrastructure_id', 'ferme_id');
    }
}
