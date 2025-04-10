<?php


namespace App\Http\Controllers;

use App\Models\Ferme;
use App\Models\FermeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FermeController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation des données
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
            'infrastructures' => 'nullable|array',
            'infrastructures.*' => 'exists:infrastructure_fermes,id',
            'orientation_id' => 'required|exists:orientation_fermes,id',
            'environnement_id' => 'required|exists:environnement_fermes,id',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // 2. Traitement des images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('fermes_images', 'public');
                $imagesPaths[] = $path;
            }
        }

        // 3. Création de la ferme
        $ferme = Ferme::create([
            'type_id' => $validated['type_id'],
            'categorie_id' => $validated['categorie_id'],
            'ville_id' => $validated['ville_id'],
            'delegation_id' => $validated['delegation_id'],
            'adresse' => $validated['adresse'],
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'superficie' => $validated['superficie'],
            'orientation_id' => $validated['orientation_id'],
            'environnement_id' => $validated['environnement_id'],
          
            'images' => $imagesPaths,
        ]);

        // 4. Attachement des infrastructures
        if ($request->has('infrastructures')) {
            $ferme->infrastructures()->attach($validated['infrastructures']);
        }

       

        // 6. Retour de la réponse
        return response()->json([
            'message' => 'Ferme créée avec succès',
            'data' => $ferme->load('infrastructures')
        ], 201);
    }
}