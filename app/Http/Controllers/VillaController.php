<?php

namespace App\Http\Controllers;

use App\Models\Villa;
use App\Models\Environnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VillaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:ville,id',
            'delegation_id' => 'required|exists:delegations,id',
            'adresse' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|integer|min:1',
            'chambres' => 'required|integer|min:0',
            'pieces' => 'required|integer|min:0',
            'annee_construction' => 'required|integer|min:1900|max:'.date('Y'),
            'meuble' => 'sometimes|boolean',
            'environnement_id' => 'required|exists:environnements,id',
            'jardin' => 'sometimes|boolean',
            'piscine' => 'sometimes|boolean',
            'etages' => 'nullable|integer|min:0',
            'superficie_jardin' => 'nullable|integer|min:0',
            'piscine_privee' => 'sometimes|boolean',
            'garage' => 'sometimes|boolean',
            'cave' => 'sometimes|boolean',
            'terrasse' => 'sometimes|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Traitement des images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('villas_images', 'public');
                $imagesPaths[] = $path;
            }
        }

        // Création de la villa
        $villa = Villa::create([
            'type_id' => $validated['type_id'],
            'categorie_id' => $validated['categorie_id'],
            'ville_id' => $validated['ville_id'],
            'delegation_id' => $validated['delegation_id'],
            'adresse' => $validated['adresse'],
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'superficie' => $validated['superficie'],
            'chambres' => $validated['chambres'],
            'pieces' => $validated['pieces'],
            'annee_construction' => $validated['annee_construction'],
            'meuble' => $validated['meuble'] ?? false,
            'environnement_id' => $validated['environnement_id'],
            'jardin' => $validated['jardin'] ?? false,
            'piscine' => $validated['piscine'] ?? false,
            'etages' => $validated['etages'] ?? null,
            'superficie_jardin' => $validated['superficie_jardin'] ?? null,
            'piscine_privee' => $validated['piscine_privee'] ?? false,
            'garage' => $validated['garage'] ?? false,
            'cave' => $validated['cave'] ?? false,
            'terrasse' => $validated['terrasse'] ?? false,
            'images' => $imagesPaths,
        ]);

        return response()->json([
            'message' => 'Villa créée avec succès',
            'data' => $villa
        ], 201);
    }

    public function index()
    {
        return response()->json(Villa::all());
    }
}