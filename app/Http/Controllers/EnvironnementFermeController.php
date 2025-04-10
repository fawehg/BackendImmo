<?php


namespace App\Http\Controllers;

use App\Models\EnvironnementFerme;
use Illuminate\Http\Request;

class EnvironnementFermeController extends Controller
{
    public function index()
    {
        $environnements = EnvironnementFerme::all();
        return response()->json($environnements);
    }
}