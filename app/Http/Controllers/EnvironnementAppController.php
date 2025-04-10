<?php

namespace App\Http\Controllers;

use App\Models\EnvironnementApp;
use Illuminate\Http\Request;

class EnvironnementAppController extends Controller
{
    public function index()
    {
        return response()->json(EnvironnementApp::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255'
        ]);

        $env = EnvironnementApp::create($validated);

        return response()->json($env, 201);
    }
}
