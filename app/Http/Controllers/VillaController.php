<?php

namespace App\Http\Controllers;

use App\Models\Villa;
use App\Models\Environnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VillaController extends Controller
{
    public function index()
    {
        $villas = Villa::with(['ville', 'delegation', 'categorie', 'type', 'environnement'])
                       ->orderBy('created_at', 'desc')
                       ->get();

        $formattedVillas = $villas->map(function ($villa) {
            // Convertir les chemins relatifs en URLs absolues
            $images = array_map(function ($image) {
                return [
                    'url' => asset('storage/' . $image),
                    'path' => $image
                ];
            }, $villa->images ?? []);

            return [
                'id' => $villa->id,
                'titre' => $villa->titre,
                'description' => $villa->description,
                'prix' => $villa->prix,
                'superficie' => $villa->superficie,
                'chambres' => $villa->chambres,
                'pieces' => $villa->pieces,
                'annee_construction' => $villa->annee_construction,
                'meuble' => $villa->meuble,
                'adresse' => $villa->adresse,
                'ville' => $villa->ville->nom,
                'delegation' => $villa->delegation->nom,
                'categorie' => $villa->categorie->nom,
                'type' => $villa->type->nom,
                'environnement' => $villa->environnement->nom,
                'jardin' => $villa->jardin,
                'piscine' => $villa->piscine,
                'etages' => $villa->etages,
                'superficie_jardin' => $villa->superficie_jardin,
                'piscine_privee' => $villa->piscine_privee,
                'garage' => $villa->garage,
                'cave' => $villa->cave,
                'terrasse' => $villa->terrasse,
                'images' => $images,
                'created_at' => $villa->created_at,
                'updated_at' => $villa->updated_at
            ];
        });

        return response()->json($formattedVillas);
    }

    public function show($id)
    {
        $villa = Villa::with([
            'ville',
            'delegation',
            'categorie',
            'type',
            'environnement'
        ])->find($id);

        if (!$villa) {
            return response()->json(['message' => 'Villa non trouvée'], 404);
        }

        // Convertir les chemins relatifs en URLs absolues
        $images = array_map(function ($image) {
            return [
                'url' => asset('storage/' . $image),
                'path' => $image
            ];
        }, $villa->images ?? []);

        return response()->json([
            'id' => $villa->id,
            'titre' => $villa->titre,
            'description' => $villa->description,
            'prix' => $villa->prix,
            'superficie' => $villa->superficie,
            'chambres' => $villa->chambres,
            'pieces' => $villa->pieces,
            'annee_construction' => $villa->annee_construction,
            'meuble' => $villa->meuble,
            'adresse' => $villa->adresse,
            'ville' => $villa->ville,
            'delegation' => $villa->delegation,
            'categorie' => $villa->categorie,
            'type' => $villa->type,
            'environnement' => $villa->environnement,
            'jardin' => $villa->jardin,
            'piscine' => $villa->piscine,
            'etages' => $villa->etages,
            'superficie_jardin' => $villa->superficie_jardin,
            'piscine_privee' => $villa->piscine_privee,
            'garage' => $villa->garage,
            'cave' => $villa->cave,
            'terrasse' => $villa->terrasse,
            'images' => $images
        ]);
    }
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


}