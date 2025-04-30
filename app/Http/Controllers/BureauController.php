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

public function index()
{
    try {
        // 1. Récupération des bureaux avec relations
        $bureaux = Bureau::with([
            'ville',
            'delegation',
            'categorie',
            'type',
            'environnement',
            'caracteristiques'
        ])->orderBy('created_at', 'desc')->get();

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

            // 4. Construction de la réponse
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
                'updated_at' => $bureau->updated_at
            ];
        });

        // Log final avant retour
        Log::info('Réponse des bureaux générée', [
            'count' => $formatted->count(),
            'sample' => $formatted->first()
        ]);

        // 5. Retour de la réponse
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
        $bureau = Bureau::with(['ville', 'delegation', 'categorie', 'type', 'environnement', 'caracteristiques'])->findOrFail($id);

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
            'updated_at' => $bureau->updated_at
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
        // Validation des données
        $validatedData = $request->validate([
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
    
        // Traitement des images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('bureaux_images', 'public');
                $imagesPaths[] = $path;
            }
        }
    
        // Création du bureau avec les données validées
        $bureau = Bureau::create([
            'titre' => $validatedData['titre'],
            'description' => $validatedData['description'],
            'prix' => $validatedData['prix'],
            'superficie' => $validatedData['superficie'],
            'superficie_couverte' => $validatedData['superficie_couverte'],
            'nombre_bureaux' => $validatedData['nombre_bureaux'],
            'nombre_toilettes' => $validatedData['nombre_toilettes'],
            'adresse' => $validatedData['adresse'],
            'images' => !empty($imagesPaths) ? json_encode($imagesPaths) : null,
            'type_id' => $validatedData['type_id'],
            'categorie_id' => $validatedData['categorie_id'],
            'ville_id' => $validatedData['ville_id'],
            'delegation_id' => $validatedData['delegation_id'],
            'environnement_id' => $validatedData['environnement_id']
        ]);
    
        // Attachement des caractéristiques sélectionnées
        if (isset($validatedData['caracteristiques'])) {
            $bureau->caracteristiques()->sync($validatedData['caracteristiques']);
        }
    
        // Redirection avec message de succès
        return response()->json([
            'message' => 'bureaux créé avec succès',
            'data' => $bureau
        ], 201);
    }
    }
    
