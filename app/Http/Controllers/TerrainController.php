<?php

namespace App\Http\Controllers;

use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TerrainController extends Controller
{
    public function index()
    {
        $terrains = Terrain::with(['type', 'categorie', 'ville', 'delegation', 'type_terrain', 'type_sol'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        $formatted = $terrains->map(function ($terrain) {
            $images = collect($terrain->images)->map(function ($img) {
                return [
                    'url' => asset('storage/' . $img),
                    'path' => $img
                ];
            });
    
            return [
                'id' => $terrain->id,
                'titre' => $terrain->titre,
                'description' => $terrain->description,
                'prix' => $terrain->prix,
                'superficie' => $terrain->superficie,
                'adresse' => $terrain->adresse,
                'type' => $terrain->type->nom ?? null,
                'categorie' => $terrain->categorie->nom ?? null,
                'ville' => $terrain->ville->nom ?? null,
                'delegation' => $terrain->delegation->nom ?? null,
                'type_terrain' => $terrain->type_terrain->nom ?? null,
                'type_sol' => $terrain->type_sol->nom ?? null,
                'surface_constructible' => $terrain->surface_constructible,
                'permis_construction' => $terrain->permis_construction,
                'cloture' => $terrain->cloture,
                'images' => $images,
                'created_at' => $terrain->created_at,
            ];
        });
    
        return response()->json($formatted);
    }
    
    public function show($id)
    {
        $terrain = Terrain::with(['type', 'categorie', 'ville', 'delegation', 'type_terrain', 'type_sol'])->find($id);
    
        if (!$terrain) {
            return response()->json(['message' => 'Terrain non trouvé'], 404);
        }
    
        $images = collect($terrain->images)->map(function ($img) {
            return [
                'url' => asset('storage/' . $img),
                'path' => $img
            ];
        });
    
        return response()->json([
            'id' => $terrain->id,
            'titre' => $terrain->titre,
            'description' => $terrain->description,
            'prix' => $terrain->prix,
            'superficie' => $terrain->superficie,
            'adresse' => $terrain->adresse,
            'type' => $terrain->type->nom ?? null,
            'categorie' => $terrain->categorie->nom ?? null,
            'ville' => $terrain->ville->nom ?? null,
            'delegation' => $terrain->delegation->nom ?? null,
            'type_terrain' => $terrain->type_terrain->nom ?? null,
            'type_sol' => $terrain->type_sol->nom ?? null,
            'surface_constructible' => $terrain->surface_constructible,
            'permis_construction' => $terrain->permis_construction,
            'cloture' => $terrain->cloture,
            'images' => $images,
            'created_at' => $terrain->created_at,
        ]);
    }
    
    public function store(Request $request)
    {
        
        // Validation des données
        $validated = $request->validate([
            'type_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:ville,id',
            'delegation_id' => 'required|exists:delegations,id',
            'adresse' => 'required|string',
            'titre' => 'required|string',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'superficie' => 'required|integer',
            'types_terrains_id' => 'required|exists:types_terrains,id',
            'types_sols_id' => 'required|exists:types_sols,id',
            'surface_constructible' => 'nullable|integer',
            'permis_construction' => 'nullable|boolean',
            'cloture' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
    
        // Traitement des images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('terrains_images', 'public');
                $imagesPaths[] = $path;
            }
        }
    
    
        // Création du terrain avec les données validées
        $terrain = Terrain::create([
            'type_id' => $validated['type_id'],
            'categorie_id' => $validated['categorie_id'],
            'ville_id' => $validated['ville_id'],
            'delegation_id' => $validated['delegation_id'],
            'adresse' => $validated['adresse'],
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'superficie' => $validated['superficie'],
            'types_terrains_id' => $validated['types_terrains_id'],
            'types_sols_id' => $validated['types_sols_id'],
            'surface_constructible' => $validated['surface_constructible'],
            'permis_construction' => $validated['permis_construction'],
            'cloture' => $validated['cloture']?? false,
            'images' => $imagesPaths // Stocker les images sous forme de chaîne JSON
        ]);
    
        return response()->json([
            'message' => 'Terrain créé avec succès',
            'data' => $terrain
        ], 201);
    }
    
   
}
