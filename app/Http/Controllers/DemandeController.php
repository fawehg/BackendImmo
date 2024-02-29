<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;

class DemandeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'service' => 'required',
            'panneType' => 'required',
            'city' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $demande = new Demande();
        $demande->service = $request->service;
        $demande->panneType = $request->panneType;
        $demande->city = $request->city;
        $demande->date = $request->date;
        $demande->time = $request->time;
        $demande->description = $request->description;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images');
            $demande->image = $imagePath;
        }

        $demande->save();

        return response()->json(['message' => 'Demande created successfully'], 201);
    }
}
