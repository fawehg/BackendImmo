<?php

namespace App\Http\Controllers;

use App\Models\Specialite;

class SpecialiteController extends Controller
{
    public function index()
    {
        $specialites = Specialite::all();
        return response()->json($specialites);
    }
}

