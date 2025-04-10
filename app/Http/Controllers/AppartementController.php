<?php
// app/Http/Controllers/AppartementController.php
namespace App\Http\Controllers;

use App\Models\Appartement;
use App\Models\EnvironnementApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppartementController extends Controller
{
    public function store(Request $request)
    {
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
            'superficie_couvert' => 'nullable|integer|min:0',
            'etage' => 'nullable|integer|min:0',
            'meuble' => 'sometimes|boolean',
            'environnements_app' => 'nullable|array',
            'environnements_app.*' => 'exists:environnementapp,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Traitement des images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('appartements_images', 'public');
                $imagesPaths[] = $path;
            }
        }

        // Création de l'appartement
        $appartement = Appartement::create([
            'type_transaction_id' => $validated['type_transaction_id'],
            'categorie_id' => $validated['categorie_id'],
            'ville_id' => $validated['ville_id'],
            'delegation_id' => $validated['delegation_id'],
            'adresse' => $validated['adresse'],
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'superficie' => $validated['superficie'],
            'superficie_couvert' => $validated['superficie_couvert'] ?? null,
            'etage' => $validated['etage'] ?? null,
            'meuble' => $validated['meuble'] ?? false,
            'images' => $imagesPaths,
        ]);

        // Attacher les environnements
        if ($request->has('environnements_app')) {
            $appartement->environnementsApp()->attach($request->environnements_app);
        }

        return response()->json([
            'message' => 'Appartement créé avec succès',
            'data' => $appartement
        ], 201);
    }
}