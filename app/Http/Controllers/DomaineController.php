<?php

namespace App\Http\Controllers;

use App\Models\Domaine;
use App\Models\Specialite;
use Illuminate\Http\Request;

class DomaineController extends Controller
{
    public function index()
    {
        return Domaine::with('specialites')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_domaine' => 'required|string|max:255|unique:domaines',
            'specialites' => 'required|array',
            'specialites.*.nom_specialite' => 'required|string|max:255|unique:specialites',
        ]);

        $domaine = Domaine::create($request->only('nom_domaine'));

        foreach ($request->specialites as $specialiteData) {
            $specialite = new Specialite($specialiteData);
            $domaine->specialites()->save($specialite);
        }

        return response()->json($domaine->load('specialites'), 201);
    }
}
