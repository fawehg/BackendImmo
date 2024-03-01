<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email|unique:users',
            'ville' => 'required|string',
            'adresse' => 'required|string',
            'password' => 'required|string',
            'confirmationMotDePasse' => 'required|string|same:password',
            'profession' => 'required|string',
            'specialties' => 'array',
            'joursDisponibilite' => 'array',
            'heureDebut' => 'required|string',
            'heureFin' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
            'password' => bcrypt($request->password),
            'profession' => $request->profession,
            'specialties' => $request->specialties,
            'joursDisponibilite' => $request->joursDisponibilite,
            'heureDebut' => $request->heureDebut,
            'heureFin' => $request->heureFin,
        ]);

        return response()->json(['message' => 'Inscription réussie !'], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['user' => $user, 'message' => 'Connexion réussie !'], 200);
        } else {
            return response()->json(['message' => 'Adresse e-mail ou mot de passe incorrect.'], 401);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }
}
