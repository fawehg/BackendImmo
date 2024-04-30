<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Notifications\NouvelleDemandeNotification;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

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


    // Récupérer le client à partir du token
    $client = Auth::guard('client_api')->user();

    // Créer une demande avec les données de la requête
    $demande = new Demande($request->only('domaines', 'specialites', 'city', 'date', 'time', 'description'));

    // Enregistrer la demande

    // Récupérer l'ouvrier sélectionné
    $ouvrierId = $request->input('ouvrier_id');
    $ouvrier = User::findOrFail($ouvrierId);

    // Envoyer la notification à l'ouvrier
    $ouvrier->notify(new NouvelleDemandeNotification($demande, $client));

    return response()->json(['message' => 'Notification envoyée à l\'ouvrier choisi']);
}
}