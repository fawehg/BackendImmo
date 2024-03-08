<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
//helllo
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $response = [
            "ResultInfo" => [
                'Success' => true,
                'ErrorMessage' => "",
            ],
            "ResultData" => []
        ];

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
            $response["ResultInfo"]["Success"] = false;
            $response["ResultInfo"]["ErrorMessage"] = $validator->errors();

            return response()->json($response, 400);
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
       if (!$token = JWTAuth::fromUser($user)) {
        $response["ResultInfo"]["Success"] = false;
        $response["ResultInfo"]["ErrorMessage"] = 'Erreur lors de la génération du token.';
        return response()->json($response, 401);
    }

    $response["ResultData"]['token'] = $token;
    $response["ResultData"]['user'] = $user;

    return response()->json($response, 201);
}

    public function login(Request $request)
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
        $response["ResultData"]['user'] = auth()->user(); 

        return response()->json($response, 200);
        
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
