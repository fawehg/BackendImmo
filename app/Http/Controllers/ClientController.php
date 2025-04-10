<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordCode;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // Inscription d'un client
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'ville' => 'required|string',
            'adresse' => 'required|string',
            'email' => 'required|email|unique:clients',
            'password' => 'required|confirmed',
        ]);

        $client = Client::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Client enregistré avec succès', 'client' => $client], 201);
    }

    // Connexion d'un client
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = auth('clients')->attempt($credentials)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        return response()->json([
            'token' => $token,
            'client' => auth('clients')->user(),
        ]);
    }

    // Récupérer les informations du client connecté
    public function me()
    {
        return response()->json(auth('clients')->user());
    }

    // Déconnexion du client
    public function logout()
    {
        auth('clients')->logout();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $client = Client::where('email', $request->email)->first();
    
        if (!$client) {
            return response()->json([
                "ResultInfo" => [
                    'Success' => false,
                    'ErrorMessage' => "Le client avec cet e-mail n'existe pas.",
                ],
                "ResultData" => []
            ], 404);
        }
    
        $resetCode = mt_rand(100000, 999999);
    
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $resetCode,
            'created_at' => now(),
        ]);
    
        Mail::to($client->email)->send(new ResetPasswordCode($resetCode));
    
        return response()->json([
            "ResultInfo" => [
                'Success' => true,
                'ErrorMessage' => "",
            ],
            "ResultData" => [
                'message' => 'Un code de réinitialisation a été envoyé à votre adresse e-mail.'
            ]
        ]);
    }

public function verifyResetCode(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'code' => 'required|digits:6',
        'password' => 'required|string|min:6',
    ]);

    // Vérifier si le code de réinitialisation est valide
    $reset = DB::table('password_resets')
        ->where('email', $request->email)
        ->where('token', $request->code)
        ->first();

    if (!$reset) {
        return response()->json([
            "ResultInfo" => [
                'Success' => false,
                'ErrorMessage' => "Le code de réinitialisation est invalide.",
            ],
            "ResultData" => []
        ], 400);
    }

    // Remplacer User par Client
    $client = Client::where('email', $request->email)->first();
    $client->password = Hash::make($request->password);
    $client->save();

    // Supprimer le code de réinitialisation après utilisation
    DB::table('password_resets')->where('email', $request->email)->delete();

    return response()->json([
        "ResultInfo" => [
            'Success' => true,
            'ErrorMessage' => "",
        ],
        "ResultData" => [
            'message' => 'Le mot de passe a été réinitialisé avec succès.'
        ]
    ]);
}
    // Afficher le profil
    public function show()
    {
        $client = Auth::guard('clients')->user();
        return response()->json($client);
    }

    // Mettre à jour le profil
    public function update(Request $request)
    {
        $client = Auth::guard('clients')->user();

        $request->validate([
            'nom' => 'sometimes|string',
            'prenom' => 'sometimes|string',
            'ville' => 'sometimes|string',
            'adresse' => 'sometimes|string',
            'email' => 'sometimes|email|unique:clients,email,'.$client->id,
            'password' => 'sometimes|confirmed',
        ]);

        $client->update($request->all());
        return response()->json(['message' => 'Profil mis à jour', 'client' => $client]);
    }

    // Supprimer le compte
    public function destroy()
    {
        $client = Auth::guard('clients')->user();
        $client->delete();
        return response()->json(['message' => 'Compte supprimé']);
    }

}