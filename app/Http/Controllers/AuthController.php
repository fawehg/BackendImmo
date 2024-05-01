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
use Illuminate\Support\Facades\Storage; 

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
              
                'heureDebut' => 'required|string',
                'heureFin' => 'required|string',
                'numeroTelephone' => 'required|string', 
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|', 
            ]);
        
            if ($validator->fails()) {
                $response["ResultInfo"]["Success"] = false;
                $response["ResultInfo"]["ErrorMessage"] = $validator->errors();
        
                return response()->json($response, 400);
            }
        
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
            } else {
                $imagePath = null;
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
                'numeroTelephone' => $request->numeroTelephone, 
                'image' => $imagePath, 
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

        $validator = Validator::make($request->all(), [
            'nom' => 'string',
            'prenom' => 'string',
            'email' => 'email|unique:users,email,' . $user->id,
            'ville' => 'string',
            'adresse' => 'string',
            'password' => 'string',
            'confirmationMotDePasse' => 'string|same:password',
            'profession' => 'string',
            'specialties' => 'array',
            'joursDisponibilite' => 'array',
            'heureDebut' => 'string',
            'heureFin' => 'string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                "ResultInfo" => [
                    'Success' => false,
                    'ErrorMessage' => $validator->errors(),
                ],
                "ResultData" => []
            ], 400);
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $imagePath = $request->file('image')->store('images', 'public');
        } else {
            $imagePath = $user->image;
        }

        $user->update([
            'nom' => $request->nom ?? $user->nom,
            'prenom' => $request->prenom ?? $user->prenom,
            'email' => $request->email ?? $user->email,
            'ville' => $request->ville ?? $user->ville,
            'adresse' => $request->adresse ?? $user->adresse,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'profession' => $request->profession ?? $user->profession,
            'specialties' => $request->specialties ?? $user->specialties,
            'joursDisponibilite' => $request->joursDisponibilite ?? $user->joursDisponibilite,
            'heureDebut' => $request->heureDebut ?? $user->heureDebut,
            'heureFin' => $request->heureFin ?? $user->heureFin,
            'image' => $imagePath, 
        ]);

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
    
        $resetCode = mt_rand(100000, 999999); 
    
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $resetCode,
            'created_at' => now(),
        ]);
    
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


    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

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
    public function profil(Request $request)
    {
        try {
            $user = $request->user();
    
            return response()->json([
                "ResultInfo" => [
                    'Success' => true,
                ],
                "ResultData" => [
                    'data' => $user,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "ResultInfo" => [
                    'Success' => false,
                    'ErrorMessage' => "Erreur lors de la récupération du profil de l'utilisateur",
                ],
                "ResultData" => [
                    'error' => $e->getMessage(),
                ]
            ], 500);
        }
    }

    public function mettreAJourProfil(Request $request)
    {
        try {
            $user = $request->user();
    
            $validator = Validator::make($request->all(), [
                'nom' => 'string',
                'prenom' => 'string',
                'email' => 'email|unique:users,email,' . $user->id,

                'password' => 'nullable|string|min:6']);
        
    
            if ($validator->fails()) {
                return response()->json([
                    "ResultInfo" => [
                        'Success' => false,
                        'ErrorMessage' => 'Validation failed',
                    ],
                    "ResultData" => [
                        'errors' => $validator->errors(),
                    ]
                ], 400);
            }
    
            if ($request->has('password')) {
                $request->merge(['password' => bcrypt($request->password)]);
            }
    
            $user->update($request->all());
    
            return response()->json([
                "ResultInfo" => [
                    'Success' => true,
                    'Message' => 'Profil mis à jour avec succès',
                ],
                "ResultData" => []
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "ResultInfo" => [
                    'Success' => false,
                    'ErrorMessage' => "Erreur lors de la mise à jour du profil de l'utilisateur",
                ],
                "ResultData" => []
            ], 500);
        }
    }
}