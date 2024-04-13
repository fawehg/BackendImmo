<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\QueryException;

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

            return response()->json([
                "ResultInfo" => [
                    'Success' => true,
                    'ErrorMessage' => "",
                ],
                "ResultData" => $ouvriers
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
