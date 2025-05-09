<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use App\Models\Villa;
use App\Models\Environnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VillaController extends Controller
{
    public function indexvillas()
    {
        try {
            $villas = Villa::with(['type', 'categorie', 'ville', 'delegation', 'environnement'])
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Villas loaded for index view', ['count' => $villas->count()]);

            return view('villas.index', compact('villas'));
        } catch (Exception $e) {
            Log::error('Error in VillaController@indexetagesvillas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement des villas.');
        }
    }

    /**
     * Show the form for creating a new villa.
     *
     * @return \Illuminate\View\View
     */
    public function createvilla()
    {
        try {
            $types = Type::all();
            $categories = Categorie::all();
            $villes = Ville::all();
            $environnements = Environnement::all();

            return view('villas.create', compact('types', 'categories', 'villes', 'environnements'));
        } catch (Exception $e) {
            Log::error('Error in VillaController@createvilla', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('villas.index')->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Store a newly created villa in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storevilla(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'required|string',
                'prix' => 'required|numeric|min:0',
                'superficie' => 'required|numeric|min:1',
                'superficie_jardin' => 'nullable|numeric|min:0',
                'chambres' => 'required|integer|min:0',
                'pieces' => 'required|integer|min:0',
                'etages' => 'required|integer|min:0',
                'annee_construction' => 'nullable|integer|min:1900|max:' . date('Y'),
                'meuble' => 'nullable|boolean',
                'jardin' => 'nullable|boolean',
                'piscine' => 'nullable|boolean',
                'piscine_privee' => 'nullable|boolean',
                'garage' => 'nullable|boolean',
                'cave' => 'nullable|boolean',
                'terrasse' => 'nullable|boolean',
                'adresse' => 'required|string|max:255',
                'type_id' => 'required|exists:types,id',
                'categorie_id' => 'required|exists:categories,id',
                'ville_id' => 'required|exists:villes,id',
                'delegation_id' => 'required|exists:delegations,id',
                'environnement_id' => 'nullable|exists:environnements,id',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('villas_images', 'public');
                    $imagePaths[] = $path;
                }
            }
            $validated['images'] = $imagePaths;

            // Convert checkbox values to boolean
            $checkboxes = ['meuble', 'jardin', 'piscine', 'piscine_privee', 'garage', 'cave', 'terrasse'];
            foreach ($checkboxes as $checkbox) {
                $validated[$checkbox] = $request->has($checkbox) ? true : false;
            }

            Villa::create($validated);

            Log::info('Villa created successfully', ['titre' => $validated['titre']]);

            return redirect()->route('villas.index')->with('success', 'Villa ajoutée avec succès.');
        } catch (Exception $e) {
            Log::error('Error in VillaController@storevilla', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout de la villa.')->withInput();
        }
    }

    /**
     * Display the specified villa.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showvilla($id)
    {
        try {
            $villa = Villa::with(['type', 'categorie', 'ville', 'delegation', 'environnement'])->findOrFail($id);

            Log::info('Villa loaded for show view', ['id' => $id]);

            return view('villas.show', compact('villa'));
        } catch (Exception $e) {
            Log::error('Error in VillaController@showvilla', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('villas.index')->with('error', 'Une erreur est survenue lors du chargement de la villa.');
        }
    }

    /**
     * Show the form for editing the specified villa.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function editvilla($id)
    {
        try {
            $villa = Villa::findOrFail($id);
            $types = Type::all();
            $categories = Categorie::all();
            $villes = Ville::all();
            $environnements = Environnement::all();

            Log::info('Villa loaded for edit view', ['id' => $id]);

            return view('villas.edit', compact('villa', 'types', 'categories', 'villes', 'environnements'));
        } catch (Exception $e) {
            Log::error('Error in VillaController@editvilla', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('villas.index')->with('error', 'Une erreur est survenue lors du chargement du formulaire de modification.');
        }
    }

    /**
     * Update the specified villa in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatevilla(Request $request, $id)
    {
        try {
            $villa = Villa::findOrFail($id);

            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'required|string',
                'prix' => 'required|numeric|min:0',
                'superficie' => 'required|numeric|min:1',
                'superficie_jardin' => 'nullable|numeric|min:0',
                'chambres' => 'required|integer|min:0',
                'pieces' => 'required|integer|min:0',
                'etages' => 'required|integer|min:0',
                'annee_construction' => 'nullable|integer|min:1900|max:' . date('Y'),
                'meuble' => 'nullable|boolean',
                'jardin' => 'nullable|boolean',
                'piscine' => 'nullable|boolean',
                'piscine_privee' => 'nullable|boolean',
                'garage' => 'nullable|boolean',
                'cave' => 'nullable|boolean',
                'terrasse' => 'nullable|boolean',
                'adresse' => 'required|string|max:255',
                'type_id' => 'required|exists:types,id',
                'categorie_id' => 'required|exists:categories,id',
                'ville_id' => 'required|exists:villes,id',
                'delegation_id' => 'required|exists:delegations,id',
                'environnement_id' => 'nullable|exists:environnements,id',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $imagePaths = is_array($villa->images) ? $villa->images : [];
            if ($request->hasFile('images')) {
                // Optionally delete old images
                foreach ($imagePaths as $path) {
                    Storage::disk('public')->delete($path);
                }
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('villas_images', 'public');
                    $imagePaths[] = $path;
                }
            }
            $validated['images'] = $imagePaths;

            // Convert checkbox values to boolean
            $checkboxes = ['meuble', 'jardin', 'piscine', 'piscine_privee', 'garage', 'cave', 'terrasse'];
            foreach ($checkboxes as $checkbox) {
                $validated[$checkbox] = $request->has($checkbox) ? true : false;
            }

            $villa->update($validated);

            Log::info('Villa updated successfully', ['id' => $id, 'titre' => $validated['titre']]);

            return redirect()->route('villas.index')->with('success', 'Villa mise à jour avec succès.');
        } catch (Exception $e) {
            Log::error('Error in VillaController@updatevilla', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de la villa.')->withInput();
        }
    }

    /**
     * Remove the specified villa from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyetagesvillas($id)
    {
        try {
            $villa = Villa::findOrFail($id);

            // Delete associated images
            if (is_array($villa->images)) {
                foreach ($villa->images as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            $villa->delete();

            Log::info('Villa deleted successfully', ['id' => $id]);

            return redirect()->route('villas.index')->with('success', 'Villa supprimée avec succès.');
        } catch (Exception $e) {
            Log::error('Error in VillaController@destroyetagesvillas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('villas')->with('error', 'Une erreur est survenue lors de la suppression de la villa.');
        }
    }
    public function indexe()
{
    $terrainsCount = Villa::count();
    return view('villas.index', compact('villaCount'));
}
public function index(Request $request)
    {
        try {
            // 1. Récupération des villas avec relations
            $query = Villa::with([
                'ville',
                'delegation',
                'categorie',
                'type',
                'environnement'
            ]);

            // Apply status filter if provided
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            $villas = $query->orderBy('created_at', 'desc')->get();

            // Log pour vérifier les données brutes
            Log::debug('Villas récupérées', [
                'count' => $villas->count(),
                'first_item' => $villas->first() ? $villas->first()->toArray() : null
            ]);

            // 2. Formatage des données
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
                    'ville' => $villa->ville->nom ?? null,
                    'delegation' => $villa->delegation->nom ?? null,
                    'categorie' => $villa->categorie->nom ?? null,
                    'type' => $villa->type->nom ?? null,
                    'environnement' => $villa->environnement->nom ?? null,
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
                    'updated_at' => $villa->updated_at,
                    'status' => $villa->status,
                    'ville_id' => $villa->ville_id,
                    'delegation_id' => $villa->delegation_id,
                    'categorie_id' => $villa->categorie_id,
                    'type_transaction_id' => $villa->type_transaction_id
                ];
            });

            // Log final avant retour
            Log::info('Réponse des villas générée', [
                'count' => $formattedVillas->count(),
                'sample' => $formattedVillas->first()
            ]);

            // 3. Retour de la réponse
            return response()->json($formattedVillas);

        } catch (\Exception $e) {
            Log::error('Erreur dans VillaController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Une erreur est survenue lors de la récupération des villas',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    public function show($id)
    {
        // Load the villa with its relationships, including vendeur
        $villa = Villa::with([
            'ville',
            'delegation',
            'categorie',
            'type',
            'environnement',
            'vendeur' // Added vendeur relationship
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
            'ville' => $villa->ville ? ['id' => $villa->ville->id, 'nom' => $villa->ville->nom] : null,
            'delegation' => $villa->delegation ? ['id' => $villa->delegation->id, 'nom' => $villa->delegation->nom] : null,
            'categorie' => $villa->categorie ? ['id' => $villa->categorie->id, 'nom' => $villa->categorie->nom] : null,
            'type' => $villa->type ? ['id' => $villa->type->id, 'nom' => $villa->type->nom] : null,
            'environnement' => $villa->environnement ? ['id' => $villa->environnement->id, 'nom' => $villa->environnement->nom] : null,
            'jardin' => $villa->jardin,
            'piscine' => $villa->piscine,
            'etages' => $villa->etages,
            'superficie_jardin' => $villa->superficie_jardin,
            'piscine_privee' => $villa->piscine_privee,
            'garage' => $villa->garage,
            'cave' => $villa->cave,
            'terrasse' => $villa->terrasse,
            'images' => $images,
            'vendeur' => $villa->vendeur ? [
                'id' => $villa->vendeur->id,
                'nom' => $villa->vendeur->nom,
                'prenom' => $villa->vendeur->prenom,
                'email' => $villa->vendeur->email,
                'phone' => $villa->vendeur->phone,
            ] : null
        ]);
    }
    public function store(Request $request)
    {
        // Log des informations de requête
        Log::info('Request headers', $request->headers->all());

        // Log authentication state
        Log::info('Authentication state', [
            'guard' => 'vendeurs',
            'user' => auth('vendeurs')->user(),
            'id' => auth('vendeurs')->id(),
            'token' => $request->bearerToken(),
        ]);
    
        // Validation des données
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
    
        // Traitement des images (exactement comme vous le souhaitez)
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('villas_images', 'public');
                $imagesPaths[] = $path;
            }
            Log::info('Images stored', ['paths' => $imagesPaths]);
        }
    
        // Récupération de l'ID du vendeur
        $vendeurId = auth('vendeurs')->id();
        if (!$vendeurId) {
            Log::error('Vendeur not authenticated');
            return response()->json(['message' => 'Authentification requise'], 401);
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
            'images' => $imagesPaths, // Exactement comme vous le vouliez
            'vendeur_id' => $vendeurId // Ajout du vendeur
        ]);
    
        Log::info('Villa created', ['id' => $villa->id]);
    
        return response()->json([
            'message' => 'Villa créée avec succès',
            'data' => $villa->load([
                'ville',
                'delegation',
                'categorie',
                'type',
                'environnement',
                'vendeur'
            ])
        ], 201);
    }


}