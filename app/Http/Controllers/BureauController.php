<?php

// app/Http/Controllers/BureauController.php
namespace App\Http\Controllers;

use App\Models\Bureau;
use App\Models\Type;
use App\Models\Categorie;
use App\Models\Ville;
use App\Models\Delegation;
use App\Models\Environnement;
use App\Models\Caracteristique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BureauController extends Controller
{
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|numeric|min:0',
            'superficie_couverte' => 'required|numeric|min:0',
            'nombre_bureaux' => 'required|integer|min:1',
            'nombre_toilettes' => 'required|integer|min:0',
            'adresse' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'type_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:ville,id',
            'delegation_id' => 'required|exists:delegations,id',
            'environnement_id' => 'required|exists:environnements,id',
            'caracteristiques' => 'nullable|array',
            'caracteristiques.*' => 'exists:caracteristique_bureaux,id'
        ]);
    
        // Traitement des images
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('bureaux_images', 'public');
                $imagesPaths[] = $path;
            }
        }
    
        // Création du bureau avec les données validées
        $bureau = Bureau::create([
            'titre' => $validatedData['titre'],
            'description' => $validatedData['description'],
            'prix' => $validatedData['prix'],
            'superficie' => $validatedData['superficie'],
            'superficie_couverte' => $validatedData['superficie_couverte'],
            'nombre_bureaux' => $validatedData['nombre_bureaux'],
            'nombre_toilettes' => $validatedData['nombre_toilettes'],
            'adresse' => $validatedData['adresse'],
            'images' => !empty($imagesPaths) ? json_encode($imagesPaths) : null,
            'type_id' => $validatedData['type_id'],
            'categorie_id' => $validatedData['categorie_id'],
            'ville_id' => $validatedData['ville_id'],
            'delegation_id' => $validatedData['delegation_id'],
            'environnement_id' => $validatedData['environnement_id']
        ]);
    
        // Attachement des caractéristiques sélectionnées
        if (isset($validatedData['caracteristiques'])) {
            $bureau->caracteristiques()->sync($validatedData['caracteristiques']);
        }
    
        // Redirection avec message de succès
        return response()->json([
            'message' => 'bureaux créé avec succès',
            'data' => $bureau
        ], 201);
    }
    }
    
