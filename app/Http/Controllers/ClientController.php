<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;

class ClientController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'ville' => 'required|string',
            'adresse' => 'required|string',
            'email' => 'required|email|unique:clients',
            'password' => 'required|string|min:6',
        ]);

        $client = new Client([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $client->save();

        return response()->json(['message' => 'Inscription réussie'], 201);
    }

    public function signin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $response = [
            "ResultInfo" => [
                'Success' => true,
                'ErrorMessage' => "",
            ],
            "ResultData" => []
        ];
        if (!$token = JWTAuth::attempt($credentials)) {
            $response["ResultInfo"]["Success"] = false;
            $response["ResultInfo"]["ErrorMessage"] = 'Adresse e-mail ou mot de passe incorrect.';
            return response()->json($response, 401);
        }
        $response["ResultInfo"]["Success"] = true;
        $response["ResultData"]['token'] = $token;
        
        return response()->json($response, 200);
        
    }

    public function show($id)
    {
        $user = Client::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = Client::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }
}
