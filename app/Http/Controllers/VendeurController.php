<?php

namespace App\Http\Controllers;

use App\Models\Vendeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordCode;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth;

class VendeurController extends Controller
{
    public function indexe()
    {
        $vendeurs = Vendeur::all();
        return view('vendeurs.index', compact('vendeurs'));
    }

    public function createe()
    {
        return view('vendeurs.create');
    }

    public function storee(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'ville' => 'required|string',
            'adresse' => 'required|string',
            'email' => 'required|email|unique:vendeurs',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        Vendeur::create($validated);

        return redirect()->route('vendeurs')->with('success', 'Vendeur créé avec succès.');
    }

    public function showw($id)
    {
        $vendeur = Vendeur::findOrFail($id);
        return view('vendeurs.show', compact('vendeur'));
    }

    public function editt($id)
    {
        $vendeur = Vendeur::findOrFail($id);
        return view('vendeurs.edit', compact('vendeur'));
    }

    public function updatee(Request $request, $id)
    {
        $vendeur = Vendeur::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'ville' => 'required|string',
            'adresse' => 'required|string',
            'email' => 'required|email|unique:vendeurs,email,'.$id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $vendeur->update($validated);

        return redirect()->route('vendeurs')->with('success', 'Vendeur mis à jour avec succès.');
    }

    public function destroyy($id)
    {
        $vendeur = Vendeur::findOrFail($id);
        $vendeur->delete();

        return redirect()->route('vendeurs')->with('success', 'Vendeur supprimé avec succès.');
    }
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'ville' => 'required|string',
            'adresse' => 'required|string',
            'email' => 'required|email|unique:vendeurs',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $vendeur = Vendeur::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'ville' => $request->ville,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($vendeur);

        return response()->json([
            'vendeur' => $vendeur,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Utiliser le guard 'vendeurs' pour l'authentification
        if (!$token = auth('vendeurs')->attempt($credentials)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        return response()->json([
            'token' => $token,
            'vendeur' => auth('vendeurs')->user(),
        ]);
    }
    public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    // Remplacer Client par Vendeur
    $vendeur = Vendeur::where('email', $request->email)->first();

    if (!$vendeur) {
        return response()->json([
            "ResultInfo" => [
                'Success' => false,
                'ErrorMessage' => "Le vendeur avec cet e-mail n'existe pas.",
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

    Mail::to($vendeur->email)->send(new ResetPasswordCode($resetCode));

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

    // Remplacer Client par Vendeur
    $vendeur = Vendeur::where('email', $request->email)->first();
    $vendeur->password = Hash::make($request->password);
    $vendeur->save();

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

    // Afficher le profil du vendeur
    public function show()
    {
        $vendeur = Auth::guard('vendeurs')->user();
        return response()->json($vendeur);
    }

    // Mettre à jour le profil du vendeur
    public function update(Request $request)
    {
        $vendeur = Auth::guard('vendeurs')->user();

        $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'entreprise' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
            'ville' => 'sometimes|string|max:255',
            'adresse' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:vendeurs,email,'.$vendeur->id,
            'password' => 'sometimes|string|min:8|confirmed',
            'description' => 'sometimes|string|max:1000',
            'logo' => 'sometimes|string',
            'site_web' => 'sometimes|url|max:255'
        ]);

        // Ne mettre à jour que les champs fournis
        $vendeur->fill($request->all());
        $vendeur->save();

        return response()->json([
            'message' => 'Profil vendeur mis à jour avec succès',
            'vendeur' => $vendeur
        ]);
    }

    // Supprimer le compte vendeur
    public function destroy()
    {
        $vendeur = Auth::guard('vendeurs')->user();
        
        // Ajouter ici toute logique supplémentaire avant suppression
        // Par exemple : suppression des biens associés, etc.
        
        $vendeur->delete();
        
        return response()->json([
            'message' => 'Compte vendeur supprimé avec succès'
        ]);
    }
}
