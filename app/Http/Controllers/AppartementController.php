<?php
namespace App\Http\Controllers;

use App\Models\Appartement;
use App\Models\EnvironnementApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Ville;
use App\Models\Delegation;
use App\Models\Categorie;
use App\Models\Type;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AppartementController extends Controller
{
    public function listAnnonceAppartement()
    {
        $appartements = Appartement::where('vendeur_id', Auth::guard('vendeurs')->id())
            ->with(['ville', 'delegation', 'categorie', 'typeTransaction', 'environnementsApp'])
            ->latest()
            ->get();
        return response()->json($appartements);
    }

public function showAnnonceAppartement($id)
{
    $vendeurId = Auth::guard('vendeurs')->id();

    $appartement = Appartement::with(['ville', 'delegation', 'categorie', 'typeTransaction', 'environnementsApp'])
        ->where('id', $id)
        ->where('vendeur_id', $vendeurId)
        ->firstOrFail();

    return response()->json($appartement);
}

    
    public function editAnnonceAppartement(Request $request, $id)
    {
        $appartement = Appartement::where('vendeur_id', Auth::guard('vendeurs')->id())->findOrFail($id);
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:1',
            'nombre_chambres' => 'nullable|integer|min:0',
            'nombre_pieces' => 'nullable|integer|min:0',
            'etage' => 'nullable|integer|min:0',
            'superficie_couvert' => 'nullable|numeric|min:0',
            'meuble' => 'boolean',
            'ville_id' => 'required|exists:villes,id',
            'delegation_id' => 'required|exists:delegations,id',
            'categorie_id' => 'required|exists:categories,id',
            'type_transaction_id' => 'required|exists:types,id',
            'environnements_app' => 'nullable|array',
            'environnements_app.*' => 'exists:environnementapp,id',
        ]);

        $appartement->update($validated);

        if ($request->hasFile('images')) {
            foreach ($appartement->images as $image) {
                Storage::disk('public')->delete($image);
            }
            $appartement->images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('appartements', 'public');
                $appartement->images[] = $path;
            }
            $appartement->save();
        }

        return response()->json($appartement);
    }

    public function deleteAnnonceAppartement($id)
    {
        $appartement = Appartement::where('vendeur_id', Auth::guard('vendeurs')->id())->findOrFail($id);
        foreach ($appartement->images as $image) {
            Storage::disk('public')->delete($image);
        }
        $appartement->delete();
        return response()->json(['message' => 'Appartement supprimé avec succès']);
    }
    public function getDelegationsByVille(Request $request)
    {
        $villeId = $request->input('ville_id');
        $delegations = Delegation::where('ville_id', $villeId)->get();
        return response()->json($delegations);
    }
    public function indexappartement()
    {
        $appartements = Appartement::with(['ville', 'delegation', 'categorie', 'typeTransaction'])->get();
        return view('appartements.index', compact('appartements'));
    }

    public function createappartement()
    {
        // Fetch data for dropdowns or form inputs
        $villes = Ville::all();
        $delegations = Delegation::all();
        $categories = Categorie::all();
        $types = Type::all();
        $environnements = EnvironnementApp::all();

        return view('appartements.create', compact('villes', 'delegations', 'categories', 'types', 'environnements'));
    }

    public function storeappartement(Request $request)
{
    $validated = $request->validate([
        'type_transaction_id' => 'required|exists:types,id',
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
        'adresse' => 'required|string|max:255',
        'titre' => 'required|string|max:255',
        'description' => 'required|string',
        'prix' => 'required|numeric|min:0',
        'superficie' => 'required|integer|min:1',
        'superficie_couvert' => 'nullable|integer|min:0',
        'etage' => 'nullable|integer|min:0',
        'meuble' => 'sometimes|boolean',
        'environnements_app' => 'nullable|array',
        'environnements_app.*' => 'exists:environnementapp,id',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Prepare data
    $data = $validated;
    $data['meuble'] = $request->boolean('meuble', false);

    // Handle image uploads
    if ($request->hasFile('images')) {
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('appartements_images', 'public');
            $imagePaths[] = $path;
        }
        $data['images'] = json_encode($imagePaths);
    } else {
        $data['images'] = json_encode([]);
    }

    // Create the apartment
    $appartement = Appartement::create($data);

    // Attach environments if provided
    if ($request->has('environnements_app')) {
        $appartement->environnementsApp()->attach($request->environnements_app);
    }

    return redirect()->route('appartements.index')->with('success', 'Appartement créé avec succès.');
}

    public function showappartement($id)
    {
        $appartement = Appartement::with(['ville', 'delegation', 'categorie', 'typeTransaction', 'environnementsApp'])->findOrFail($id);
        return view('appartements.show', compact('appartement'));
    }

    public function editappartement($id)
    {
        $appartement = Appartement::findOrFail($id);
        $villes = Ville::all();
        $delegations = Delegation::all();
        $categories = Categorie::all();
        $types = Type::all();
        $environnements = EnvironnementApp::all();

        return view('appartements.edit', compact('appartement', 'villes', 'delegations', 'categories', 'types', 'environnements'));
    }

    public function updateappartement(Request $request, $id)
    {
        $appartement = Appartement::findOrFail($id);

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
            'superficie_couvert' => 'nullable|integer|min:0',
            'etage' => 'nullable|integer|min:0',
            'meuble' => 'sometimes|boolean',
            'environnements_app' => 'nullable|array',
            'environnements_app.*' => 'exists:environnementapp,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle image uploads
        $imagesPaths = $appartement->images ?? [];
        if ($request->hasFile('images')) {
            // Optionally delete old images
            foreach ($imagesPaths as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
            $imagesPaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('appartements_images', 'public');
                $imagesPaths[] = $path;
            }
        }

        // Update the apartment
        $appartement->update([
            'type_transaction_id' => $validated['type_transaction_id'],
            'categorie_id' => $validated['categorie_id'],
            'ville_id' => $validated['ville_id'],
            'delegation_id' => $validated['delegation_id'],
            'adresse' => $validated['adresse'],
            'titre' => $validated['titre'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'superficie' => $validated['superficie'],
            'superficie_couvert' => $validated['superficie_couvert'] ?? null,
            'etage' => $validated['etage'] ?? null,
            'meuble' => $validated['meuble'] ?? false,
            'images' => $imagesPaths,
        ]);

        // Sync environments
        if ($request->has('environnements_app')) {
            $appartement->environnementsApp()->sync($request->environnements_app);
        } else {
            $appartement->environnementsApp()->detach();
        }

        return redirect()->route('clients')->with('success', 'Appartement mis à jour avec succès.');
    }

    public function destroyappartement($id)
    {
        $appartement = Appartement::findOrFail($id);

        // Delete associated images
        if ($appartement->images) {
            foreach ($appartement->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Detach environments
        $appartement->environnementsApp()->detach();

        // Delete the apartment
        $appartement->delete();

        return redirect()->route('dashboard')->with('success', 'Appartement supprimé avec succès.');
    }
    public function indexe()
    {
        // Logique pour récupérer les appartements, par exemple :
        $appartementsCount = Appartement::count(); // Vous pouvez ajuster en fonction de votre modèle et logique
        return view('appartements.index', compact('appartementsCount'));
    }
    public function index(Request $request)
    {
        try {
            // 1. Récupération des appartements avec relations
            $query = Appartement::with([
                'ville',
                'delegation',
                'categorie',
                'typeTransaction',
                'environnementsApp'
            ]);

            // Apply status filter if provided
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            $appartements = $query->orderBy('created_at', 'desc')->get();

            // Log pour vérifier les données brutes
            Log::debug('Appartements récupérés', [
                'count' => $appartements->count(),
                'first_item' => $appartements->first() ? $appartements->first()->toArray() : null
            ]);

            // 2. Formatage des données
            $formatted = $appartements->map(function ($appartement) {
                $images = array_map(function ($image) {
                    return [
                        'url' => asset('storage/' . $image),
                        'path' => $image
                    ];
                }, $appartement->images ?? []);

                return [
                    'id' => $appartement->id,
                    'titre' => $appartement->titre,
                    'description' => $appartement->description,
                    'prix' => $appartement->prix,
                    'superficie' => $appartement->superficie,
                    'superficie_couvert' => $appartement->superficie_couvert,
                    'etage' => $appartement->etage,
                    'meuble' => $appartement->meuble,
                    'adresse' => $appartement->adresse,
                    'ville' => $appartement->ville->nom ?? null,
                    'delegation' => $appartement->delegation->nom ?? null,
                    'categorie' => $appartement->categorie->nom ?? null,
                    'type' => $appartement->typeTransaction->nom ?? null,
                    'environnements' => $appartement->environnementsApp->pluck('nom') ?? [],
                    'images' => $images,
                    'created_at' => $appartement->created_at,
                    'updated_at' => $appartement->updated_at,
                    'status' => $appartement->status,
                    'ville_id' => $appartement->ville_id,
                    'delegation_id' => $appartement->delegation_id,
                    'categorie_id' => $appartement->categorie_id,
                    'type_transaction_id' => $appartement->type_transaction_id
                ];
            });

            // Log final avant retour
            Log::info('Réponse des appartements générée', [
                'count' => $formatted->count(),
                'sample' => $formatted->first()
            ]);

            // 3. Retour de la réponse
            return response()->json($formatted);

        } catch (\Exception $e) {
            Log::error('Erreur dans AppartementController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Une erreur est survenue lors de la récupération des appartements',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
public function show($id)
{
    $appartement = Appartement::with(['ville', 'delegation', 'categorie', 'typeTransaction', 'environnementsApp', 'vendeur'])->find($id);

    if (!$appartement) {
        return response()->json(['message' => 'Appartement non trouvé'], 404);
    }

    $images = array_map(function ($image) {
        return [
            'url' => asset('storage/' . $image),
            'path' => $image
        ];
    }, $appartement->images ?? []);

    $formatted = [
        'id' => $appartement->id,
        'titre' => $appartement->titre,
        'description' => $appartement->description,
        'prix' => $appartement->prix,
        'superficie' => $appartement->superficie,
        'superficie_couvert' => $appartement->superficie_couvert,
        'etage' => $appartement->etage,
        'meuble' => $appartement->meuble,
        'ville' => $appartement->ville->nom ?? null,
        'delegation' => $appartement->delegation->nom ?? null,
        'categorie' => $appartement->categorie->nom ?? null,
        'type' => $appartement->typeTransaction->nom ?? null,
        'environnements' => $appartement->environnementsApp->pluck('nom'),
        'images' => $images,
        'adresse' => $appartement->adresse,
        'created_at' => $appartement->created_at,
        'updated_at' => $appartement->updated_at,
        'vendeur' => $appartement->vendeur ? [
            'id' => $appartement->vendeur->id,
            'nom' => $appartement->vendeur->nom,
            'prenom' => $appartement->vendeur->prenom,
            'email' => $appartement->vendeur->email,
            'phone' => $appartement->vendeur->phone,
        ] : null,
    ];

    return response()->json($formatted);
}
public function store(Request $request)
{
    // Log request headers for debugging
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
        'superficie' => 'required|integer|min:1',
        'superficie_couvert' => 'nullable|integer|min:0',
        'etage' => 'nullable|integer|min:0',
        'meuble' => 'sometimes|boolean',
        'environnements_app' => 'nullable|array',
        'environnements_app.*' => 'exists:environnementapp,id',
        'images' => 'nullable|array',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // 2. Log des données reçues
    Log::info('Requête reçue pour store appartement', [
        'files' => $request->hasFile('images'),
        'images_count' => count($request->file('images') ?? []),
        'validated' => $validated,
    ]);

    // 3. Traitement des images
    $imagesPaths = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            if ($image->isValid()) {
                $path = $image->store('appartements_images', 'public');
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
        Log::error('Utilisateur non authentifié après validation JWT');
        return response()->json(['message' => 'Utilisateur non authentifié'], 401);
    }

    // 5. Création de l'appartement avec vendeur_id
    $appartement = Appartement::create([
        'type_transaction_id' => $validated['type_transaction_id'],
        'categorie_id' => $validated['categorie_id'],
        'ville_id' => $validated['ville_id'],
        'delegation_id' => $validated['delegation_id'],
        'adresse' => $validated['adresse'],
        'titre' => $validated['titre'],
        'description' => $validated['description'],
        'prix' => $validated['prix'],
        'superficie' => $validated['superficie'],
        'superficie_couvert' => $validated['superficie_couvert'] ?? null,
        'etage' => $validated['etage'] ?? null,
        'meuble' => $validated['meuble'] ?? false,
        'images' => $imagesPaths,
        'vendeur_id' => $vendeurId, // Added vendeur_id
    ]);

    // 6. Attacher les environnements
    if ($request->has('environnements_app')) {
        $appartement->environnementsApp()->attach($request->environnements_app);
    }

    // 7. Log de l'appartement créé
    Log::info('Appartement créé', [
        'appartement_id' => $appartement->id,
        'images' => $appartement->images,
        'vendeur_id' => $appartement->vendeur_id,
    ]);

    // 8. Retour de la réponse
    return response()->json([
        'message' => 'Appartement créé avec succès',
        'data' => $appartement->load([
            'ville',
            'delegation',
            'categorie',
            'typeTransaction',
            'environnementsApp'
        ])
    ], 201);
}
}