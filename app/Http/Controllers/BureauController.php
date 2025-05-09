<?php

// app/Http/Controllers/BureauController.php
namespace App\Http\Controllers;

use App\Models\Bureau;
use App\Models\Type;
use App\Models\Categorie;
use App\Models\Ville;
use App\Models\Delegation;
use App\Models\Environnement;
use App\Models\Caracteristique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\CaracteristiqueBureau;

class BureauController extends Controller
{
     /**
     * Display a listing of the bureaux.
     */
    public function indexbureau()
    {
        try {
            $bureaux = Bureau::with(['type', 'categorie', 'ville', 'delegation', 'environnement', 'caracteristiques'])
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Bureaux loaded for index view', ['count' => $bureaux->count()]);

            return view('bureaux.index', compact('bureaux'));
        } catch (\Exception $e) {
            Log::error('Error in BureauController@indexbureau', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement des bureaux.');
        }
    }

    /**
     * Show the form for creating a new bureau.
     */
    public function createbureau()
    {
        $types = Type::all();
        $categories = Categorie::all();
        $villes = Ville::all();
        $environnements = Environnement::all();
        $caracteristiques = CaracteristiqueBureau::all();
        return view('bureaux.create', compact('types', 'categories', 'villes', 'environnements', 'caracteristiques'));
    }

    /**
     * Store a newly created bureau in storage.
     */
    public function storebureau(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:1',
            'superficie_couverte' => 'nullable|numeric|min:1',
            'nombre_bureaux' => 'nullable|integer|min:0',
            'nombre_toilettes' => 'nullable|integer|min:0',
            'adresse' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:villes,id',
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
            'caracteristiques' => 'nullable|array',
            'caracteristiques.*' => 'exists:caracteristique_bureaux,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // Prepare data
        $data = $validated;
    
        // Gestion des images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('bureaux_images', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = json_encode($imagePaths);
        } else {
            $data['images'] = json_encode([]);
        }
    
        $bureau = Bureau::create($data);
    
        if (!empty($validated['caracteristiques'])) {
            $bureau->caracteristiques()->attach($validated['caracteristiques']);
        }
    
        return redirect()->route('bureaux.index')->with('success', 'Bureau créé avec succès.');
    }

    /**
     * Display the specified bureau.
     */
    public function showbureau($id)
    {
        $bureau = Bureau::with(['type', 'categorie', 'ville', 'delegation', 'environnement', 'caracteristiques'])
            ->findOrFail($id);
        return view('bureaux.show', compact('bureau'));
    }

    /**
     * Show the form for editing the specified bureau.
     */
    public function editbureau($id)
    {
        $bureau = Bureau::with('caracteristiques')->findOrFail($id);
        $types = Type::all();
        $categories = Categorie::all();
        $villes = Ville::all();
        $delegations = Delegation::where('ville_id', $bureau->ville_id)->get();
        $environnements = Environnement::all();
        $caracteristiques = CaracteristiqueBureau::all();
        return view('bureaux.edit', compact('bureau', 'types', 'categories', 'villes', 'delegations', 'environnements', 'caracteristiques'));
    }

