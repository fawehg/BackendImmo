<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Notifications\NouvelleDemandeNotification;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\TravailDemander;

class DemandeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'domaines' => 'required|string',
            'specialites' => 'required|string',
            'city' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $client = Auth::guard('client_api')->user();

        if (!$client) {
            return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
        }

        $demande = new Demande($request->only('domaines', 'specialites', 'city', 'date', 'time', 'description'));

        if ($request->hasFile('image')) {
            $demande->image = $request->file('image')->store('uploads', 'public');
        }

        $demande->save();

        $token = JWTAuth::fromUser($client);

        return response()->json([
            'Success' => true,
            'ErrorMessage' => '',
            'ResultData' => [
                'demande' => $demande,
                'client_token' => $token,
           'demande_id' => $demande->id, 

                'message' => 'Demande créée avec succès'
            ]
        ], 201);
    }



    
    public function selectOuvrier(Request $request)
{
    $client = Auth::guard('client_api')->user();

    if (!$client) {
        return response()->json(['error' => 'Utilisateur non authentifié'], 401);
    }

    // Récupérer la demande depuis la base de données
    $demande = Demande::first(); // Vous pouvez ajouter des conditions de recherche ici si nécessaire

    if (!$demande) {
        return response()->json(['error' => 'Demande non trouvée'], 404);
    }

        $travailDemander = new TravailDemander();
        $travailDemander->client_id = $client->id;
        $travailDemander->demande_id = $demande->id; 
        $travailDemander->save();
    
        $ouvrierId = $request->input('ouvrier_id');
        $ouvrier = User::findOrFail($ouvrierId);
    
        if (!$ouvrier) {
            return response()->json(['error' => 'Ouvrier non trouvé'], 404);
        }
    
        $ouvrier->notify(new NouvelleDemandeNotification($demande, $client));
    
        $clientInfo = [
            'Nom du client' => $client->nom,
            'Prénom du client' => $client->prenom,
            'Adresse du client' => $client->adresse,
            'Email du client' => $client->email,
        ];
    
        $demandeInfo = [
            'Domaines' => $demande->domaines,
            'Spécialités' => $demande->specialites,
            'Ville' => $demande->city,
            'Description' => $demande->description,
        ];
    
        return response()->json([
            'message' => 'Notification envoyée à louvrier choisi',
            'client' => $clientInfo,
            'demande' => $demandeInfo
        ]);
    }
    public function travailDemander(Request $request)
    {
        $client = Auth::guard('client_api')->user();
        
        if (!$client) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }
    
        // Valider les données de la demande
        $request->validate([
            'domaines' => 'required|string',
            'specialites' => 'required|string',
            'city' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'description' => 'required|string',
        ]);
    
        // Créer une demande avec les données de la requête
        $demande = new Demande($request->only('domaines', 'specialites', 'city', 'date', 'time', 'description'));
    
        // Vérifier si la demande est créée avec succès
        if (!$demande->save()) {
            return response()->json(['error' => 'Impossible de créer la demande'], 500);
        }
    
        // Assigner l'ID du client à la demande
        $demande->client_id = $client->id;
    
        // Enregistrer la demande
        $demande->save();
    
        // Enregistrer la relation entre le client et la demande dans la table travaildemander
        $travailDemander = new TravailDemander();
        $travailDemander->client_id = $client->id;
        $travailDemander->demande_id = $demande->id; 
        $travailDemander->save();
    
        // Récupérer les informations du client et de la demande
        $clientInfo = [
            'Nom du client' => $client->nom,
            'Prénom du client' => $client->prenom,
            'Adresse du client' => $client->adresse,
            'Email du client' => $client->email,
        ];
    
        $demandeInfo = [
            'Domaines' => $demande->domaines,
            'Spécialités' => $demande->specialites,
            'Ville' => $demande->city,
            'Description' => $demande->description,
        ];
    
        return response()->json([
            'client' => $clientInfo,
            'demande' => $demandeInfo
        ]);
    }
    
}