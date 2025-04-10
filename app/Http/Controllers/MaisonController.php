<?php

namespace App\Http\Controllers;
use App\Models\Maison;
use App\Models\MaisonImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Ville;

class MaisonController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation des données
        $validated = $request->validate([
            'type_transaction_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:ville,id',
            'delegation_id' => 'required|exists:delegations,id',
            'adresse' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|integer|min:1',
            'nombre_chambres' => 'required|integer|min:0',
            'nombre_pieces' => 'required|integer|min:0',
'annee_construction' => 'required|integer|min:0',
            'environnement_id' => 'required|exists:environnements,id', // Validation clé étrangère
            'meuble' => 'sometimes|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
    
        // 2. Traitement des images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('maisons_images', 'public');
                $imagesPaths[] = $path;
            }
        }
    
        // 3. Création de l'annonce
        $maison = Maison::create([
            'type_transaction_id' => $validated['type_transaction_id'],
            'categorie_id' => $validated['categorie_id'],
            'ville_id' => $validated['ville_id'],
            'delegation_id' => $validated['delegation_id'],
            'adresse' => $validated['adresse'],
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'superficie' => $validated['superficie'],
            'nombre_chambres' => $validated['nombre_chambres'],
            'nombre_pieces' => $validated['nombre_pieces'],
            'annee_construction' => $validated['annee_construction'],

            'environnement_id' => $validated['environnement_id'], // Champ crucial
            'meuble' => $validated['meuble'] ?? false,
            'images' => $imagesPaths,
        ]);
    
        // 4. Retour de la réponse
        return response()->json([
            'message' => 'Maison créée avec succès',
            'data' => $maison,
            'environnement_id' => $maison->environnement_id // Vérification
        ], 201);
    }
}