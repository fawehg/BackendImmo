<?php


namespace App\Http\Controllers;
use App\Models\Ferme;
use App\Models\Type;
use App\Models\Categorie;
use App\Models\Ville;
use App\Models\Delegation;
use App\Models\Orientation;
use App\Models\Environnement;
use App\Models\FermeInfrastructure;
use App\Models\FermeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\OrientationFermes;
use App\Models\InfrastructureFermes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class FermeController extends Controller

{
    public function listAnnonceFerme()
    {
        $fermes = Ferme::where('vendeur_id', Auth::guard('vendeurs')->id())
            ->with(['ville', 'delegation', 'categorie', 'type', 'environnement', 'infrastructures', 'orientation'])
            ->latest()
            ->get();
        return response()->json($fermes);
    }

    public function showAnnonceFerme($id)
    {
        $ferme = Ferme::where('vendeur_id', Auth::guard('vendeurs')->id())
            ->with(['ville', 'delegation', 'categorie', 'type', 'environnement', 'infrastructures', 'orientation'])
            ->findOrFail($id);
        return response()->json($ferme);
    }

    public function editAnnonceFerme(Request $request, $id)
    {
        $ferme = Ferme::where('vendeur_id', Auth::guard('vendeurs')->id())->findOrFail($id);
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:1',
            'systeme_irrigation' => 'boolean',
            'cloture' => 'boolean',
            'puits' => 'boolean',
            'orientation_id' => 'required|exists:orientations,id',
            'infrastructures' => 'nullable|array',
            'infrastructures.*' => 'exists:infrastructures,id',
            'ville_id' => 'required|exists:villes,id',
            'delegation_id' => 'required|exists:delegations,id',
            'categorie_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
            'environnement_id' => 'nullable|exists:environnement_fermes,id',
        ]);

        $ferme->update($validated);

        if ($request->hasFile('images')) {
            foreach ($ferme->images as $image) {
                Storage::disk('public')->delete($image);
            }
            $ferme->images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('fermes', 'public');
                $ferme->images[] = $path;
            }
            $ferme->save();
        }

        return response()->json($ferme);
    }

    public function deleteAnnonceFerme($id)
    {
        $ferme = Ferme::where('vendeur_id', Auth::guard('vendeurs')->id())->findOrFail($id);
        foreach ($ferme->images as $image) {
            Storage::disk('public')->delete($image);
        }
        $ferme->delete();
        return response()->json(['message' => 'Ferme supprimée avec succès']);
    } public function indexferme()
    {
        $fermes = Ferme::with(['type', 'categorie', 'ville', 'delegation', 'orientationFermes', 'environnement', 'infrastructures'])->get();
        return view('fermes.index', compact('fermes'));
    }public function getDelegationsByVille(Request $request)
    {
        $villeId = $request->input('ville_id');
        $delegations = Delegation::where('ville_id', $villeId)->get();
        return response()->json($delegations);
    }

    public function createferme()
    {
        $types = Type::all();
        $categories = Categorie::all();
        $villes = Ville::all();
        $delegations = Delegation::all();
        $orientations = OrientationFermes::all();
        $environnements = Environnement::all();
        $infrastructures = InfrastructureFermes::all();
                return view('fermes.create', compact('types', 'categories', 'villes', 'delegations', 'orientations', 'environnements', 'infrastructures'));
    }

    public function storeferme(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|integer|min:1',
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
            'orientation_id' => 'required|exists:orientation_fermes,id',
            'environnement_id' => 'required|exists:environnement_fermes,id',
            'infrastructures' => 'nullable|array',
            'infrastructures.*' => 'exists:infrastructure_fermes,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // Prepare data
        $data = $validated;
    
        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('fermes_images', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = json_encode($imagePaths);
        } else {
            $data['images'] = json_encode([]);
        }
    
        // Create the ferme
        $ferme = Ferme::create($data);
    
        // Attach infrastructures if provided
        if ($request->has('infrastructures')) {
            $ferme->infrastructures()->attach($validated['infrastructures']);
        }
    
        return redirect()->route('fermes.index')->with('success', 'Ferme créée avec succès.');
    }

    public function showferme($id)
    {
        $ferme = Ferme::with(['type', 'categorie', 'ville', 'delegation', 'orientation', 'environnement', 'infrastructures'])->findOrFail($id);
        return view('fermes.show', compact('ferme'));
    }

    public function editferme($id)
    {
        $ferme = Ferme::with(['infrastructures'])->findOrFail($id);
        $types = Type::all();
        $categories = Categorie::all();
        $villes = Ville::all();
        $delegations = Delegation::all();
        $orientations = OrientationFermes::all();
        $environnements = Environnement::all();
        $infrastructures = InfrastructureFermes::all();
                return view('fermes.edit', compact('ferme', 'types', 'categories', 'villes', 'delegations', 'orientations', 'environnements', 'infrastructures'));
    }

    public function updateferme(Request $request, $id)
    {
        $ferme = Ferme::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|integer|min:1',
            'adresse' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:ville,id',
            'delegation_id' => 'required|exists:delegations,id',
            'orientation_id' => 'required|exists:orientation_fermes,id',
            'environnement_id' => 'required|exists:environnements,id',
            'infrastructures' => 'nullable|array',
            'infrastructures.*' => 'exists:infrastructures,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $imagesPaths = $ferme->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($imagesPaths as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            $imagesPaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('fermes_images', 'public');
                $imagesPaths[] = $path;
            }
        }

        $ferme->update([
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'superficie' => $validated['superficie'],
            'adresse' => $validated['adresse'],
            'type_id' => $validated['type_id'],
            'categorie_id' => $validated['categorie_id'],
            'ville_id' => $validated['ville_id'],
            'delegation_id' => $validated['delegation_id'],
            'orientation_id' => $validated['orientation_id'],
            'environnement_id' => $validated['environnement_id'],
            'images' => $imagesPaths,
        ]);

        if ($request->has('infrastructures')) {
            $ferme->infrastructures()->sync($request->infrastructures);
        } else {
            $ferme->infrastructures()->detach();
        }

        return redirect()->route('fermes')->with('success', 'Ferme mise à jour avec succès.');
    }

    public function destroyferme($id)
    {
        $ferme = Ferme::findOrFail($id);

        if ($ferme->images) {
            foreach ($ferme->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $ferme->infrastructures()->detach();
        $ferme->delete();

        return redirect()->route('fermes')->with('success', 'Ferme supprimée avec succès.');
    }
    public function indexe()
    {
        $fermes = Ferme::all(); // Récupérer toutes les fermes
        return view('fermes.index', compact('fermes'));
    }
    public function index(Request $request)
    {
        try {
            // 1. Récupération des fermes avec relations
            $query = Ferme::with([
                'type',
                'categorie',
                'ville',
                'delegation',
                'orientation',
                'environnement',
                'infrastructures'
            ]);

            // Apply status filter if provided
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            $fermes = $query->orderBy('created_at', 'desc')->get();

            // Log pour vérifier les données brutes
            Log::debug('Fermes récupérées', [
                'count' => $fermes->count(),
                'first_item' => $fermes->first() ? $fermes->first()->toArray() : null
            ]);

            // 2. Formatage des données
            $formatted = $fermes->map(function ($ferme) {
                $images = collect($ferme->images)->map(function ($img) {
                    return [
                        'url' => asset('storage/' . $img),
                        'path' => $img
                    ];
                });

                return [
                    'id' => $ferme->id,
                    'titre' => $ferme->titre,
                    'description' => $ferme->description,
                    'prix' => $ferme->prix,
                    'superficie' => $ferme->superficie,
                    'adresse' => $ferme->adresse,
                    'type' => $ferme->type->nom ?? null,
                    'categorie' => $ferme->categorie->nom ?? null,
                    'ville' => $ferme->ville->nom ?? null,
                    'delegation' => $ferme->delegation->nom ?? null,
                    'orientation' => $ferme->orientation->nom ?? null,
                    'environnement' => $ferme->environnement->nom ?? null,
                    'infrastructures' => $ferme->infrastructures->pluck('nom') ?? [],
                    'images' => $images,
                    'created_at' => $ferme->created_at,
                    'updated_at' => $ferme->updated_at,
                    'status' => $ferme->status,
                    'ville_id' => $ferme->ville_id,
                    'delegation_id' => $ferme->delegation_id,
                    'categorie_id' => $ferme->categorie_id,
                    'type_transaction_id' => $ferme->type_transaction_id
                ];
            });

            // Log final avant retour
            Log::info('Réponse des fermes générée', [
                'count' => $formatted->count(),
                'sample' => $formatted->first()
            ]);

            // 3. Retour de la réponse
            return response()->json($formatted);

        } catch (\Exception $e) {
            Log::error('Erreur dans FermeController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Une erreur est survenue lors de la récupération des fermes',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show($id)
    {
        // Load the ferme with its relationships, including vendeur
        $ferme = Ferme::with([
            'type',
            'categorie',
            'ville',
            'delegation',
            'orientation',
            'environnement',
            'infrastructures',
            'vendeur' // Added vendeur relationship
        ])->find($id);
    
        if (!$ferme) {
            return response()->json(['message' => 'Ferme non trouvée'], 404);
        }
    
        // Map images to include URL and path
        $images = collect($ferme->images)->map(function ($img) {
            return [
                'url' => asset('storage/' . $img),
                'path' => $img
            ];
        });
    
        // Return the ferme data with vendeur included
        return response()->json([
            'id' => $ferme->id,
            'titre' => $ferme->titre,
            'description' => $ferme->description,
            'prix' => $ferme->prix,
            'superficie' => $ferme->superficie,
            'adresse' => $ferme->adresse,
            'type' => $ferme->type,
            'categorie' => $ferme->categorie,
            'ville' => $ferme->ville,
            'delegation' => $ferme->delegation,
            'orientation' => $ferme->orientation,
            'environnement' => $ferme->environnement,
            'infrastructures' => $ferme->infrastructures,
            'images' => $images,
            'created_at' => $ferme->created_at,
            'vendeur' => $ferme->vendeur ? [
                'id' => $ferme->vendeur->id,
                'nom' => $ferme->vendeur->nom,
                'prenom' => $ferme->vendeur->prenom,
                'email' => $ferme->vendeur->email,
                'phone' => $ferme->vendeur->phone,
            ] : null, // Include vendeur data or null if not present
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

    // 1. Validation des données
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
        'infrastructures' => 'nullable|array',
        'infrastructures.*' => 'exists:infrastructure_fermes,id',
        'orientation_id' => 'required|exists:orientation_fermes,id',
        'environnement_id' => 'required|exists:environnement_fermes,id',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // 2. Traitement des images (exactement comme vous le souhaitez)
    $imagesPaths = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('fermes_images', 'public');
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

    // 3. Création de la ferme
    $ferme = Ferme::create([
        'type_id' => $validated['type_id'],
        'categorie_id' => $validated['categorie_id'],
        'ville_id' => $validated['ville_id'],
        'delegation_id' => $validated['delegation_id'],
        'adresse' => $validated['adresse'],
        'titre' => $validated['titre'],
        'description' => $validated['description'],
        'prix' => $validated['prix'],
        'superficie' => $validated['superficie'],
        'orientation_id' => $validated['orientation_id'],
        'environnement_id' => $validated['environnement_id'],
        'images' => $imagesPaths, // Exactement comme vous le vouliez
        'vendeur_id' => $vendeurId // Ajout du vendeur
    ]);

    // 4. Attachement des infrastructures
    if ($request->has('infrastructures')) {
        $ferme->infrastructures()->attach($validated['infrastructures']);
        Log::info('Infrastructures attached', ['count' => count($validated['infrastructures'])]);
    }

    Log::info('Ferme created', ['id' => $ferme->id]);

    // 6. Retour de la réponse avec plus de relations chargées
    return response()->json([
        'message' => 'Ferme créée avec succès',
        'data' => $ferme->load([
            'infrastructures',
            'orientation',
            'environnement',
            'ville',
            'delegation',
            'categorie',
            'type',
            'vendeur'
        ])
    ], 201);
}
}