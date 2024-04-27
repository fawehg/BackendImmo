<?php
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Models\User;

class RechercheOuvrierController extends Controller
{
    public function index(Request $request)
    {
        try {
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
