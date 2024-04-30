<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Database\QueryException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Demande;

class RechercheOuvrierController extends Controller
{
    public function rechercherOuvriers(Request $request)
    {
        try {
            // Récupérer l'utilisateur client actuel
            $client = Auth::guard('client_api')->user();
            if (!$client) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }
    
            // Récupérer l'ID de la dernière demande du client
            $demande_id = Demande::where('user_id', $client->id)->latest()->value('id');
    
            // Recherche des ouvriers en fonction des critères fournis
            $query = User::query();
    
            if ($request->has('specialite_id')) {
                $query->where('specialite_id', $request->specialite_id);
            }
    
            if ($request->has('domaine_id')) {
                $query->where('domaine_id', $request->domaine_id);
            }
    
            if ($request->has('ville')) {
                $query->where('ville', $request->ville);
            }
    
            $ouvriers = $query->get();
    
            // Récupérer le token JWT
            $token = JWTAuth::fromUser($client);
    
            return response()->json([
                "ResultInfo" => [
                    'Success' => true,
                    'ErrorMessage' => "",
                ],
                "ResultData" => [
                    'ouvriers' => $ouvriers,
                    'token' => $token,
                    'demande_id' => $demande_id // Inclure l'ID de la demande dans la réponse
                ]
            ]);
        } catch (QueryException $e) {
            return response()->json([
                "ResultInfo" => [
                    'Success' => false,
                    'ErrorMessage' => $e->getMessage(),
                ],
                "ResultData" => null
            ]);
        }
    }
    
}