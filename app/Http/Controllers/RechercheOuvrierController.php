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
            $request->validate([
                'city' => 'string'
            ]);

            $city = $request->input('city');

            $ouvriers = DB::table('demandes')
                        ->join('users', function ($join) {
                            $join
                                 ->on('demandes.city', '=', 'users.ville');
                        })
                        ->where('demandes.city', $city)
                        ->select('users.*')
                        ->get();
                        
            $client = Auth::guard('client_api')->user();
            if (!$client) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }
    
            $token = JWTAuth::fromUser($client);
    
            $demande_id = $request->input('demande_id', null);
    
            return response()->json([
                "ResultInfo" => [
                    'Success' => true,
                    'ErrorMessage' => "",
                ],
                "ResultData" => [
                    'ouvriers' => $ouvriers,
                    'token' => $token,
                    'demande_id' => $demande_id, 
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