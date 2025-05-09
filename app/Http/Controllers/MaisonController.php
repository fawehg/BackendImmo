<?php

namespace App\Http\Controllers;

use App\Models\Maison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Type;
use App\Models\Categorie;
use App\Models\Ville;
use App\Models\Environnement;
use App\Models\Delegation;
use Illuminate\Support\Facades\Auth;


class MaisonController extends Controller
{
    public function listAnnonceMaison()
    {
        // Récupère les maisons de l'utilisateur connecté
        $maisons = Maison::where('vendeur_id', Auth::id())
            ->with(['ville', 'delegation', 'categorie', 'typeTransaction', 'environnement'])
            ->latest()
            ->get();

        return response()->json($maisons);
    }

    public function showAnnonceMaison($id)
    {
        // Affiche les détails de la maison spécifique
        $maison = Maison::where('vendeur_id', Auth::id())
            ->with(['ville', 'delegation', 'categorie', 'typeTransaction', 'environnement'])
            ->findOrFail($id);

        return response()->json($maison);
    }
public function updateAnnonceMaison(Request $request, $id)
{
    $maison = Maison::where('id', $id)
        ->where('vendeur_id', Auth::id())
        ->firstOrFail();

    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'required|string',
        'prix' => 'required|numeric|min:0',
        'superficie' => 'required|numeric|min:1',
        'nombre_chambres' => 'nullable|integer|min:0',
        'nombre_pieces' => 'nullable|integer|min:0',
        'annee_construction' => 'nullable|integer|min:1900|max:' . date('Y'),
        'meuble' => 'boolean',
        'ville_id' => 'required|exists:villes,id',
        'delegation_id' => 'required|exists:delegations,id',
        'categorie_id' => 'required|exists:categories,id',
        'type_transaction_id' => 'required|exists:types,id',
        'environnement_id' => 'nullable|exists:environnements,id',
    ]);

    $maison->update($validated);

    if ($request->hasFile('images')) {
        foreach ($maison->images as $image) {
            Storage::disk('public')->delete($image);
        }

        $images = [];
        foreach ($request->file('images') as $image) {
            $images[] = $image->store('maisons', 'public');
        }

        $maison->images = $images;
        $maison->save();
    }

    return response()->json($maison);
}

    public function editAnnonceMaison(Request $request, $id)
    {
        // Tente de récupérer la maison, en vérifiant si le vendeur_id correspond
        $maison = Maison::where('id', $id)
            ->where('vendeur_id', Auth::id())
            ->firstOrFail();

        // Validation des données du formulaire
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:1',
            'nombre_chambres' => 'nullable|integer|min:0',
            'nombre_pieces' => 'nullable|integer|min:0',
            'annee_construction' => 'nullable|integer|min:1900|max:' . date('Y'),
            'meuble' => 'boolean',
            'ville_id' => 'required|exists:villes,id',
            'delegation_id' => 'required|exists:delegations,id',
            'categorie_id' => 'required|exists:categories,id',
            'type_transaction_id' => 'required|exists:types,id',
            'environnement_id' => 'nullable|exists:environnements,id',
        ]);

        // Mise à jour des données de la maison
        $maison->update($validated);

        // Si de nouvelles images sont envoyées
        if ($request->hasFile('images')) {
            // Supprimer les anciennes images
            foreach ($maison->images as $image) {
                Storage::disk('public')->delete($image);
            }

            // Réinitialiser le tableau d'images
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('maisons', 'public');
                $images[] = $path;
            }

            // Mettre à jour les images
            $maison->images = $images;
            $maison->save();
        }

        // Retourner la maison mise à jour
        return response()->json($maison);
    }

    public function deleteAnnonceMaison($id)
    {
        $maison = Maison::where('id', $id)
            ->where('vendeur_id', Auth::id())
            ->firstOrFail();

        // Supprimer les anciennes images
        foreach ($maison->images as $image) {
            Storage::disk('public')->delete($image);
        }

        $maison->delete();

        return response()->json(['message' => 'Maison supprimée avec succès']);
    }
    
        public function show($id)
        {
            // Load the maison with its relationships, including vendeur
            $maison = Maison::with([
                'ville',
                'delegation',
                'categorie',
                'typeTransaction',
                'environnement',
                'vendeur' // Added vendeur relationship
            ])->find($id);
    
            if (!$maison) {
                return response()->json(['message' => 'Maison non trouvée'], 404);
            }
    
            // Map images to include URL and path
            $images = array_map(function ($image) {
                return [
                    'url' => asset('storage/' . $image),
                    'path' => $image
                ];
            }, $maison->images ?? []);
    
            // Return the maison data with vendeur included
            return response()->json([
                'id' => $maison->id,
                'type_transaction_id' => $maison->type_transaction_id,
                'categorie_id' => $maison->categorie_id,
                'ville_id' => $maison->ville_id,
                'delegation_id' => $maison->delegation_id,
                'adresse' => $maison->adresse,
                'titre' => $maison->titre,
                'description' => $maison->description,
                'prix' => $maison->prix,
                'superficie' => $maison->superficie,
                'nombre_chambres' => $maison->nombre_chambres,
                'nombre_pieces' => $maison->nombre_pieces,
                'annee_construction' => $maison->annee_construction,
                'meuble' => $maison->meuble,
                'images' => $images,
                'ville' => $maison->ville,
                'delegation' => $maison->delegation,
                'categorie' => $maison->categorie,
                'type_transaction' => $maison->typeTransaction,
                'environnement' => $maison->environnement,
                'vendeur' => $maison->vendeur ? [
                    'id' => $maison->vendeur->id,
                    'nom' => $maison->vendeur->nom,
                    'prenom' => $maison->vendeur->prenom,
                    'email' => $maison->vendeur->email,
                    'phone' => $maison->vendeur->phone,
                ] : null, // Include vendeur data or null if not present
            ]);
        }
 
    public function indexmaison()
    {
        $maisons = Maison::with(['typeTransaction', 'categorie', 'ville', 'delegation', 'environnement'])->get();
        return view('maisons.index', compact('maisons'));
    }

    public function createmaison()
    {
        $types = Type::all();
        $categories = Categorie::all();
        $villes = Ville::all();
        $environnements = Environnement::all();
        return view('maisons.create', compact('types', 'categories', 'villes', 'environnements'));
    }

    public function storemaison(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:1',
            'nombre_chambres' => 'nullable|integer|min:0',
            'nombre_pieces' => 'nullable|integer|min:0',
            'annee_construction' => 'nullable|integer|min:1800|max:' . date('Y'),
            'meuble' => 'required|boolean',
            'adresse' => 'required|string|max:255',
            'type_transaction_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:ville,id',
            'delegation_id' => [
                'required',
                'exists:delegations,id',
                function ($attribute, $value, $fail) use ($request) {
                    $delegation = Delegation::find($value);
                    if ($delegation && $delegation->ville_id != $request->ville_id) {
                        $fail('La délégation sélectionnée n\'appartient pas à la ville choisie.');
                    }
                },
            ],
            'environnement_id' => 'nullable|exists:environnements,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // Prepare data
        $data = $validated;
        $data['meuble'] = $request->boolean('meuble');
    
        // Gestion des images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('maisons_images', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = json_encode($imagePaths);
        } else {
            $data['images'] = json_encode([]);
        }
    
        $maison = Maison::create($data);
    
        return redirect()->route('maisons')->with('success', 'Maison créée avec succès.');
    }

    public function showmaison($id)
    {
        $maison = Maison::with(['typeTransaction', 'categorie', 'ville', 'delegation', 'environnement'])->findOrFail($id);
        return view('maisons.show', compact('maison'));
    }

    public function editmaison($id)
    {
        $maison = Maison::findOrFail($id);
        $types = Type::all();
        $categories = Categorie::all();
        $villes = Ville::all();
        $delegations = Delegation::where('ville_id', $maison->ville_id)->get();
        $environnements = Environnement::all();
        return view('maisons.edit', compact('maison', 'types', 'categories', 'villes', 'delegations', 'environnements'));
    }

    public function updatemaison(Request $request, $id)
    {
        $maison = Maison::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:1',
            'nombre_chambres' => 'nullable|integer|min:0',
            'nombre_pieces' => 'nullable|integer|min:0',
            'annee_construction' => 'nullable|integer|min:1800|max:' . date('Y'),
            'meuble' => 'required|boolean',
            'adresse' => 'required|string|max:255',
            'type_transaction_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:ville,id',
            'delegation_id' => [
                'required',
                'exists:delegations,id',
                function ($attribute, $value, $fail) use ($request) {
                    $delegation = Delegation::find($value);
                    if ($delegation && $delegation->ville_id != $request->ville_id) {
                        $fail('La délégation sélectionnée n\'appartient pas à la ville choisie.');
                    }
                },
            ],
            'environnement_id' => 'nullable|exists:environnements,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagesPaths = $maison->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('maisons_images', 'public');
                $imagesPaths[] = $path;
            }
        }

        $maison->update([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'superficie' => $validated['superficie'],
            'nombre_chambres' => $validated['nombre_chambres'],
            'nombre_pieces' => $validated['nombre_pieces'],
            'annee_construction' => $validated['annee_construction'],
            'meuble' => $validated['meuble'],
            'adresse' => $validated['adresse'],
            'type_transaction_id' => $validated['type_transaction_id'],
            'categorie_id' => $validated['categorie_id'],
            'ville_id' => $validated['ville_id'],
            'delegation_id' => $validated['delegation_id'],
            'environnement_id' => $validated['environnement_id'],
            'images' => $imagesPaths,
        ]);

        return redirect()->route('maisons')->with('success', 'Maison mise à jour avec succès.');
    }

    public function destroymaison($id)
    {
        $maison = Maison::findOrFail($id);
        if ($maison->images) {
            foreach ($maison->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $maison->delete();
        return redirect()->route('maisons')->with('success', 'Maison supprimée avec succès.');
    }
    public function index(Request $request)
    {
        try {
            // 1. Récupération des maisons avec relations
            $query = Maison::with([
                'ville',
                'delegation',
                'categorie',
                'typeTransaction',
                'environnement'
            ]);

            // Apply status filter if provided
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            $maisons = $query->orderBy('created_at', 'desc')->get();
    
            // Log pour vérifier les données brutes
            Log::debug('Maisons récupérées', [
                'count' => $maisons->count(),
                'first_item' => $maisons->first() ? $maisons->first()->toArray() : null
            ]);
    
            // 2. Formatage des données
            $formatted = $maisons->map(function ($maison) {
                $images = collect($maison->images)->map(function ($img) {
                    return [
                        'url' => asset('storage/' . $img),
                        'path' => $img
                    ];
                });
        
                // 3. Construction de la réponse
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
                    'type' => $maison->typeTransaction->nom ?? null, // Changed to 'type' to match frontend
                    'environnement' => $maison->environnement->nom ?? null,
                    'images' => $images,
                    'created_at' => $maison->created_at,
                    'updated_at' => $maison->updated_at,
                    'status' => $maison->status, // Added status field
                    'ville_id' => $maison->ville_id,
                    'delegation_id' => $maison->delegation_id,
                    'categorie_id' => $maison->categorie_id,
                    'type_transaction_id' => $maison->type_transaction_id
                ];
            });
    
            // Log final avant retour
            Log::info('Réponse des maisons générée', [
                'count' => $formatted->count(),
                'sample' => $formatted->first()
            ]);
    
            // 4. Retour de la réponse
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
        // Log request headers for debugging
        Log::info('Request headers', $request->headers->all());

        // Log authentication state
        Log::info('Authentication state', [
            'guard' => 'vendeurs',
            'user' => auth('vendeurs')->user(),
            'id' => auth('vendeurs')->id(),
            'token' => $request->bearerToken(),
        ]);

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
            'annee_construction' => 'required|integer|min:1900|max:' . (date('Y') + 1),
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

        // 4. Récupération de l'ID du vendeur connecté
        $vendeurId = auth('vendeurs')->id();
        if (!$vendeurId) {
            Log::error('Utilisateur non authentifié');
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        // 5. Création de la maison avec vendeur_id
        $maison = Maison::create(array_merge($validated, [
            'vendeur_id' => $vendeurId,
            'images' => $imagesPaths ?: []
        ]));

        // 6. Log de la maison créée
        Log::info('Maison créée', ['maison_id' => $maison->id, 'images' => $maison->images]);

        // 7. Retour de la réponse
        return response()->json([
            'message' => 'Maison créée avec succès',
            'data' => $maison->load(['ville', 'delegation', 'categorie', 'typeTransaction', 'environnement'])
        ], 201);
    }
}