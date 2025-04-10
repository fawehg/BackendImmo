<?php

namespace App\Http\Controllers;

use App\Models\EtageVilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EtageVillaController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|exists:types,id',
            'categorie_id' => 'required|exists:categories,id',
            'ville_id' => 'required|exists:ville,id',
            'delegation_id' => 'required|exists:delegations,id',
            'environnement_id' => 'required|exists:environnements,id',
            'adresse' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'superficie' => 'required|integer|min:1',
            'numero_etage' => 'required|integer|min:0',
            'acces_independant' => 'boolean',
            'parking_inclus' => 'boolean',
            'annee_construction' => 'required|integer|min:1900|max:'.date('Y'),

            'images' => 'nullable|array',
            
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('images');
        $data['acces_independant'] = $request->boolean('acces_independant');
        $data['parking_inclus'] = $request->boolean('parking_inclus');

        // Gestion des images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/etage_villas');
                $imagePaths[] = str_replace('public/', 'storage/', $path);
            }
            $data['images'] = json_encode($imagePaths);
        }

        $etageVilla = EtageVilla::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $etageVilla
        ], 201);
    }
}