    /**
     * Update the specified bureau in storage.
     */
 public function updatebureau(Request $request, $id)
{
    $bureau = Bureau::findOrFail($id);

    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'required|string',
        'prix' => 'required|numeric|min:0',
        'superficie' => 'required|numeric|min:1',
        'superficie_couverte' => 'nullable|numeric|min:1',
        'nombre_bureaux' => 'nullable|integer|min:0',
        'nombre_toilettes' => 'nullable|integer|min:0',
        'adresse' => 'required|string|max:255',
        'type_id' => 'required|exists:types,id',
        'categorie_id' => 'required|exists:categories,id',
        'ville_id' => 'required|exists:villes,id',
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
        'caracteristiques' => 'nullable|array',
        'caracteristiques.*' => 'exists:caracteristique_bureaux,id',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $imagesPaths = $bureau->images ?? []; // Safe because $bureau->images is an array due to casting
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('bureaux_images', 'public');
            $imagesPaths[] = $path;
        }
    }

    $bureau->update([
        'titre' => $validated['titre'],
        'description' => $validated['description'],
        'prix' => $validated['prix'],
        'superficie' => $validated['superficie'],
        'superficie_couverte' => $validated['superficie_couverte'],
        'nombre_bureaux' => $validated['nombre_bureaux'],
        'nombre_toilettes' => $validated['nombre_toilettes'],
        'adresse' => $validated['adresse'],
        'type_id' => $validated['type_id'],
        'categorie_id' => $validated['categorie_id'],
        'ville_id' => $validated['ville_id'],
        'delegation_id' => $validated['delegation_id'],
        'environnement_id' => $validated['environnement_id'],
        'images' => !empty($imagesPaths) ? json_encode($imagesPaths) : null, // Store as JSON
    ]);

    $bureau->caracteristiques()->sync($validated['caracteristiques'] ?? []);

    return redirect()->route('bureaux.index')->with('success', 'Bureau mis à jour avec succès.');
}

    /**
     * Remove the specified bureau from storage.
     */
    public function destroybureau($id)
    {
        $bureau = Bureau::findOrFail($id);
    
        // Convertir les images en tableau si elles sont encodées en JSON
        $images = is_string($bureau->images) ? json_decode($bureau->images, true) : $bureau->images;
    
        if (is_array($images)) {
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
    
        // Détacher les caractéristiques si la relation existe
        $bureau->caracteristiques()->detach();
    
        // Supprimer le bureau
        $bureau->delete();
    
        return redirect()->route('bureaux')->with('success', 'Bureau supprimé avec succès.');
    }
    
    public function indexe()
{
    $bureauxCount = Bureau::count();
    return view('bureaux.index', compact('bureauxCount'));
}

public function index(Request $request)
{
    try {
        // 1. Récupération des bureaux avec relations
        $query = Bureau::with([
            'ville',
            'delegation',
            'categorie',
            'type',
            'environnement',
            'caracteristiques'
        ]);

        // Apply status filter if provided
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        $bureaux = $query->orderBy('created_at', 'desc')->get();

        // Log pour vérifier les données brutes
        Log::debug('Bureaux récupérés', [
            'count' => $bureaux->count(),
            'first_item' => $bureaux->first() ? $bureaux->first()->toArray() : null
        ]);

        // 2. Formatage des données
        $formatted = $bureaux->map(function ($bureau) {
            $imagesArray = is_array($bureau->images) ? $bureau->images : json_decode($bureau->images, true) ?? [];
            $images = array_map(function ($image) {
                return [
                    'url' => asset('storage/' . $image),
                    'path' => $image
                ];
            }, $imagesArray);

            return [
                'id' => $bureau->id,
                'titre' => $bureau->titre,
                'description' => $bureau->description,
                'prix' => $bureau->prix,
                'superficie' => $bureau->superficie,
                'superficie_couverte' => $bureau->superficie_couverte,
                'nombre_bureaux' => $bureau->nombre_bureaux,
                'nombre_toilettes' => $bureau->nombre_toilettes,
                'adresse' => $bureau->adresse,
                'ville' => $bureau->ville->nom ?? null,
                'delegation' => $bureau->delegation->nom ?? null,
                'categorie' => $bureau->categorie->nom ?? null,
                'type' => $bureau->type->nom ?? null,
                'environnement' => $bureau->environnement->nom ?? null,
                'caracteristiques' => $bureau->caracteristiques->pluck('nom') ?? [],
                'images' => $images,
                'created_at' => $bureau->created_at,
                'updated_at' => $bureau->updated_at,
                'status' => $bureau->status,
                'ville_id' => $bureau->ville_id,
                'delegation_id' => $bureau->delegation_id,
                'categorie_id' => $bureau->categorie_id,
                'type_transaction_id' => $bureau->type_transaction_id
            ];
        });

        // Log final avant retour
        Log::info('Réponse des bureaux générée', [
            'count' => $formatted->count(),
            'sample' => $formatted->first()
        ]);

        // 3. Retour de la réponse
        return response()->json($formatted);

    } catch (\Exception $e) {
        Log::error('Erreur dans BureauController@index', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'error' => 'Une erreur est survenue lors de la récupération des bureaux',
            'details' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}
public function show($id)
{
    try {
        $bureau = Bureau::with([
            'ville', 
            'delegation', 
            'categorie', 
            'type', 
            'environnement', 
            'caracteristiques', 
            'vendeur' // Add vendeur here
        ])->findOrFail($id);

        // Ensure images is an array
        $imagesArray = is_string($bureau->images) ? json_decode($bureau->images, true) : $bureau->images;
        $imagesArray = is_array($imagesArray) ? $imagesArray : [];

        // Map images to include URL and path
        $images = array_map(function ($image) {
            return [
                'url' => asset('storage/' . $image),
                'path' => $image
            ];
        }, $imagesArray);

        return response()->json([
            'id' => $bureau->id,
            'titre' => $bureau->titre,
            'description' => $bureau->description,
            'prix' => $bureau->prix,
            'superficie' => $bureau->superficie,
            'superficie_couverte' => $bureau->superficie_couverte,
            'nombre_bureaux' => $bureau->nombre_bureaux,
            'nombre_toilettes' => $bureau->nombre_toilettes,
            'adresse' => $bureau->adresse,
            'ville' => $bureau->ville,
            'delegation' => $bureau->delegation,
            'categorie' => $bureau->categorie,
            'type' => $bureau->type,
            'environnement' => $bureau->environnement,
            'caracteristiques' => $bureau->caracteristiques,
            'images' => $images,
            'created_at' => $bureau->created_at,
            'updated_at' => $bureau->updated_at,
            'vendeur' => $bureau->vendeur ? [
                'id' => $bureau->vendeur->id,
                'nom' => $bureau->vendeur->nom,
                'prenom' => $bureau->vendeur->prenom,
                'email' => $bureau->vendeur->email,
            ] : null,
        ]);
    } catch (\Exception $e) {
        Log::error('Error in BureauController@show', [
            'id' => $id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['message' => 'Bureau non trouvé'], 404);
    }
}
public function store(Request $request)
{
    // 1. Log des headers de requête
    Log::info('Request headers', $request->headers->all());

    // Log authentication state
    Log::info('Authentication state', [
        'guard' => 'vendeurs',
        'user' => auth('vendeurs')->user(),
        'id' => auth('vendeurs')->id(),
        'token' => $request->bearerToken(),
    ]);
    // 3. Validation des données
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'required|string',
        'prix' => 'required|numeric|min:0',
        'superficie' => 'required|numeric|min:0',
        'superficie_couverte' => 'required|numeric|min:0',
        'nombre_bureaux' => 'required|integer|min:1',
        'nombre_toilettes' => 'required|integer|min:0',
        'adresse' => 'required|string|max:255',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        'type_id' => 'required|exists:types,id',
        'categorie_id' => 'required|exists:categories,id',
        'ville_id' => 'required|exists:ville,id',
        'delegation_id' => 'required|exists:delegations,id',
        'environnement_id' => 'required|exists:environnements,id',
        'caracteristiques' => 'nullable|array',
        'caracteristiques.*' => 'exists:caracteristique_bureaux,id'
    ]);

    Log::info('Requête reçue pour store bureau', [
        'has_images' => $request->hasFile('images'),
        'validated' => $validated,
    ]);

    // 4. Traitement des images
    $imagesPaths = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            if ($image->isValid()) {
                $path = $image->store('bureaux_images', 'public');
                $imagesPaths[] = $path;
                Log::info('Image stockée', ['path' => $path]);
            } else {
                Log::warning('Image invalide détectée', ['image' => $image]);
            }
        }
    } else {
        Log::info('Aucune image envoyée dans la requête');
    }

    // 5. Récupération du vendeur connecté
    $vendeurId = auth('vendeurs')->id();
    if (!$vendeurId) {
        Log::error('Utilisateur non authentifié');
        return response()->json(['message' => 'Utilisateur non authentifié'], 401);
    }

    // 6. Création du bureau
    $bureau = Bureau::create([
        'titre' => $validated['titre'],
        'description' => $validated['description'],
        'prix' => $validated['prix'],
        'superficie' => $validated['superficie'],
        'superficie_couverte' => $validated['superficie_couverte'],
        'nombre_bureaux' => $validated['nombre_bureaux'],
        'nombre_toilettes' => $validated['nombre_toilettes'],
        'adresse' => $validated['adresse'],
        'images' => !empty($imagesPaths) ? json_encode($imagesPaths) : null,
        'type_id' => $validated['type_id'],
        'categorie_id' => $validated['categorie_id'],
        'ville_id' => $validated['ville_id'],
        'delegation_id' => $validated['delegation_id'],
        'environnement_id' => $validated['environnement_id'],
        'vendeur_id' => $vendeurId,
    ]);

    // 7. Attachement des caractéristiques
    if (!empty($validated['caracteristiques'])) {
        $bureau->caracteristiques()->sync($validated['caracteristiques']);
    }

    Log::info('Bureau créé', [
        'bureau_id' => $bureau->id,
        'vendeur_id' => $bureau->vendeur_id,
        'images' => $bureau->images,
    ]);

    // 8. Réponse JSON
    return response()->json([
        'message' => 'Bureau créé avec succès',
        'data' => $bureau
    ], 201);
}

    }
    
