<?php

namespace App\Http\Controllers;
use App\Models\TypeSol;

use Illuminate\Http\Request;

class TypeSolController extends Controller
{
    public function index()
    {
        $types =  TypeSol::all(); // ou
        return response()->json($types);
    }
}
