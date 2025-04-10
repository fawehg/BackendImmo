<?php

namespace App\Http\Controllers;
use App\Models\TypeTerrain;

use Illuminate\Http\Request;

class TypeTerrainController extends Controller
{
    public function index()
    {
        $types = TypeTerrain::all(); // ou TypeSol::all()
        return response()->json($types);
    }
}
