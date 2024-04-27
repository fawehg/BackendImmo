<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Demande;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class RechercheOuvrierController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Requête pour récupérer les ouvriers qui correspondent aux critères
            $query = User::whereHas( function($q) use ($request) {
                $q->where('profession', $request->domaines)
                  ->where('specialties', $request->specialites)
                  ->where('ville', $request->city);
            });

            // Si un domaine est spécifié, filtrer également par domaine
            if ($request->has('domaine_id')) {
                $query->where('domaine_id', $request->domaine_id);
            }

            // Si une spécialité est spécifiée, filtrer également par spécialité
            if ($request->has('specialite_id')) {
                $query->where('specialite_id', $request->specialite_id);
            }

            // Filtrer également par ville de la table demandes
            if ($request->has('ville')) {
                $query->whereHas('demandes', function($q) use ($request) {
                    $q->where('city', $request->ville);
                });
            }

            // Récupérer les ouvriers filtrés
            $ouvriers = $query->get();

            // Vérifier si l'utilisateur est authentifié
            $client = Auth::guard('client_api')->user();
            if (!$client) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            // Générer le token JWT à partir de l'utilisateur authentifié
            $token = JWTAuth::fromUser($client);

            return response()->json([
                "ResultInfo" => [
                    'Success' => true,
                    'ErrorMessage' => "",
                ],
                "ResultData" => [
                    'ouvriers' => $ouvriers,
                    'token' => $token,
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
