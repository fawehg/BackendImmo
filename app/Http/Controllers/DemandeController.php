<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Notifications\NouvelleDemandeNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

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
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $demande = new Demande();
        $demande->domaines = $request->domaines;
        $demande->specialites = $request->specialites;
        $demande->city = $request->city;
        $demande->date = $request->date;
        $demande->time = $request->time;
        $demande->description = $request->description;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
            $demande->image = $imagePath;
        }

        $demande->save();

        $token = JWTAuth::fromUser($client);

        $response = [
            "ResultInfo" => [
                'Success' => true,
                'ErrorMessage' => "",
            ],
            "ResultData" => [
                'demande_id' => $demande->id,
                'message' => 'Demande créée avec succès',
                'token' => $token,
            ]
        ];
        return response()->json($response, 201);
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
