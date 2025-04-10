<?php

namespace App\Http\Controllers;

use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TerrainController extends Controller
{
   

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
    
        // Convertir le tableau des chemins d'images en JSON
        $imagesPathsJson = json_encode($imagesPaths);
    
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
            'images' => $imagesPathsJson // Stocker les images sous forme de chaîne JSON
        ]);
    
        return response()->json([
            'message' => 'Terrain créé avec succès',
            'data' => $terrain
        ], 201);
    }
    
   
}
