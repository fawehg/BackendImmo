<?php

namespace App\Http\Controllers;

use App\Models\Maison;
use App\Models\Appartement;
use App\Models\Villa;
use App\Models\Bureau;
use App\Models\Ferme;
use App\Models\Terrain;
use App\Models\EtageVilla;
use App\Models\Ville;
use App\Models\Categorie;
use App\Models\Type;
use Illuminate\Http\JsonResponse;

class PropertyController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'villes' => Ville::all(),
            'categories' => Categorie::all(),
            'types' => Type::all(),
            'maisons' => Maison::all(),
            'appartements' => Appartement::all(),
            'villas' => Villa::all(),
            'bureaux' => Bureau::all(),
            'fermes' => Ferme::all(),
            'terrains' => Terrain::all(),
            'etageVillas' => EtageVilla::all(),
        ]);
    }
}
