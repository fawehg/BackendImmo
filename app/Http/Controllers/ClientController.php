<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Mail\ResetPasswordCode;
use Tymon\JWTAuth\Facades\JWTAuth;
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

    $token = JWTAuth::fromUser($client);

    $response = [
        "ResultInfo" => [
            'Success' => true,
            'ErrorMessage' => "",
        ],
        "ResultData" => [
            'token' => $token,
            'client' => $client,
        ]
    ];

    return response()->json($response, 201);
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
    
        $client = Client::where('email', $request->email)->first();
        $response["ResultData"]['client'] = $client;
    
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);

        return response()->json($client);
    }

    public function update(Request $request, $id)
    {
        $user = Client::findOrFail($id);
        $user->update($request->all());
        return response()->json($user);
    }
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
    
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
    
        $response = [
            "ResultInfo" => [
                'Success' => true,
                'ErrorMessage' => "",
            ],
            "ResultData" => [
                'message' => 'Un code de réinitialisation a été envoyé à votre adresse e-mail.'
            ]
        ];
    
        return response()->json($response, 200);
    }
    
    public function verifyResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|digits:6',
            'password' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
    
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
            ], 400);        }
    
        $client = Client::where('email', $request->email)->first();
        $client->password = Hash::make($request->password);
        $client->save();
    
        DB::table('password_resets')->where('email', $request->email)->delete();
    
        $response = [
            "ResultInfo" => [
                'Success' => true,
                'ErrorMessage' => "",
            ],
            "ResultData" => [
                'message' => 'Le mot de passe a été réinitialisé avec succès.'
            ]
        ];
    
        return response()->json($response, 200);
    }
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            $response = [
                "ResultInfo" => [
                    'Success' => true,
                    'ErrorMessage' => "",
                ],
                "ResultData" => [
                    'message' => 'Déconnexion réussie.'
                ]
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                "ResultInfo" => [
                    'Success' => false,
                    'ErrorMessage' => "Une erreur s'est produite lors de la déconnexion.",
                ],
                "ResultData" => []
            ], 500);
        }
    }

}
