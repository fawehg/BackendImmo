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

{ public function listAnnonceEtageVilla()
    {
        $etages = EtageVilla::where('vendeur_id', Auth::guard('vendeurs')->id())
            ->with(['ville', 'delegation', 'categorie', 'type', 'environnement'])
            ->latest()
            ->get();
        
        return response()->json($etages);
    }
    
    public function showAnnonceEtageVilla($id)
    {
        $etage = EtageVilla::where('vendeur_id', Auth::guard('vendeurs')->id())
            ->with(['ville', 'delegation', 'categorie', 'type', 'environnement'])
            ->findOrFail($id);
        
        return response()->json($etage);
    }
    
    public function editAnnonceEtageVilla(Request $request, $id)
    {
        $etage = EtageVilla::where('vendeur_id', Auth::guard('vendeurs')->id())->findOrFail($id);
    
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:1',
            'numero_etage' => 'required|integer|min:0',
            'acces_independant' => 'boolean',
            'parking_inclus' => 'boolean',
            'annee_construction' => 'nullable|integer|min:1900|max:' . date('Y'),
            'ville_id' => 'required|exists:villes,id',
            'delegation_id' => 'required|exists:delegations,id',
            'categorie_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
            'environnement_id' => 'nullable|exists:environnements,id',
        ]);
    
        $etage->update($validated);
    
        // Handling images if provided
        if ($request->hasFile('images')) {
            // Deleting old images
            foreach ($etage->images as $image) {
                Storage::disk('public')->delete($image);
            }
    
            $etage->images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('etage_villas', 'public');
                $etage->images[] = $path;
            }
            $etage->save();
        }
    
        return response()->json($etage);
    }
    
    public function deleteAnnonceEtageVilla($id)
    {
        $etage = EtageVilla::where('vendeur_id', Auth::guard('vendeurs')->id())->findOrFail($id);
    
        // Deleting images
        foreach ($etage->images as $image) {
            Storage::disk('public')->delete($image);
        }
    
        $etage->delete();
    
        return response()->json(['message' => 'Étage de villa supprimé avec succès']);
    }
    
     public function indexetagesvillas()
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
    
    public function index(Request $request)
    {
        try {
            // 1. Récupération des étages de villas avec relations
            $query = EtageVilla::with([
                'type',
                'categorie',
                'ville',
                'delegation',
                'environnement'
            ]);

            // Apply status filter if provided
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            $etageVillas = $query->orderBy('created_at', 'desc')->get();

            // Log pour vérifier les données brutes
            Log::debug('Étages de villas récupérés', [
                'count' => $etageVillas->count(),
                'first_item' => $etageVillas->first() ? $etageVillas->first()->toArray() : null
            ]);

            // 2. Formatage des données
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
                    'updated_at' => $etage->updated_at,
                    'status' => $etage->status,
                    'ville_id' => $etage->ville_id,
                    'delegation_id' => $etage->delegation_id,
                    'categorie_id' => $etage->categorie_id,
                    'type_transaction_id' => $etage->type_transaction_id
                ];
            });

            // Log final avant retour
            Log::info('Réponse des étages de villas générée', [
                'count' => $formatted->count(),
                'sample' => $formatted->first()
            ]);

            // 3. Retour de la réponse
            return response()->json($formatted);

        } catch (\Exception $e) {
            Log::error('Erreur dans EtageVillaController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Une erreur est survenue lors de la récupération des étages de villas',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show($id)
    {
        $etage = EtageVilla::with(['type', 'categorie', 'ville', 'delegation', 'environnement', 'vendeur'])->find($id);

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
            'vendeur' => $etage->vendeur ? [
                'id' => $etage->vendeur->id,
                'nom' => $etage->vendeur->nom,
                'prenom' => $etage->vendeur->prenom,
                'email' => $etage->vendeur->email,
                'phone' => $etage->vendeur->phone ?? null,
            ] : null,
        ]);
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Log des données reçues
        Log::info('Requête reçue pour store etage_villa', [
            'files' => $request->hasFile('images'),
            'images_count' => count($request->file('images') ?? []),
            'data' => $request->all(),
        ]);

        // 3. Traitement des images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/etage_villas');
                $imagesPaths[] = str_replace('public/', 'storage/', $path);
            }
            $data['images'] = json_encode($imagesPaths);
        } else {
            Log::info('Aucune image envoyée dans la requête');
        }

        // 4. Récupération de l'ID du vendeur connecté
        $vendeurId = auth('vendeurs')->id();
        if (!$vendeurId) {
            Log::error('Utilisateur non authentifié après validation JWT');
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        // 5. Création de l'étage de villa avec vendeur_id
        $data = $request->except('images');
        $data['acces_independant'] = $request->boolean('acces_independant');
        $data['parking_inclus'] = $request->boolean('parking_inclus');
        $data['images'] = $imagesPaths ? json_encode($imagesPaths) : null;
        $data['vendeur_id'] = $vendeurId;

        $etageVilla = EtageVilla::create($data);

        // 6. Log de l'étage de villa créé
        Log::info('Étage de villa créé', [
            'etage_villa_id' => $etageVilla->id,
            'images' => $etageVilla->images,
            'vendeur_id' => $etageVilla->vendeur_id,
        ]);

        // 7. Retour de la réponse
        return response()->json([
            'message' => 'Étage de villa créé avec succès',
            'status' => 'success',
            'data' => $etageVilla->load([
                'ville',
                'delegation',
                'categorie',
                'type',
                'environnement',
            ])
        ], 201);
    }
}