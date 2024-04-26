<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Notifications\NouvelleDemandeNotification;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

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
                'message' => 'Demande créée avec succès'
            ]
        ], 201);
    }


    public function selectOuvrier(Request $request)
    {
        $request->validate([
            'demande_id' => 'required|exists:demandes,id',
            'ouvrier_id' => 'required|exists:users,id'
        ]);

        $demandeId = $request->input('demande_id');
        $ouvrierId = $request->input('ouvrier_id');

        $demande = Demande::findOrFail($demandeId);
        $ouvrier = User::findOrFail($ouvrierId);

        $ouvrier->notify(new NouvelleDemandeNotification($demande));

        return response()->json(['message' => 'Notification envoyée à l\'ouvrier choisi']);
    }
}