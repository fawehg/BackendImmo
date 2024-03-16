<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordCode;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                "ResultInfo" => [
                    'Success' => false,
                    'ErrorMessage' => "L'utilisateur avec cet e-mail n'existe pas.",
                ],
                "ResultData" => []
            ], 404);
        }
    
        $resetCode = mt_rand(100000, 999999); // Générer un code de réinitialisation aléatoire
    
        // Stocker le code de réinitialisation dans la base de données
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $resetCode,
            'created_at' => now(),
        ]);
    
        // Envoyer le code de réinitialisation par e-mail
        Mail::to($user->email)->send(new ResetPasswordCode($resetCode));
    
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

    // Vérifie si le code de réinitialisation est valide
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

    // Vérification de la validité du code de réinitialisation (par exemple, expiration)

    // Mettre à jour le mot de passe de l'utilisateur
    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Supprimer l'entrée de réinitialisation de mot de passe de la base de données
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
}
