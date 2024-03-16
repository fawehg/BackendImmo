<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;

class DemandeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'domaines' => 'required|string',
            'specialites' => 'required|string',
            'city' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $demande = new Demande();
        $demande->domaines = $request->domaines;
        $demande->specialites = $request->specialites;
        $demande->city = $request->city;
        $demande->date = $request->date;
        $demande->time = $request->time;
        $demande->description = $request->description;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
            $demande->image = $imagePath;
        }

        $demande->save();

        return response()->json(['message' => 'Demande créée avec succès'], 201);
    }
}
