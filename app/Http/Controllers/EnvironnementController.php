<?php

namespace App\Http\Controllers;

use App\Models\Environnement;
use Illuminate\Http\Request;

class EnvironnementController extends Controller
{
    public function index()
    {
        return response()->json(Environnement::all());
    }
}
