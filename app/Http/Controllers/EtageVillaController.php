<?php

namespace App\Http\Controllers;

use App\Models\EtageVilla;
use App\Models\Type;
use App\Models\Categorie;
use App\Models\Ville;
use App\Models\Environnement;
use App\Models\Delegation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EtageVillaController extends Controller
{  public function indexetagesvillas()
    {
        try {
            $etagesvillas = EtageVilla::with(['type', 'categorie', 'ville', 'delegation', 'environnement'])
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Etage Villas loaded for index view', ['count' => $etagesvillas->count()]);

            return view('etagesvillas.index', compact('etagesvillas'));
        } catch (\Exception $e) {
            Log::error('Error in EtageVillaController@indexetagesvillas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement des étages de villas.');
        }
    }

    /**
     * Show the form for creating a new etage villa.
     */
    public function createetagesvillas()
    {
        $types = Type::all();
        $categories = Categorie::all();
        $villes = Ville::all();
        $environnements = Environnement::all();
        return view('etagesvillas.create', compact('types', 'categories', 'villes', 'environnements'));
    }

    /**
     * Store a newly created etage villa in storage.
     */
    public function storeetagesvillas(Request $request)
{
    $validated = $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'required|string',
        'prix' => 'required|numeric|min:0',
        'superficie' => 'required|numeric|min:1',
        'numero_etage' => 'required|integer|min:0',
        'acces_independant' => 'nullable|boolean',
        'parking_inclus' => 'nullable|boolean',
        'annee_construction' => 'nullable|integer|min:1900|max:' . date('Y'),
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
        'environnement_id' => 'nullable|exists:environnements,id',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Prepare data, excluding images
    $data = $validated;
    $data['acces_independant'] = $request->boolean('acces_independant', false);
    $data['parking_inclus'] = $request->boolean('parking_inclus', false);

    // Gestion des images
    if ($request->hasFile('images')) {
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('public/etage_villas');
            $imagePaths[] = str_replace('public/', 'storage/', $path);
        }
        $data['images'] = json_encode($imagePaths);
    } else {
        $data['images'] = json_encode([]); // Ensure images is an empty array if no images are uploaded
    }

    $etagevilla = EtageVilla::create($data);

    return redirect()->route('etagesVillas')->with('success', 'Étage de villa créé avec succès.');
}

    /**
     * Display the specified etage villa.
     */
    public function showetagesvillas($id)
    {
        $etagevilla = EtageVilla::with(['type', 'categorie', 'ville', 'delegation', 'environnement'])
            ->findOrFail($id);
        return view('etagesvillas.show', compact('etagevilla'));
    }

    /**
     * Show the form for editing the specified etage villa.
     */
    public function editetagesvillas($id)
    {
        $etagevilla = EtageVilla::findOrFail($id);
        $types = Type::all();
        $categories = Categorie::all();
        $villes = Ville::all();
        $delegations = Delegation::where('ville_id', $etagevilla->ville_id)->get();
        $environnements = Environnement::all();
        return view('etagesvillas.edit', compact('etagevilla', 'types', 'categories', 'villes', 'delegations', 'environnements'));
    }

    /**
     * Update the specified etage villa in storage.
     */
    public function updateetagesvillas(Request $request, $id)
    {
        $etagevilla = EtageVilla::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:1',
            'numero_etage' => 'required|integer|min:0',
            'acces_independant' => 'nullable|boolean',
            'parking_inclus' => 'nullable|boolean',
            'annee_construction' => 'nullable|integer|min:1900|max:' . date('Y'),
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
            'environnement_id' => 'nullable|exists:environnements,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagesPaths = $etagevilla->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('etage_villa_images', 'public');
                $imagesPaths[] = $path;
            }
        }

        $etagevilla->update([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'superficie' => $validated['superficie'],
            'numero_etage' => $validated['numero_etage'],
            'acces_independant' => $validated['acces_independant'] ?? false,
            'parking_inclus' => $validated['parking_inclus'] ?? false,
            'annee_construction' => $validated['annee_construction'],
            'adresse' => $validated['adresse'],
            'type_id' => $validated['type_id'],
            'categorie_id' => $validated['categorie_id'],
            'ville_id' => $validated['ville_id'],
            'delegation_id' => $validated['delegation_id'],
            'environnement_id' => $validated['environnement_id'],
            'images' => $imagesPaths,
        ]);

        return redirect()->route('etagesvillas.index')->with('success', 'Étage de villa mis à jour avec succès.');
    }

    /**
     * Remove the specified etage villa from storage.
     */
    public function destroyetagesvillas($id)
    {
        $etagevilla = EtageVilla::findOrFail($id);
    
        // Convertir les images JSON en tableau PHP
        $images = is_string($etagevilla->images) ? json_decode($etagevilla->images, true) : $etagevilla->images;
    
        if (is_array($images)) {
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
    
        $etagevilla->delete();
    
        return redirect()->route('etagesVillas')->with('success', 'Étage de villa supprimé avec succès.');
    }
    
    public function indexe()
    {
        $etagesVillasCount = EtageVilla::count();
        return view('etagesVillas.index', compact('etagesVillasCount'));
    }
    
    public function index()
    {
        $etageVillas = EtageVilla::with(['type', 'categorie', 'ville', 'delegation', 'environnement'])
            ->orderBy('created_at', 'desc')
            ->get();
    
        $formatted = $etageVillas->map(function ($etage) {
            $images = collect(is_string($etage->images) ? json_decode($etage->images, true) : $etage->images)->map(function ($img) {
                return [
                    'url' => asset($img),
                    'path' => $img
                ];
            });
    
            return [
                'id' => $etage->id,
                'titre' => $etage->titre,
                'description' => $etage->description,
                'prix' => $etage->prix,
                'superficie' => $etage->superficie,
                'adresse' => $etage->adresse,
                'type' => $etage->type->nom ?? null,
                'categorie' => $etage->categorie->nom ?? null,
                'ville' => $etage->ville->nom ?? null,
                'delegation' => $etage->delegation->nom ?? null,
                'environnement' => $etage->environnement->nom ?? null,
                'numero_etage' => $etage->numero_etage,
                'acces_independant' => $etage->acces_independant,
                'parking_inclus' => $etage->parking_inclus,
                'annee_construction' => $etage->annee_construction,
                'images' => $images,
                'created_at' => $etage->created_at,
                'ville_id' => $etage->ville_id,
                'delegation_id' => $etage->delegation_id,
            ];
        });
    
        return response()->json($formatted);
    }

    public function show($id)
    {
        $etage = EtageVilla::with(['type', 'categorie', 'ville', 'delegation', 'environnement'])->find($id);

        if (!$etage) {
            return response()->json(['message' => 'Etage Villa non trouvé'], 404);
        }

        $images = collect($etage->images)->map(function ($img) {
            return [
                'url' => asset($img),
                'path' => $img
            ];
        });

        return response()->json([
            'id' => $etage->id,
            'titre' => $etage->titre,
            'description' => $etage->description,
            'prix' => $etage->prix,
            'superficie' => $etage->superficie,
            'adresse' => $etage->adresse,
            'type' => $etage->type->nom ?? null,
            'categorie' => $etage->categorie->nom ?? null,
            'ville' => $etage->ville->nom ?? null,
            'delegation' => $etage->delegation->nom ?? null,
            'environnement' => $etage->environnement->nom ?? null,
            'numero_etage' => $etage->numero_etage,
            'acces_independant' => $etage->acces_independant,
            'parking_inclus' => $etage->parking_inclus,
            'annee_construction' => $etage->annee_construction,
            'images' => $images,
            'created_at' => $etage->created_at,
        ]);
    }   
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:ville,id',
            'delegation_id' => 'required|exists:delegations,id',
            'environnement_id' => 'required|exists:environnements,id',
            'adresse' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|integer|min:1',
            'numero_etage' => 'required|integer|min:0',
            'acces_independant' => 'boolean',
            'parking_inclus' => 'boolean',
            'annee_construction' => 'required|integer|min:1900|max:'.date('Y'),

            'images' => 'nullable|array',
            
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('images');
        $data['acces_independant'] = $request->boolean('acces_independant');
        $data['parking_inclus'] = $request->boolean('parking_inclus');

        // Gestion des images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/etage_villas');
                $imagePaths[] = str_replace('public/', 'storage/', $path);
            }
            $data['images'] = json_encode($imagePaths);
        }

        $etageVilla = EtageVilla::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $etageVilla
        ], 201);
    }
}