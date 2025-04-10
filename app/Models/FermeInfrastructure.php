<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FermeInfrastructure extends Model
{
    use HasFactory;

    protected $table = 'ferme_infrastructure';
    protected $fillable = ['ferme_id', 'infrastructure_id'];

    public function ferme()
    {
        return $this->belongsTo(Ferme::class);
    }

    public function infrastructure()
    {
        return $this->belongsTo(InfrastructureFermes::class);
    }
}
