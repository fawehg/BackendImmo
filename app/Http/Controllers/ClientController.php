<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

class ClientController extends Controller
{
    public function signup(Request $request)
    {
        // Valider les données de la requête
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'ville' => 'required|string',
            'adresse' => 'required|string',
            'email' => 'required|email|unique:clients',
            'password' => 'required|string|min:6',
        ]);

        // Créer un nouvel utilisateur
        $client = new Client([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Sauvegarder l'utilisateur dans la base de données
        $client->save();

        // Répondre avec un message de succès
        return response()->json(['message' => 'Inscription réussie'], 201);
    }

    public function signin(Request $request)
    {
        // Valider les données de la requête
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Tentative de connexion
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Authentification réussie
            return response()->json(['message' => 'Connexion réussie']);
        } else {
            // Authentification échouée
            return response()->json(['message' => 'Email ou mot de passe incorrect'], 401);
        }
    }
}
