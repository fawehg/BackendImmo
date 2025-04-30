<?php

namespace App\Http\Controllers;

use App\Models\Terrain;
use App\Models\Type;
use App\Models\Categorie;
use App\Models\TypeTerrain;
use App\Models\Ville;
use App\Models\TypeSol;
use App\Models\Delegation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TerrainController extends Controller
{
    public function indexterrain()
    {
         {
            $terrains = Terrain::with(['type', 'categorie', 'ville', 'delegation', 'type_terrain', 'type_sol'])
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Terrains loaded for index view', ['count' => $terrains->count()]);

            return view('terrains.index', compact('terrains'));
        }
    }

    /**
     * Show the form for creating a new terrain.
     */
    public function createterrain()
    {
        $types = Type::all();
        $categories = Categorie::all();
        $villes = Ville::all();
        $types_terrains = TypeTerrain::all();
        $types_sols = TypeSol::all();
        return view('terrains.create', compact('types', 'categories', 'villes', 'types_terrains', 'types_sols'));
    }

    /**
     * Store a newly created terrain in storage.
     */
    public function storeterrain(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:1',
            'surface_constructible' => 'nullable|numeric|min:0',
            'permis_construction' => 'nullable|boolean',
            'cloture' => 'nullable|boolean',
            'adresse' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
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
            'types_terrains_id' => 'required|exists:types_terrains,id',
            'types_sols_id' => 'required|exists:types_sols,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Prepare data
        $data = $validated;
        $data['permis_construction'] = $request->boolean('permis_construction', false);
        $data['cloture'] = $request->boolean('cloture', false);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('terrains_images', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = json_encode($imagePaths);
        } else {
            $data['images'] = json_encode([]);
        }

        $terrain = Terrain::create($data);

        return redirect()->route('terrains.index')->with('success', 'Terrain créé avec succès.');
    }

    /**
     * Display the specified terrain.
     */
    public function showterrain($id)
    {
        $terrain = Terrain::with(['type', 'categorie', 'ville', 'delegation', 'type_terrain', 'type_sol'])
            ->findOrFail($id);
        return view('terrains.show', compact('terrain'));
    }

    /**
     * Show the form for editing the specified terrain.
     */
    public function editterrain($id)
    {
        $terrain = Terrain::findOrFail($id);
        $types = Type::all();
        $categories = Categorie::all();
        $villes = Ville::all();
        $types_terrains = TypeTerrain::all();
        $types_sols = TypeSol::all();
        return view('terrains.edit', compact('terrain', 'types', 'categories', 'villes', 'types_terrains', 'types_sols'));
    }

    /**
     * Update the specified terrain in storage.
     */
    public function updateterrain(Request $request, $id)
    {
        $terrain = Terrain::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:1',
            'surface_constructible' => 'nullable|numeric|min:0',
            'permis_construction' => 'nullable|boolean',
            'cloture' => 'nullable|boolean',
            'adresse' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
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
            'types_terrains_id' => 'required|exists:types_terrains,id',
            'types_sols_id' => 'required|exists:types_sols,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Prepare data
        $data = $validated;
        $data['permis_construction'] = $request->boolean('permis_construction', false);
        $data['cloture'] = $request->boolean('cloture', false);

        // Handle image uploads
        $imagePaths = $terrain->images ? json_decode($terrain->images, true) : [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('terrains_images', 'public');
                $imagePaths[] = $path;
            }
        }
        $data['images'] = json_encode($imagePaths);

        $terrain->update($data);

        return redirect()->route('terrains.index')->with('success', 'Terrain mis à jour avec succès.');
    }

    /**
     * Remove the specified terrain from storage.
     */
    public function destroyterrain($id)
{
    $terrain = Terrain::findOrFail($id);

    if (is_array($terrain->images)) {
        foreach ($terrain->images as $image) {
            Storage::disk('public')->delete($image);
        }
    }

    $terrain->delete();

    return redirect()->route('terrains')->with('success', 'Terrain supprimé avec succès.');
}

    public function indexe()
{
    $terrainsCount = Terrain::count();
    return view('terrains.index', compact('terrainsCount'));
}

    public function index()
    {
        $terrains = Terrain::with(['type', 'categorie', 'ville', 'delegation', 'type_terrain', 'type_sol'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        $formatted = $terrains->map(function ($terrain) {
            $images = collect($terrain->images)->map(function ($img) {
                return [
                    'url' => asset('storage/' . $img),
                    'path' => $img
                ];
            });
    
            return [
                'id' => $terrain->id,
                'titre' => $terrain->titre,
                'description' => $terrain->description,
                'prix' => $terrain->prix,
                'superficie' => $terrain->superficie,
                'adresse' => $terrain->adresse,
                'type' => $terrain->type->nom ?? null,
                'categorie' => $terrain->categorie->nom ?? null,
                'ville' => $terrain->ville->nom ?? null,
                'delegation' => $terrain->delegation->nom ?? null,
                'type_terrain' => $terrain->type_terrain->nom ?? null,
                'type_sol' => $terrain->type_sol->nom ?? null,
                'surface_constructible' => $terrain->surface_constructible,
                'permis_construction' => $terrain->permis_construction,
                'cloture' => $terrain->cloture,
                'images' => $images,
                'created_at' => $terrain->created_at,
            ];
        });
    
        return response()->json($formatted);
    }
    
    public function show($id)
    {
        $terrain = Terrain::with(['type', 'categorie', 'ville', 'delegation', 'type_terrain', 'type_sol'])->find($id);
    
        if (!$terrain) {
            return response()->json(['message' => 'Terrain non trouvé'], 404);
        }
    
        $images = collect($terrain->images)->map(function ($img) {
            return [
                'url' => asset('storage/' . $img),
                'path' => $img
            ];
        });
    
        return response()->json([
            'id' => $terrain->id,
            'titre' => $terrain->titre,
            'description' => $terrain->description,
            'prix' => $terrain->prix,
            'superficie' => $terrain->superficie,
            'adresse' => $terrain->adresse,
            'type' => $terrain->type->nom ?? null,
            'categorie' => $terrain->categorie->nom ?? null,
            'ville' => $terrain->ville->nom ?? null,
            'delegation' => $terrain->delegation->nom ?? null,
            'type_terrain' => $terrain->type_terrain->nom ?? null,
            'type_sol' => $terrain->type_sol->nom ?? null,
            'surface_constructible' => $terrain->surface_constructible,
            'permis_construction' => $terrain->permis_construction,
            'cloture' => $terrain->cloture,
            'images' => $images,
            'created_at' => $terrain->created_at,
        ]);
    }
    
    public function store(Request $request)
    {
        
        // Validation des données
        $validated = $request->validate([
            'type_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:ville,id',
            'delegation_id' => 'required|exists:delegations,id',
            'adresse' => 'required|string',
            'titre' => 'required|string',
            'description' => 'required|string',
            'prix' => 'required|numeric',
            'superficie' => 'required|integer',
            'types_terrains_id' => 'required|exists:types_terrains,id',
            'types_sols_id' => 'required|exists:types_sols,id',
            'surface_constructible' => 'nullable|integer',
            'permis_construction' => 'nullable|boolean',
            'cloture' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);
    
        // Traitement des images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('terrains_images', 'public');
                $imagesPaths[] = $path;
            }
        }
    
    
        // Création du terrain avec les données validées
        $terrain = Terrain::create([
            'type_id' => $validated['type_id'],
            'categorie_id' => $validated['categorie_id'],
            'ville_id' => $validated['ville_id'],
            'delegation_id' => $validated['delegation_id'],
            'adresse' => $validated['adresse'],
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'superficie' => $validated['superficie'],
            'types_terrains_id' => $validated['types_terrains_id'],
            'types_sols_id' => $validated['types_sols_id'],
            'surface_constructible' => $validated['surface_constructible'],
            'permis_construction' => $validated['permis_construction'],
            'cloture' => $validated['cloture']?? false,
            'images' => $imagesPaths // Stocker les images sous forme de chaîne JSON
        ]);
    
        return response()->json([
            'message' => 'Terrain créé avec succès',
            'data' => $terrain
        ], 201);
    }
    
   
}
