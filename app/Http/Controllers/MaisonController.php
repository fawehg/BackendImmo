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
    public function index()
    {
        $maisons = Maison::with(['ville', 'delegation', 'categorie', 'type'])
                         ->orderBy('created_at', 'desc')
                         ->get();
    
        $formattedMaisons = $maisons->map(function ($maison) {
            // Convertir les chemins relatifs en URLs absolues
            $images = array_map(function ($image) {
                return [
                    'url' => asset('storage/'.$image),
                    'path' => $image
                ];
            }, $maison->images ?? []);
    
            return [
                'id' => $maison->id,
                'titre' => $maison->titre,
                'description' => $maison->description,
                'prix' => $maison->prix,
                'superficie' => $maison->superficie,
                'chambres' => $maison->chambres,
                'salles_de_bain' => $maison->salles_de_bain,
                'salles_eau' => $maison->salles_eau,
                'ville' => $maison->ville->nom,
                'delegation' => $maison->delegation->nom,
                'categorie' => $maison->categorie->nom,
                'type' => $maison->type->nom,
                'images' => $images, // Maintenant un tableau d'objets avec url et path
                'adresse' => $maison->adresse,
                'created_at' => $maison->created_at,
                'updated_at' => $maison->updated_at
            ];
        });
    
        return response()->json($formattedMaisons);
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
    public function show($id)
{
    $maison = Maison::with([
        'ville',
        'delegation',
        'categorie',
        'type', // C’est celui qu’on vient d’ajouter
    ])->find($id);
    
    if (!$maison) {
        return response()->json(['message' => 'Maison non trouvée'], 404);
    }

    return response()->json([
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
        'images' => $maison->images,
    
        'ville' => $maison->ville,
        'delegation' => $maison->delegation,
        'categorie' => $maison->categorie,
        'type' => $maison->type,
        'environnement' => $maison->environnement,
    ]);
    
}}