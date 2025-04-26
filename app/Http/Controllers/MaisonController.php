<?php

namespace App\Http\Controllers;

use App\Models\Maison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MaisonController extends Controller
{
    public function index()
    {
        try {
            // 1. Récupération des maisons avec relations
            $maisons = Maison::with([
                'ville',
                'delegation',
                'categorie',
                'typeTransaction',
                'environnement'
            ])->orderBy('created_at', 'desc')->get();
    
            // Log pour vérifier les données brutes
            Log::debug('Maisons récupérées', [
                'count' => $maisons->count(),
                'first_item' => $maisons->first() ? $maisons->first()->toArray() : null
            ]);
    
            // 2. Formatage des données
            $formatted = $maisons->map(function ($maison) {
                $images = array_map(function ($image) {
                    return [
                        'url' => asset('storage/' . $image),
                        'path' => $image
                    ];
                }, $maison->images ?? []);
        
    
                // 4. Construction de la réponse
                return [
                    'id' => $maison->id,
                    'titre' => $maison->titre,
                    'description' => $maison->description,
                    'prix' => $maison->prix,
                    'superficie' => $maison->superficie,
                    'nombre_chambres' => $maison->nombre_chambres,
                    'nombre_pieces' => $maison->nombre_pieces,
                    'annee_construction' => $maison->annee_construction,
                    'adresse' => $maison->adresse,
                    'meuble' => $maison->meuble,
                    'ville' => $maison->ville->nom ?? null,
                    'delegation' => $maison->delegation->nom ?? null,
                    'categorie' => $maison->categorie->nom ?? null,
                    'type_transaction' => $maison->typeTransaction->nom ?? null,
                    'environnement' => $maison->environnement->nom ?? null,
                    'images' => $images,
                    'created_at' => $maison->created_at,
                    'updated_at' => $maison->updated_at
                ];
            });
    
            // Log final avant retour
            Log::info('Réponse des maisons générée', [
                'count' => $formatted->count(),
                'sample' => $formatted->first()
            ]);
    
            // 5. Retour de la réponse
            return response()->json($formatted);
    
        } catch (\Exception $e) {
            Log::error('Erreur dans MaisonController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Une erreur est survenue lors de la récupération des maisons',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    
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
            'superficie' => 'required|numeric|min:1',
            'nombre_chambres' => 'required|integer|min:0',
            'nombre_pieces' => 'required|integer|min:0',
            'annee_construction' => 'required|integer|min:1900|max:'.(date('Y') + 1),
            'environnement_id' => 'required|exists:environnements,id',
            'meuble' => 'sometimes|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // 2. Log des données reçues
        Log::info('Requête reçue pour store maison', [
            'files' => $request->hasFile('images'),
            'images_count' => count($request->file('images') ?? []),
            'validated' => $validated,
        ]);

        // 3. Traitement des images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('maisons_images', 'public');
                    $imagesPaths[] = $path;
                    Log::info('Image stockée', ['path' => $path]);
                } else {
                    Log::warning('Image invalide détectée', ['image' => $image]);
                }
            }
        } else {
            Log::info('Aucune image envoyée dans la requête');
        }

        // 4. Création de la maison
        $maison = Maison::create(array_merge($validated, [
            'images' => $imagesPaths ?: []
        ]));

        // 5. Log de la maison créée
        Log::info('Maison créée', ['maison_id' => $maison->id, 'images' => $maison->images]);

        // 6. Retour de la réponse
        return response()->json([
            'message' => 'Maison créée avec succès',
            'data' => $maison->load(['ville', 'delegation', 'categorie', 'typeTransaction', 'environnement'])
        ], 201);
    }
}