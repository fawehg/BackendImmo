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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function vendorProperties(Request $request)
    {
        $vendeur = Auth::guard('vendeurs')->user();
        if (!$vendeur) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }

        $properties = [];

        // Fetch properties for each type
        $maisons = Maison::where('vendeur_id', $vendeur->id)
            ->with(['ville', 'delegation', 'categorie', 'type', 'environnement'])
            ->get()
            ->map(function ($item) {
                return array_merge($item->toArray(), ['type' => 'maisons']);
            });

        $villas = Villa::where('vendeur_id', $vendeur->id)
            ->with(['ville', 'delegation', 'categorie', 'type', 'environnement'])
            ->get()
            ->map(function ($item) {
                return array_merge($item->toArray(), ['type' => 'villas']);
            });

        $appartements = Appartement::where('vendeur_id', $vendeur->id)
            ->with(['ville', 'delegation', 'categorie', 'type', 'environnements_app'])
            ->get()
            ->map(function ($item) {
                return array_merge($item->toArray(), ['type' => 'appartements']);
            });

        $bureaux = Bureau::where('vendeur_id', $vendeur->id)
            ->with(['ville', 'delegation', 'categorie', 'type', 'environnement', 'caracteristiques'])
            ->get()
            ->map(function ($item) {
                return array_merge($item->toArray(), ['type' => 'bureaux']);
            });

        $fermes = Ferme::where('vendeur_id', $vendeur->id)
            ->with(['ville', 'delegation', 'categorie', 'type', 'environnement', 'infrastructures', 'orientation'])
            ->get()
            ->map(function ($item) {
                return array_merge($item->toArray(), ['type' => 'fermes']);
            });

        $etageVillas = EtageVilla::where('vendeur_id', $vendeur->id)
            ->with(['ville', 'delegation', 'categorie', 'type', 'environnement'])
            ->get()
            ->map(function ($item) {
                return array_merge($item->toArray(), ['type' => 'etage_villas']);
            });

        $terrains = Terrain::where('vendeur_id', $vendeur->id)
            ->with(['ville', 'delegation', 'categorie', 'type', 'types_terrains', 'types_sols'])
            ->get()
            ->map(function ($item) {
                return array_merge($item->toArray(), ['type' => 'terrains']);
            });

        $properties = collect([
            ...$maisons,
            ...$villas,
            ...$appartements,
            ...$bureaux,
            ...$fermes,
            ...$etageVillas,
            ...$terrains,
        ])->map(function ($item) {
            // Transform images to full URLs
            if (isset($item['images'])) {
                $item['images'] = collect($item['images'])->pluck('path')->map(function ($path) {
                    return 'http://localhost:8000/storage/' . $path;
                })->toArray();
            }
            return $item;
        })->toArray();

        return response()->json($properties);
    }
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